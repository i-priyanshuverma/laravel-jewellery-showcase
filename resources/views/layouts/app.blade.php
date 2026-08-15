<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sonar Haat') }}</title>

        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,400&display=swap" rel="stylesheet">

        <script>
            if (localStorage.getItem('color-theme') === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 min-h-screen flex flex-col selection:bg-amber-500 selection:text-slate-950">
        <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex flex-col transition-colors duration-200">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800/80 shadow-sm sticky top-16 z-30 transition-colors duration-200">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="flex-grow">
                {{ $slot }}
            </main>

            <footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800/80 py-8 mt-16 text-slate-500 dark:text-slate-400 text-sm transition-colors duration-200">
                <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left">
                    <div>
                        <span class="text-sm font-extrabold tracking-wider text-slate-900 dark:text-slate-100 uppercase">SONAR HAAT</span>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Multi-Vendor Goldsmith Showcase & Certified Jewellery Platform.</p>
                    </div>
                    <div class="text-xs text-slate-400 dark:text-slate-500">
                        &copy; {{ date('Y') }} Sonar Haat. All rights reserved.
                    </div>
                </div>
            </footer>
        </div>

        <x-confirm-modal />

        @auth
            <div x-data="realtimeAlertManager({{ auth()->id() }}, {{ auth()->user()->isVendor() ? 'true' : 'false' }}, {{ auth()->user()->isAdmin() ? 'true' : 'false' }})"
                 class="fixed top-24 right-6 z-50 flex flex-col gap-3 max-w-sm w-full pointer-events-none">
                <template x-for="alert in alerts" :key="alert.id">
                    <div class="pointer-events-auto p-4 rounded-2xl shadow-2xl border transition-all duration-300 transform translate-y-0 animate-in fade-in slide-in-from-top-4"
                         :class="{
                            'bg-emerald-600 text-white border-emerald-500': alert.type === 'approved' || alert.type === 'reactivated',
                            'bg-rose-600 text-white border-rose-500': alert.type === 'suspended',
                            'bg-slate-900 text-white border-slate-700': alert.type === 'info'
                         }">
                        <div class="flex items-start gap-3">
                            <span class="text-xl" x-text="alert.icon"></span>
                            <div class="flex-1 min-w-0">
                                <h5 class="text-xs font-extrabold uppercase tracking-wider" x-text="alert.title"></h5>
                                <p class="text-xs mt-0.5 opacity-95 leading-relaxed" x-text="alert.message"></p>
                            </div>
                            <button @click="remove(alert.id)" class="text-white/80 hover:text-white text-sm font-bold ml-1">✕</button>
                        </div>
                    </div>
                </template>
            </div>

            <script>
                function realtimeAlertManager(userId, isVendor, isAdmin) {
                    return {
                        alerts: [],
                        init() {
                            if (typeof window.Echo === 'undefined') return;

                            if (isVendor && userId) {
                                window.Echo.private('vendor.' + userId)
                                    .listen('.VendorStatusUpdated', (e) => {
                                        let icon = '🎉';
                                        let title = 'Account Approved!';
                                        if (e.action === 'suspended') {
                                            icon = '⚠️';
                                            title = 'Account Suspended';
                                        } else if (e.action === 'reactivated') {
                                            icon = '✅';
                                            title = 'Account Reactivated';
                                        }
                                        this.addAlert(title, e.message, e.action, icon);

                                        setTimeout(() => {
                                            if (e.action === 'suspended') {
                                                window.location.href = "{{ route('vendor.dashboard') }}";
                                            } else {
                                                window.location.reload();
                                            }
                                        }, 1500);
                                    });
                            }

                            if (isAdmin) {
                                window.Echo.private('admin.inventory')
                                    .listen('.VendorStatusUpdated', (e) => {
                                        this.addAlert('Vendor Status Changed', `${e.vendorName} status updated to ${e.status}.`, 'info', '🏪');
                                    });
                            }
                        },
                        addAlert(title, message, type, icon) {
                            const id = Date.now();
                            this.alerts.push({ id, title, message, type, icon });
                            setTimeout(() => this.remove(id), 8000);
                        },
                        remove(id) {
                            this.alerts = this.alerts.filter(a => a.id !== id);
                        }
                    };
                }
            </script>
        @endauth
    </body>
</html>
