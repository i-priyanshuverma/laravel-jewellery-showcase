<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-xl text-slate-900 dark:text-slate-100 leading-tight">
                {{ __('Platform Admin Dashboard') }}
            </h2>
            <span class="text-xs text-purple-700 dark:text-purple-300 font-bold px-3 py-1 bg-purple-100 dark:bg-purple-950/80 border border-purple-300 dark:border-purple-800 rounded-full uppercase tracking-wider">
                {{ __('System SuperAdmin') }}
            </span>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-900 dark:text-slate-100 transition-colors duration-200" x-data="adminDashboardTracker({{ $activeReservations }})">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <x-flash-message />

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <x-stat-card title="Total Vendors"
                             :value="$totalVendors"
                             icon="🏪"
                             color="amber"
                             :link-url="route('admin.vendors.index')"
                             link-label="View All →">
                    <span class="text-amber-600 dark:text-amber-400 font-bold">{{ $pendingVendors }} {{ __('Pending') }}</span> &bull;
                    <span class="text-emerald-600 dark:text-emerald-400 font-bold">{{ $approvedVendors }} {{ __('Approved') }}</span> &bull;
                    <span class="text-rose-600 dark:text-rose-400 font-bold">{{ $suspendedVendors }} {{ __('Suspended') }}</span>
                </x-stat-card>

                <x-stat-card title="Total Products"
                             :value="$totalProducts"
                             icon="💍"
                             color="emerald"
                             :link-url="route('admin.products.index')"
                             link-label="View All →">
                    <span class="text-emerald-600 dark:text-emerald-400 font-bold">{{ $activeProducts }} {{ __('Active') }}</span> {{ __('listed in showcase') }}
                </x-stat-card>

                <x-stat-card title="Jewellery Categories"
                             :value="$totalCategories"
                             icon="🏷️"
                             color="sky"
                             :link-url="route('admin.categories.index')"
                             link-label="Manage →">
                    {{ __('Admin managed taxonomy tree') }}
                </x-stat-card>

                <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-3 transition-colors">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('Active Stock Holds') }}</span>
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse" title="Live platform holds"></span>
                    </div>
                    <div class="flex items-baseline justify-between">
                        <span class="text-3xl font-extrabold text-purple-600 dark:text-purple-400 font-mono" x-text="activeHolds">{{ $activeReservations }}</span>
                    </div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400 pt-2 border-t border-slate-100 dark:border-slate-800">
                        {{ __('15-minute concurrency holds active') }}
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm dark:shadow-2xl transition-colors">
                <h3 class="font-bold text-lg text-slate-900 dark:text-slate-100 mb-4">{{ __('Attribute Management') }}</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('admin.metals.index') }}" class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 hover:border-amber-500/50 transition group shadow-sm">
                        <span class="text-2xl">⚙️</span>
                        <p class="text-sm font-bold text-slate-900 dark:text-slate-100 mt-2 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition">{{ __('Metals & Purities') }}</p>
                        <p class="text-[11px] text-slate-500 mt-1">{{ __('Gold, Silver, Platinum...') }}</p>
                    </a>
                    <a href="{{ route('admin.colours.index') }}" class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 hover:border-amber-500/50 transition group shadow-sm">
                        <span class="text-2xl">🎨</span>
                        <p class="text-sm font-bold text-slate-900 dark:text-slate-100 mt-2 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition">{{ __('Colours') }}</p>
                        <p class="text-[11px] text-slate-500 mt-1">{{ __('Yellow, Rose, White...') }}</p>
                    </a>
                    <a href="{{ route('admin.sizes.index') }}" class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 hover:border-amber-500/50 transition group shadow-sm">
                        <span class="text-2xl">📏</span>
                        <p class="text-sm font-bold text-slate-900 dark:text-slate-100 mt-2 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition">{{ __('Jewellery Sizes') }}</p>
                        <p class="text-[11px] text-slate-500 mt-1">{{ __('Ring, Necklace, Bangle...') }}</p>
                    </a>
                    <a href="{{ route('admin.stone-types.index') }}" class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 hover:border-amber-500/50 transition group shadow-sm">
                        <span class="text-2xl">💎</span>
                        <p class="text-sm font-bold text-slate-900 dark:text-slate-100 mt-2 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition">{{ __('Stone Types') }}</p>
                        <p class="text-[11px] text-slate-500 mt-1">{{ __('Diamond, Ruby, CZ...') }}</p>
                    </a>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm dark:shadow-2xl space-y-6 transition-colors">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4">
                    <div>
                        <h3 class="font-bold text-lg text-slate-900 dark:text-slate-100">{{ __('Vendors Requiring Approval') }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Review vendor profile information and verify documents before granting catalogue creation rights') }}</p>
                    </div>
                    <span class="px-3 py-1 bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30 text-amber-700 dark:text-amber-400 text-xs font-bold rounded-full">
                        {{ $pendingVendorsList->count() }} {{ __('Pending') }}
                    </span>
                </div>

                @if ($pendingVendorsList->isEmpty())
                    <div class="text-center py-8 text-slate-500 dark:text-slate-400 text-sm">
                        {{ __('No vendors currently awaiting review. All registrations are processed!') }}
                    </div>
                @else
                    <div class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach ($pendingVendorsList as $pendingVendor)
                            <div class="py-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-3">
                                        <h4 class="font-bold text-slate-900 dark:text-slate-200 text-base">
                                            {{ $pendingVendor->vendorProfile?->business_name ?? $pendingVendor->name }}
                                        </h4>
                                        <span class="text-[10px] px-2.5 py-0.5 rounded-full bg-amber-50 dark:bg-amber-950 border border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-300 uppercase font-bold">
                                            {{ __('Pending Review') }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ __('Owner:') }} {{ $pendingVendor->name }} ({{ $pendingVendor->email }}) &bull; {{ __('Registered:') }} {{ $pendingVendor->created_at->diffForHumans() }}
                                    </p>
                                </div>

                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.vendors.show', $pendingVendor) }}" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-xs text-slate-700 dark:text-slate-200 font-bold rounded-xl transition shadow-sm">
                                        {{ __('Review Profile') }}
                                    </a>
                                    <form method="POST" action="{{ route('admin.vendors.approve', $pendingVendor) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-xs text-white font-bold rounded-xl transition shadow-sm">
                                            {{ __('Approve Vendor') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function adminDashboardTracker(initialHolds) {
            return {
                activeHolds: initialHolds,

                init() {
                    if (window.Echo) {
                        window.Echo.private('admin.inventory')
                            .listen('.ProductStockUpdated', () => {
                                fetch('/admin/active-holds-count')
                                    .then(res => res.json())
                                    .then(data => {
                                        if (data && data.count !== undefined) {
                                            this.activeHolds = data.count;
                                        }
                                    })
                                    .catch(() => {});
                            });
                    }
                }
            };
        }
    </script>
</x-app-layout>
