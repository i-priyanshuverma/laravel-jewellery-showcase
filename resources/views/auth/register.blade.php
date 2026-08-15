<x-guest-layout>
    <div class="mb-6 text-center space-y-1">
        <h2 class="text-2xl font-extrabold text-slate-900 dark:text-slate-100">Vendor Registration</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400">Join Sonar Haat & showcase your artisanal jewellery</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
        </div>

        <!-- Business Name -->
        <div>
            <x-input-label for="business_name" :value="__('Business / Brand Name')" />
            <x-text-input id="business_name" class="block mt-1 w-full" type="text" name="business_name" :value="old('business_name')" required placeholder="e.g. Heritage Jewels Studio" />
            <x-input-error :messages="$errors->get('business_name')" class="mt-1.5" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="vendor@jewellery.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full">
                {{ __('Register as Vendor') }}
            </x-primary-button>
        </div>

        <div class="text-center pt-4 border-t border-slate-100 dark:border-slate-800 text-xs text-slate-500 dark:text-slate-400">
            Already registered?
            <a href="{{ route('login') }}" class="font-bold text-amber-600 dark:text-amber-400 hover:text-amber-500 dark:hover:text-amber-300 hover:underline ml-1">
                Log in to account &rarr;
            </a>
        </div>
    </form>
</x-guest-layout>
