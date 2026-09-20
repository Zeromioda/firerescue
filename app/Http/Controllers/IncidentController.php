<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; // <-- Added missing import facade

class IncidentController extends Controller
{
    // Redirect user to their role-specific dashboard
    public function dashboard()
    {
        $user = Auth::user();

        // 1. Firefighter / Responder View
        if ($user->hasRole('Responder')) {
            $activeIncidents = Incident::whereIn('status', ['Dispatched', 'Under Control'])->latest()->get();
            $resolvedIncidents = Incident::where('status', 'Resolved')->latest()->take(5)->get();
            return view('dashboards.firefighter', compact('activeIncidents', 'resolvedIncidents'));
        }

        // 2. Admin / Dispatcher View
        $incidents = Incident::with('reporter')->latest()->get();
        $stats = [
            'total' => Incident::count(),
            'active' => Incident::whereIn('status', ['Pending', 'Dispatched', 'Under Control'])->count(),
            'resolved' => Incident::where('status', 'Resolved')->count(),
        ];

        return view('dashboards.admin', compact('incidents', 'stats'));
    }

    // Toggle Duty Availability Status for Responders
    public function toggleAvailability(Request $request)
    {
        $user = Auth::user();
        $user->is_available = !$user->is_available;
        $user->save();

        return redirect()->back()->with('success', 'Duty availability status updated.');
    }

    // Admin: Create New Emergency Call View
    public function create()
    {
        return view('incidents.create');
    }

    // Admin: Store Incident Call
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'severity' => 'required|string',
            'location_address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'description' => 'required|string',
        ]);

        Incident::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'severity' => $request->severity,
            'location_address' => $request->location_address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'description' => $request->description,
            'status' => 'Dispatched',
        ]);

        return redirect()->route('dashboard')->with('success', '🚨 Incident dispatched to response teams!');
    }

    // Firefighter / Admin: Update On-Scene Status
    public function updateStatus(Request $request, Incident $incident)
    {
        $request->validate(['status' => 'required|string']);
        $incident->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Incident status updated.');
    }

    // Firefighter: Submit Final After-Action Incident Report
    public function submitFinalReport(Request $request, Incident $incident)
    {
        $request->validate([
            'after_action_report' => 'required|string|min:10',
        ]);

        $rawReport = $request->after_action_report;
        $aiSummary = null;
        $apiKey = env('OPENAI_API_KEY');

        if ($apiKey) {
            try {
                $prompt = "You are an expert Fire Department Command Analyst for Barangay 177 Camarin, Caloocan City. " .
                    "Convert the following raw firefighter after-action report into a professional, highly structured administrative summary.\n\n" .
                    "RAW REPORT:\n\"{$rawReport}\"\n\n" .
                    "Provide the response strictly in this formatted structure:\n" .
                    "• EXECUTIVE SUMMARY: (Brief overview)\n" .
                    "• INCIDENT CAUSE & ORIGIN: (Identified cause)\n" .
                    "• CASUALTIES & DAMAGE: (Injuries, fatalities, structural impact)\n" .
                    "• RESPONSE & EXTINGUISHMENT: (Apparatus used, response time, status)";

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You format raw emergency responder notes into structured executive fire reports.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.3,
                ]);

                if ($response->successful()) {
                    $aiSummary = $response->json()['choices'][0]['message']['content'] ?? null;
                }
            } catch (\Exception $e) {
                $aiSummary = "• EXECUTIVE SUMMARY: " . $rawReport;
            }
        }

        if (!$aiSummary) {
            $aiSummary = "• EXECUTIVE SUMMARY: " . $rawReport;
        }

        $incident->update([
            'status' => 'Resolved',
            'after_action_report' => $rawReport,
            'ai_summary' => $aiSummary,
        ]);

        return redirect()->back()->with('success', '🚨 Report submitted & AI summary generated!');
    }
}