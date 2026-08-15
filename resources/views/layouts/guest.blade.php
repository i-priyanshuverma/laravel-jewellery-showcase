<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sonar Haat') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <!-- Google Fonts: Inter & Playfair Display -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,400&display=swap" rel="stylesheet">

        <!-- Early Theme Initialization -->
        <script>
            if (localStorage.getItem('color-theme') === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 selection:bg-amber-500 selection:text-slate-950 transition-colors duration-200">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-16 sm:pt-0 bg-slate-50 dark:bg-slate-950 relative overflow-hidden px-4">
            <!-- Top Controls (Catalogue link, Language Switcher, Theme Toggle) -->
            <div class="absolute top-4 left-4 right-4 flex items-center justify-between z-20">
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-amber-600 dark:hover:text-amber-400 transition-colors bg-white/80 dark:bg-slate-900/80 backdrop-blur-md px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                    <span>&larr;</span>
                    <span>{{ __('Explore Catalogue') }}</span>
                </a>

                <div class="flex items-center gap-2">
                    <!-- Language Switcher Dropdown -->
                    <div class="relative" x-data="{ langOpen: false }">
                        <button @click="langOpen = !langOpen" class="h-8 px-2.5 text-[11px] font-bold uppercase rounded-xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-md hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 transition-all flex items-center gap-1 shadow-sm">
                            <span>{{ strtoupper(app()->getLocale()) }}</span>
                            <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="langOpen" @click.outside="langOpen = false" x-cloak class="absolute right-0 mt-1.5 w-32 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xl py-1 z-50 text-xs">
                            <a href="{{ route('locale.switch', 'en') }}" class="block px-3 py-1.5 hover:bg-amber-50 dark:hover:bg-slate-800 {{ app()->getLocale() === 'en' ? 'font-bold text-amber-600' : 'text-slate-700 dark:text-slate-300' }}">English</a>
                            <a href="{{ route('locale.switch', 'hi') }}" class="block px-3 py-1.5 hover:bg-amber-50 dark:hover:bg-slate-800 {{ app()->getLocale() === 'hi' ? 'font-bold text-amber-600' : 'text-slate-700 dark:text-slate-300' }}">हिन्दी (Hindi)</a>
                            <a href="{{ route('locale.switch', 'ar') }}" class="block px-3 py-1.5 hover:bg-amber-50 dark:hover:bg-slate-800 {{ app()->getLocale() === 'ar' ? 'font-bold text-amber-600' : 'text-slate-700 dark:text-slate-300' }}">العربية (Arabic)</a>
                        </div>
                    </div>

                    <!-- Theme Toggle Switcher -->
                    <button type="button"
                            x-data="{
                                isDark: document.documentElement.classList.contains('dark'),
                                toggle() {
                                    this.isDark = !this.isDark;
                                    if (this.isDark) {
                                        document.documentElement.classList.add('dark');
                                        localStorage.setItem('color-theme', 'dark');
                                    } else {
                                        document.documentElement.classList.remove('dark');
                                        localStorage.setItem('color-theme', 'light');
                                    }
                                }
                            }"
                            @click="toggle()"
                            class="w-8 h-8 rounded-xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-md hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-amber-400 transition-all flex items-center justify-center shadow-sm flex-shrink-0"
                            :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
                            aria-label="Toggle theme">
                        <svg x-show="isDark" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg x-show="!isDark" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Subtle background glows -->
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-amber-500/5 dark:bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-amber-600/5 dark:bg-yellow-600/10 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Brand Header -->
            <div class="relative z-10 text-center space-y-2 mb-4">
                <a href="/" class="inline-flex flex-col items-center group">
                    <x-application-logo class="w-12 h-12 shadow-lg shadow-amber-500/20 group-hover:scale-105 transition-transform" />
                    <span class="text-xl font-black tracking-widest text-slate-900 dark:text-slate-100 mt-2 uppercase block">SONAR HAAT</span>
                    <span class="text-[10px] text-amber-600 dark:text-amber-400 font-bold uppercase tracking-wider">{{ __('Multi-Vendor Goldsmith Showcase') }}</span>
                </a>
            </div>

            <!-- Auth Form Card -->
            <div class="w-full sm:max-w-md px-6 sm:px-8 py-8 bg-white dark:bg-slate-900/90 backdrop-blur-xl border border-slate-200 dark:border-slate-800 shadow-xl dark:shadow-2xl rounded-3xl relative z-10 transition-colors duration-200">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="mt-8 mb-4 text-center text-xs text-slate-400 dark:text-slate-500 relative z-10">
                &copy; {{ date('Y') }} Sonar Haat. {{ __('All rights reserved.') }}
            </div>
        </div>
    </body>
</html>
