<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-display font-black text-2xl text-foreground tracking-tight flex items-center gap-2">
                    👨‍🚒 {{ __('Firefighter Personnel Roster') }}
                </h2>
                <p class="text-xs text-muted font-mono uppercase tracking-wider mt-0.5">
                    BFAD Station 178 • Admin Command Only
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-background min-h-screen text-foreground">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 font-bold text-xs rounded-xl">
                    ✓ {{ session('status') }}
                </div>
            @endif

            <!-- Form: Register New Personnel -->
            <details class="bg-card border border-border rounded-2xl p-6 shadow-sm group" open>
                <summary class="font-black text-base text-foreground cursor-pointer flex items-center justify-between">
                    <span>+ Register New Firefighter / Personnel</span>
                    <span class="text-xs text-rose-500 font-bold uppercase tracking-wider">Admin Privileged Form</span>
                </summary>

                <form action="{{ route('admin.firefighters.store') }}" method="POST" class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @csrf

                    <div>
                        <label class="block text-[10px] font-bold uppercase text-muted mb-1">Full Name</label>
                        <input type="text" name="name" required placeholder="John Doe" class="w-full bg-background border border-border rounded-xl p-2.5 text-xs text-foreground">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase text-muted mb-1">Badge / Serial No.</label>
                        <input type="text" name="badge_number" required placeholder="BFAD-178-088" class="w-full bg-background border border-border rounded-xl p-2.5 text-xs text-foreground font-mono">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase text-muted mb-1">Official Email</label>
                        <input type="email" name="email" required placeholder="j.doe@bfad178.gov.ph" class="w-full bg-background border border-border rounded-xl p-2.5 text-xs text-foreground">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase text-muted mb-1">Rank / Position</label>
                        <select name="rank" required class="w-full bg-background border border-border rounded-xl p-2.5 text-xs text-foreground">
                            <option value="Firefighter I">Firefighter I</option>
                            <option value="Firefighter II">Firefighter II</option>
                            <option value="Senior Fire Officer">Senior Fire Officer</option>
                            <option value="Apparatus Driver / Engineer">Apparatus Driver / Engineer</option>
                            <option value="Dispatch Officer">Dispatch Officer</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase text-muted mb-1">Default Password</label>
                        <input type="password" name="password" required class="w-full bg-background border border-border rounded-xl p-2.5 text-xs text-foreground">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase text-muted mb-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" required class="w-full bg-background border border-border rounded-xl p-2.5 text-xs text-foreground">
                    </div>

                    <div class="sm:col-span-2 md:col-span-3 flex justify-end">
                        <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-500 text-white font-black text-xs rounded-xl shadow-md uppercase tracking-wider">
                            Register Personnel
                        </button>
                    </div>
                </form>
            </details>

            <!-- Personnel Roster Table -->
            <div class="bg-card border border-border rounded-2xl p-6 shadow-sm space-y-4">
                <h3 class="font-black text-lg text-foreground">Active Station Personnel Roster</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="border-b border-border uppercase text-[10px] font-mono text-muted">
                            <tr>
                                <th class="py-3 px-2">Badge No</th>
                                <th class="py-3 px-2">Name</th>
                                <th class="py-3 px-2">Email</th>
                                <th class="py-3 px-2">Rank</th>
                                <th class="py-3 px-2">Registered On</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @forelse($firefighters as $personnel)
                                <tr>
                                    <td class="py-3 px-2 font-mono font-bold text-rose-500">{{ $personnel->badge_number ?? 'N/A' }}</td>
                                    <td class="py-3 px-2 font-bold text-foreground">{{ $personnel->name }}</td>
                                    <td class="py-3 px-2 text-muted">{{ $personnel->email }}</td>
                                    <td class="py-3 px-2">{{ $personnel->rank ?? 'Personnel' }}</td>
                                    <td class="py-3 px-2 font-mono text-muted">{{ $personnel->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-muted italic">No personnel found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>