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
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-8 sm:pt-0 bg-slate-50 dark:bg-slate-950 relative overflow-hidden px-4">
            <!-- Subtle background glows -->
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-amber-500/5 dark:bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-amber-600/5 dark:bg-yellow-600/10 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Brand Header -->
            <div class="relative z-10 text-center space-y-2 mb-4">
                <a href="/" class="inline-flex flex-col items-center group">
                    <x-application-logo class="w-12 h-12 shadow-lg shadow-amber-500/20 group-hover:scale-105 transition-transform" />
                    <span class="text-xl font-black tracking-widest text-slate-900 dark:text-slate-100 mt-2 uppercase block">SONAR HAAT</span>
                    <span class="text-[10px] text-amber-600 dark:text-amber-400 font-bold uppercase tracking-wider">Multi-Vendor Goldsmith Showcase</span>
                </a>
            </div>

            <!-- Auth Form Card -->
            <div class="w-full sm:max-w-md px-6 sm:px-8 py-8 bg-white dark:bg-slate-900/90 backdrop-blur-xl border border-slate-200 dark:border-slate-800 shadow-xl dark:shadow-2xl rounded-3xl relative z-10 transition-colors duration-200">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
