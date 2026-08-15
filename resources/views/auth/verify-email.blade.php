<x-guest-layout>
    <div class="mb-4 text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-semibold text-xs sm:text-sm text-emerald-600 dark:text-emerald-400">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-6 flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-800">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Resend Email') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-xs text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
