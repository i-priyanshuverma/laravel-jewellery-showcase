<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-xl text-slate-900 dark:text-slate-100 leading-tight">
                {{ __('Vendor:') }} {{ $vendor->vendorProfile?->business_name ?? $vendor->name }}
            </h2>
            <a href="{{ route('admin.vendors.index') }}" class="text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200">&larr; {{ __('Back to Vendors') }}</a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-900 dark:text-slate-100 transition-colors duration-200">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash-message />

            <!-- Profile Overview Card -->
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-6 transition-colors">
                <div class="flex items-center gap-4">
                    <x-vendor-avatar :vendor="$vendor" size="lg" />
                    <div>
                        <div class="flex items-center gap-3">
                            <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">{{ $vendor->vendorProfile?->business_name ?? $vendor->name }}</h3>
                            <x-status-badge :status="$vendor->status" />
                        </div>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('Owner:') }} {{ $vendor->name }} &bull; {{ $vendor->email }}</p>
                        @if ($vendor->vendorProfile?->phone)
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">📞 {{ $vendor->vendorProfile->phone }}</p>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @if ($vendor->isPending())
                        <form method="POST" action="{{ route('admin.vendors.approve', $vendor) }}" onsubmit="event.preventDefault(); window.confirmAction({ title: '{{ __('Approve Vendor Application') }}', message: '{{ __('Are you sure you want to approve') }} {{ addslashes($vendor->vendorProfile?->business_name ?? $vendor->name) }}? {{ __('Their storefront and products will become visible to buyers.') }}', confirmText: '{{ __('Approve Vendor') }}', confirmButtonClass: 'bg-emerald-600 hover:bg-emerald-500 text-white', icon: 'success', form: this });">
                            @csrf @method('PATCH')
                            <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-sm transition">{{ __('Approve Vendor') }}</button>
                        </form>
                    @elseif ($vendor->isApproved())
                        <form method="POST" action="{{ route('admin.vendors.suspend', $vendor) }}" onsubmit="event.preventDefault(); window.confirmAction({ title: '{{ __('Suspend Vendor Account') }}', message: '{{ __('Are you sure you want to suspend') }} {{ addslashes($vendor->vendorProfile?->business_name ?? $vendor->name) }}? {{ __('Active reservations on their products will be released immediately.') }}', confirmText: '{{ __('Suspend Vendor') }}', icon: 'danger', form: this });">
                            @csrf @method('PATCH')
                            <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-sm transition">{{ __('Suspend Vendor') }}</button>
                        </form>
                    @elseif ($vendor->isSuspended())
                        <form method="POST" action="{{ route('admin.vendors.reactivate', $vendor) }}" onsubmit="event.preventDefault(); window.confirmAction({ title: '{{ __('Reactivate Vendor Account') }}', message: '{{ __('Are you sure you want to reactivate') }} {{ addslashes($vendor->vendorProfile?->business_name ?? $vendor->name) }}? {{ __('Their product catalog and active status will be restored.') }}', confirmText: '{{ __('Reactivate Vendor') }}', confirmButtonClass: 'bg-sky-600 hover:bg-sky-500 text-white', icon: 'info', form: this });">
                            @csrf @method('PATCH')
                            <button type="submit" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-sm transition">{{ __('Reactivate Vendor') }}</button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Products List belonging to this vendor -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 transition-colors">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">{{ __('Vendor Catalogue Products') }} ({{ $vendor->products->count() }})</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse ($vendor->products as $product)
                        <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-950/50 flex flex-col justify-between shadow-sm">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-semibold text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30 px-2 py-0.5 rounded-full">{{ $product->category->name }}</span>
                                    <x-status-badge :status="$product->status" />
                                </div>
                                <h4 class="font-bold text-slate-900 dark:text-white text-base">{{ $product->name }}</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 mt-1">{{ $product->description }}</p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                                <span>{{ $product->variants->count() }} {{ __('Variants') }}</span>
                                <a href="{{ route('admin.products.show', $product) }}" class="font-bold text-amber-600 dark:text-amber-400 hover:underline">{{ __('View Product →') }}</a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-8 text-center text-slate-500 dark:text-slate-400">{{ __('This vendor has not added any products yet.') }}</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
