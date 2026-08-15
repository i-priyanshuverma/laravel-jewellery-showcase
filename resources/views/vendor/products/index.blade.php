<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-xl text-slate-900 dark:text-slate-100 leading-tight">
                {{ __('My Jewellery Products') }}
            </h2>
            <a href="{{ route('vendor.products.create') }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-sm transition">{{ __('+ Add Product') }}</a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-900 dark:text-slate-100 transition-colors duration-200">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash-message />

            <!-- Filters -->
            <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between transition-colors">
                <form method="GET" action="{{ route('vendor.products.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search my products...') }}" class="px-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-amber-500 focus:border-amber-500 w-full md:w-56">

                    <select name="category_id" class="pl-3.5 pr-9 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-slate-100 focus:ring-amber-500 focus:border-amber-500">
                        <option value="">{{ __('All Categories') }}</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>

                    <select name="status" class="pl-3.5 pr-9 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-slate-100 focus:ring-amber-500 focus:border-amber-500">
                        <option value="">{{ __('All Statuses') }}</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>{{ __('Draft') }}</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                    </select>

                    <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition shadow-sm">{{ __('Filter') }}</button>
                </form>

                <div class="text-xs text-slate-500 dark:text-slate-400">
                    {{ __('Showing') }} {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} {{ __('of') }} {{ $products->total() }} {{ __('items') }}
                </div>
            </div>

            <!-- Products List -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
                <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-950/60 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3.5">{{ __('Product') }}</th>
                            <th class="px-6 py-3.5">{{ __('Category') }}</th>
                            <th class="px-6 py-3.5">{{ __('Variants') }}</th>
                            <th class="px-6 py-3.5">{{ __('Status') }}</th>
                            <th class="px-6 py-3.5 text-right">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse ($products as $product)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 overflow-hidden flex-shrink-0 flex items-center justify-center shadow-sm">
                                            @if ($product->primaryImage())
                                                <img src="{{ $product->primaryImage()->url }}" alt="" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-xl text-slate-400">💍</span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 dark:text-white">{{ $product->name }}</div>
                                            <div class="text-xs text-slate-400 font-mono">{{ $product->slug }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30 text-amber-700 dark:text-amber-400 text-xs font-semibold">{{ $product->category->name }}</span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-700 dark:text-slate-300">
                                    {{ $product->variants->count() }} {{ __('variant(s)') }}
                                </td>
                                <td class="px-6 py-4">
                                    <x-status-badge :status="$product->status" />
                                </td>
                                <td class="px-6 py-4 text-right space-x-3">
                                    <a href="{{ route('vendor.products.show', $product) }}" class="text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline">{{ __('Manage') }}</a>
                                    <a href="{{ route('vendor.products.edit', $product) }}" class="text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">{{ __('Edit') }}</a>
                                    <form method="POST" action="{{ route('vendor.products.destroy', $product) }}" class="inline" onsubmit="event.preventDefault(); window.confirmAction({ title: '{{ __('Delete Product') }}', message: '{{ __('Are you sure you want to delete this product? All associated variants and images will be permanently removed, and active reservations will be released.') }}', confirmText: '{{ __('Delete Product') }}', icon: 'danger', form: this });">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">{{ __('Delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">{{ __('No products found.') }} <a href="{{ route('vendor.products.create') }}" class="text-amber-600 dark:text-amber-400 font-semibold underline">{{ __('Create your first product') }}</a></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($products->hasPages())
                    <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
