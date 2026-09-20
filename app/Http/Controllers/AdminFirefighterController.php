<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminFirefighterController extends Controller
{
    public function index()
    {
        // Fetch users with roles or filter by firefighter personnel
        $firefighters = User::latest()->get();

        return view('admin.firefighters.index', compact('firefighters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'badge_number' => ['required', 'string', 'max:50', 'unique:users,badge_number'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'rank' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'badge_number' => $request->badge_number,
            'email' => $request->email,
            'rank' => $request->rank,
            'password' => Hash::make($request->password),
        ]);

        // Assign Spatie Role
        if (method_exists($user, 'assignRole')) {
            $user->assignRole('Firefighter');
        }

        return redirect()->back()->with('status', "Firefighter {$user->name} registered successfully!");
    }
}