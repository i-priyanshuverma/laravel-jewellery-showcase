<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-xl text-slate-900 dark:text-slate-100 leading-tight">
            {{ __('Vendor Management') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-900 dark:text-slate-100 transition-colors duration-200">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash-message />

            <!-- Filters & Search -->
            <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between transition-colors">
                <form method="GET" action="{{ route('admin.vendors.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search vendor name, business, email...') }}" class="px-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-amber-500 focus:border-amber-500 w-full md:w-64">

                    <select name="status" class="pl-3.5 pr-9 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-slate-100 focus:ring-amber-500 focus:border-amber-500">
                        <option value="">{{ __('All Statuses') }}</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ __('Approved') }}</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>{{ __('Suspended') }}</option>
                    </select>

                    <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition shadow-sm">{{ __('Filter') }}</button>
                    @if (request()->hasAny(['search', 'status']))
                        <a href="{{ route('admin.vendors.index') }}" class="text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200">{{ __('Clear') }}</a>
                    @endif
                </form>

                <div class="text-xs text-slate-500 dark:text-slate-400">
                    {{ __('Showing') }} {{ $vendors->firstItem() ?? 0 }}-{{ $vendors->lastItem() ?? 0 }} {{ __('of') }} {{ $vendors->total() }} {{ __('vendors') }}
                </div>
            </div>

            <!-- Vendors Table -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                        <thead class="bg-slate-50 dark:bg-slate-950/60 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3.5">{{ __('Business Name') }}</th>
                                <th class="px-6 py-3.5">{{ __('Contact Name') }}</th>
                                <th class="px-6 py-3.5">{{ __('Email') }}</th>
                                <th class="px-6 py-3.5">{{ __('Products') }}</th>
                                <th class="px-6 py-3.5 text-center">{{ __('Status') }}</th>
                                <th class="px-6 py-3.5 text-center">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse ($vendors as $vendor)
                                <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/50 transition">
                                    <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                                        <a href="{{ route('admin.vendors.show', $vendor) }}" class="hover:text-amber-600 dark:hover:text-amber-400 hover:underline transition">
                                            {{ $vendor->vendorProfile?->business_name ?? __('N/A') }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">{{ $vendor->name }}</td>
                                    <td class="px-6 py-4">{{ $vendor->email }}</td>
                                    <td class="px-6 py-4 font-medium">{{ $vendor->products->count() }} {{ __('items') }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <x-status-badge :status="$vendor->status" />
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="inline-flex items-center justify-center gap-2">
                                            @if ($vendor->isPending())
                                                <form method="POST" action="{{ route('admin.vendors.approve', $vendor) }}" class="inline-flex" onsubmit="event.preventDefault(); window.confirmAction({ title: '{{ __('Approve Vendor Application') }}', message: '{{ __('Are you sure you want to approve') }} {{ addslashes($vendor->vendorProfile?->business_name ?? $vendor->name) }}? {{ __('Their storefront and products will become visible to buyers.') }}', confirmText: '{{ __('Approve Vendor') }}', confirmButtonClass: 'bg-emerald-600 hover:bg-emerald-500 text-white', icon: 'success', form: this });">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-lg transition shadow-sm">{{ __('Approve') }}</button>
                                                </form>
                                            @elseif ($vendor->isApproved())
                                                <form method="POST" action="{{ route('admin.vendors.suspend', $vendor) }}" class="inline-flex" onsubmit="event.preventDefault(); window.confirmAction({ title: '{{ __('Suspend Vendor Account') }}', message: '{{ __('Are you sure you want to suspend') }} {{ addslashes($vendor->vendorProfile?->business_name ?? $vendor->name) }}? {{ __('Active reservations on their products will be released immediately.') }}', confirmText: '{{ __('Suspend Vendor') }}', icon: 'danger', form: this });">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold rounded-lg transition shadow-sm">{{ __('Suspend') }}</button>
                                                </form>
                                            @elseif ($vendor->isSuspended())
                                                <form method="POST" action="{{ route('admin.vendors.reactivate', $vendor) }}" class="inline-flex" onsubmit="event.preventDefault(); window.confirmAction({ title: '{{ __('Reactivate Vendor Account') }}', message: '{{ __('Are you sure you want to reactivate') }} {{ addslashes($vendor->vendorProfile?->business_name ?? $vendor->name) }}? {{ __('Their product catalog and active status will be restored.') }}', confirmText: '{{ __('Reactivate Vendor') }}', confirmButtonClass: 'bg-sky-600 hover:bg-sky-500 text-white', icon: 'info', form: this });">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="px-3 py-1.5 bg-sky-600 hover:bg-sky-500 text-white text-xs font-bold rounded-lg transition shadow-sm">{{ __('Reactivate') }}</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">{{ __('No vendors found matching criteria.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($vendors->hasPages())
                    <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                        {{ $vendors->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
