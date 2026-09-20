<x-guest-layout>
    <div class="fixed inset-0 flex items-center justify-center p-4 bg-background">
        <div class="w-full max-w-md bg-card border border-border rounded-2xl p-8 shadow-2xl space-y-6">
            
            <!-- Header Text -->
            <div class="space-y-2 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-primary/10 text-primary mb-1 text-xl">
                    🛡️
                </div>
                <h2 class="font-display font-bold text-xl text-foreground tracking-wide">
                    Verify OTP & Reset Password
                </h2>
                <p class="text-xs text-muted leading-relaxed">
                    Please enter your email, the 6-digit verification code sent to your inbox, and your new password.
                </p>
            </div>

            <!-- Session Status / Notifications -->
            @if (session('status'))
                <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-lg p-3 text-xs text-emerald-400 font-medium text-center">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.otp.reset') }}" class="space-y-4">
                @csrf

                <!-- Email Address -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-1.5">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', session('email')) }}" required autofocus 
                        class="w-full bg-card-alt border border-border rounded-xl px-4 py-2.5 text-sm text-foreground focus:ring-1 focus:ring-primary focus:border-primary">
                    <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                </div>

                <!-- 6-Digit OTP Code -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-1.5">6-Digit OTP Code</label>
                    <input type="text" name="otp" maxlength="6" placeholder="123456" required 
                        class="w-full bg-card-alt border border-border rounded-xl px-4 py-3 text-center font-mono font-bold text-lg tracking-widest text-foreground focus:ring-1 focus:ring-primary focus:border-primary">
                    <x-input-error :messages="$errors->get('otp')" class="mt-1.5" />
                </div>

                <!-- New Password -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-1.5">New Password</label>
                    <input type="password" name="password" required autocomplete="new-password" placeholder="••••••••"
                        class="w-full bg-card-alt border border-border rounded-xl px-4 py-2.5 text-sm text-foreground focus:ring-1 focus:ring-primary focus:border-primary">
                    <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-muted mb-1.5">Confirm New Password</label>
                    <input type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••"
                        class="w-full bg-card-alt border border-border rounded-xl px-4 py-2.5 text-sm text-foreground focus:ring-1 focus:ring-primary focus:border-primary">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 bg-primary text-primary-foreground font-display font-bold text-xs rounded-xl shadow-lg hover:opacity-90 transition uppercase tracking-wider mt-2">
                    Reset Password
                </button>
            </form>

            <!-- Footer Link back to login -->
            <div class="text-center pt-2 border-t border-border">
                <a href="{{ route('login') }}" class="text-xs text-muted hover:text-foreground font-semibold transition">
                    ← Back to Login
                </a>
            </div>

        </div>
    </div>
</x-guest-layout>