<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-xl text-slate-900 dark:text-slate-100 leading-tight">
            {{ __('All Vendor Products') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-900 dark:text-slate-100 transition-colors duration-200">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash-message />

            <!-- Filters -->
            <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between transition-colors">
                <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products, SKU..." class="px-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-amber-500 focus:border-amber-500 w-full md:w-48">

                    <select name="category_id" class="pl-3.5 pr-9 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-slate-100 focus:ring-amber-500 focus:border-amber-500">
                        <option value="">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>

                    <select name="vendor_id" class="pl-3.5 pr-9 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-slate-100 focus:ring-amber-500 focus:border-amber-500">
                        <option value="">All Vendors</option>
                        @foreach ($vendors as $v)
                            <option value="{{ $v->id }}" {{ request('vendor_id') == $v->id ? 'selected' : '' }}>{{ $v->vendorProfile?->business_name ?? $v->name }}</option>
                        @endforeach
                    </select>

                    <select name="status" class="pl-3.5 pr-9 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-slate-100 focus:ring-amber-500 focus:border-amber-500">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>

                    <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition shadow-sm">Filter</button>
                </form>

                <div class="text-xs text-slate-500 dark:text-slate-400">
                    Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                </div>
            </div>

            <!-- Products Table -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                        <thead class="bg-slate-50 dark:bg-slate-950/60 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3.5">Product Name</th>
                                <th class="px-6 py-3.5">Vendor</th>
                                <th class="px-6 py-3.5">Category</th>
                                <th class="px-6 py-3.5">Variants</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse ($products as $product)
                                <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/50 transition">
                                    <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                                        <a href="{{ route('admin.products.show', $product) }}" class="hover:text-amber-600 dark:hover:text-amber-400 hover:underline transition">
                                            {{ $product->name }}
                                        </a>
                                        @if ($product->is_featured)
                                            <span class="ms-2 px-2 py-0.5 text-[10px] uppercase font-bold bg-amber-100 dark:bg-amber-900/60 text-amber-800 dark:text-amber-300 rounded-full border border-amber-300 dark:border-amber-700">Featured</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-medium">{{ $product->vendor?->vendorProfile?->business_name ?? $product->vendor?->name }}</td>
                                    <td class="px-6 py-4"><span class="text-xs font-semibold px-2.5 py-1 bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30 text-amber-700 dark:text-amber-400 rounded-full">{{ $product->category->name }}</span></td>
                                    <td class="px-6 py-4 font-medium">{{ $product->variants->count() }} variant(s)</td>
                                    <td class="px-6 py-4"><x-status-badge :status="$product->status" /></td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.products.show', $product) }}"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 hover:text-amber-600 dark:hover:text-amber-400 text-xs font-bold transition shadow-sm"
                                           title="View Product Details">
                                            <svg class="w-3.5 h-3.5 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <span>View</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">No products found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($products->hasPages())
                    <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
