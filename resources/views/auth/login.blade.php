<x-guest-layout>
    <div class="mb-6 text-center space-y-1">
        <h2 class="text-2xl font-extrabold text-slate-900 dark:text-slate-100">Welcome Back</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400">Sign in to your account</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="user@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer gap-2">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-amber-600 focus:ring-amber-500" name="remember">
                <span class="text-xs text-slate-600 dark:text-slate-400 font-medium">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-xs font-semibold text-amber-600 dark:text-amber-400 hover:text-amber-500 dark:hover:text-amber-300 hover:underline transition-colors" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <div class="text-center pt-4 border-t border-slate-100 dark:border-slate-800 text-xs text-slate-500 dark:text-slate-400">
            Don't have a vendor account?
            <a href="{{ route('register') }}" class="font-bold text-amber-600 dark:text-amber-400 hover:text-amber-500 dark:hover:text-amber-300 hover:underline ml-1">
                Register as Vendor &rarr;
            </a>
        </div>
    </form>
</x-guest-layout>
