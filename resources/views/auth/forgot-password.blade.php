<x-guest-layout>
    <div class="mb-4 text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6 pt-4 border-t border-slate-100 dark:border-slate-800">
            <a href="{{ route('login') }}" class="text-xs text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200">&larr; Back to login</a>
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
