<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-xl text-slate-900 dark:text-slate-100 leading-tight">
                {{ __('Vendor Control Panel') }}
            </h2>
            <div class="text-xs text-slate-500 dark:text-slate-400">
                {{ __('Business:') }} <strong class="text-slate-800 dark:text-slate-200">{{ $user->vendorProfile?->business_name ?? $user->name }}</strong>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-900 dark:text-slate-100 transition-colors duration-200" x-data="vendorDashboardTracker({{ $user->id }}, {{ $activeReservationsCount }})">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash-message />

            <!-- Account Status Banner -->
            @if ($user->isPending())
                <div class="p-6 rounded-2xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30 text-amber-800 dark:text-amber-200">
                    <div class="flex items-start gap-4">
                        <span class="text-3xl">⏳</span>
                        <div>
                            <h3 class="text-lg font-bold text-amber-900 dark:text-amber-400">{{ __('Account Approval Pending') }}</h3>
                            <p class="text-sm mt-1 text-slate-700 dark:text-slate-300">{{ __('Thank you for registering! Your vendor account is currently pending review and approval by an administrator. Once approved, you will be able to create products, variants, and perform bulk CSV imports.') }}</p>
                        </div>
                    </div>
                </div>
            @elseif ($user->isSuspended())
                <div class="p-6 rounded-2xl bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/30 text-rose-800 dark:text-rose-200">
                    <div class="flex items-start gap-4">
                        <span class="text-3xl">⚠️</span>
                        <div>
                            <h3 class="text-lg font-bold text-rose-900 dark:text-rose-400">{{ __('Account Suspended') }}</h3>
                            <p class="text-sm mt-1 text-slate-700 dark:text-slate-300">{{ __('Your vendor account has been suspended by an administrator. Product management features are restricted while suspended. Please contact platform support for resolution.') }}</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-emerald-200 dark:border-emerald-500/30 shadow-sm dark:shadow-md flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">✅</span>
                        <div>
                            <h3 class="font-bold text-base text-emerald-700 dark:text-emerald-400">{{ __('Account Active & Approved') }}</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('You are a certified approved vendor on the jewellery showcase platform.') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('vendor.products.create') }}" class="px-4 py-2.5 bg-gold-gradient text-slate-950 text-xs font-bold uppercase tracking-wider rounded-xl shadow-md hover:shadow-lg hover:brightness-105 transition">{{ __('+ Add Product') }}</a>
                        <a href="{{ route('vendor.imports.create') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 text-amber-700 dark:text-amber-400 text-xs font-bold uppercase tracking-wider rounded-xl transition shadow-sm">📤 {{ __('Bulk CSV Import') }}</a>
                    </div>
                </div>
            @endif

            <!-- Vendor Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                <x-stat-card title="My Total Products"
                             :value="$totalProducts"
                             icon="💍"
                             color="amber"
                             :link-url="route('vendor.products.index')"
                             link-label="View All →" />

                <x-stat-card title="Active Showcase Items"
                             :value="$activeProducts"
                             icon="✨"
                             color="emerald"
                             :link-url="route('vendor.products.index')"
                             link-label="View Active →" />

                <x-stat-card title="Total Variants"
                             :value="$totalVariants"
                             icon="🏷️"
                             color="sky" />

                <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-3 transition-colors">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('Active Holds on My Items') }}</span>
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse" title="Live real-time count"></span>
                    </div>
                    <div class="flex items-baseline justify-between">
                        <span class="text-3xl font-extrabold text-purple-600 dark:text-purple-400 font-mono" x-text="activeHolds">{{ $activeReservationsCount }}</span>
                    </div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400 pt-2 border-t border-slate-100 dark:border-slate-800">
                        {{ __('Live customer checkout reservations') }}
                    </div>
                </div>
            </div>

            <!-- Quick Management Navigation -->
            @if ($user->isApproved())
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-xl space-y-4 transition-colors">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-lg text-slate-900 dark:text-slate-100">{{ __('Manage Catalogue & Uploads') }}</h3>
                        
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('vendor.products.index') }}" class="p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 hover:border-amber-500/50 rounded-xl transition group shadow-sm">
                            <div class="font-bold text-slate-800 dark:text-slate-200 group-hover:text-amber-600 dark:group-hover:text-amber-400">💍 {{ __('Product Portfolio') }}</div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('Manage listings, descriptions, categories, and images.') }}</p>
                        </a>
                        <a href="{{ route('vendor.imports.index') }}" class="p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 hover:border-amber-500/50 rounded-xl transition group shadow-sm">
                            <div class="font-bold text-slate-800 dark:text-slate-200 group-hover:text-amber-600 dark:group-hover:text-amber-400">📄 {{ __('CSV Imports') }}</div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('View bulk upload logs and real-time background progress.') }}</p>
                        </a>
                        <a href="{{ route('vendor.profile.edit') }}" class="p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 hover:border-amber-500/50 rounded-xl transition group shadow-sm">
                            <div class="font-bold text-slate-800 dark:text-slate-200 group-hover:text-amber-600 dark:group-hover:text-amber-400">🏪 {{ __('Vendor Profile') }}</div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('Update business name, phone, address, and logo.') }}</p>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function vendorDashboardTracker(vendorId, initialHolds) {
            return {
                vendorId: vendorId,
                activeHolds: initialHolds,

                init() {
                    if (window.Echo) {
                        window.Echo.private(`vendor.${this.vendorId}`)
                            .listen('.ProductStockUpdated', (e) => {
                                if (e.vendorActiveHoldsTotal !== undefined) {
                                    this.activeHolds = e.vendorActiveHoldsTotal;
                                }
                            })
                            .listen('.VendorStatusUpdated', (e) => {
                                // Live reload after toast displays
                                setTimeout(() => window.location.reload(), 1500);
                            });
                    }
                }
            };
        }
    </script>
</x-app-layout>
