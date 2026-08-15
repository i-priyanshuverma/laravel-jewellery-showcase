<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-xl text-slate-900 dark:text-slate-100 leading-tight">
                Edit Product: {{ $product->name }}
            </h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('vendor.products.images.index', $product) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200 hover:text-amber-600 dark:hover:text-amber-400 transition shadow-sm">
                    <span>📷 Gallery ({{ $product->images->count() }}/5)</span>
                </a>
                <a href="{{ route('vendor.products.show', $product) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200 hover:text-amber-600 dark:hover:text-amber-400 transition shadow-sm">
                    <span>View Product &rarr;</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-900 dark:text-slate-100 transition-colors duration-200">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <x-flash-message />

            <!-- Primary Product Details Form -->
            <div class="bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-xl transition-colors">
                <div class="border-b border-slate-100 dark:border-slate-800 pb-4 mb-6">
                    <h3 class="text-sm font-extrabold text-slate-900 dark:text-white uppercase tracking-wider">
                        1. Product Information
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Update basic product name, category, description and public visibility.
                    </p>
                </div>

                <form method="POST" action="{{ route('vendor.products.update', $product) }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div>
                            <x-input-label for="name" :value="__('Product Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $product->name)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select id="category_id" name="category_id" class="mt-1 block w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 text-sm focus:ring-amber-500 focus:border-amber-500 transition-colors" required>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 text-sm focus:ring-amber-500 focus:border-amber-500 transition-colors">{{ old('description', $product->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="status" :value="__('Publication Status')" />
                                <select id="status" name="status" class="mt-1 block w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 text-sm focus:ring-amber-500 focus:border-amber-500 transition-colors">
                                    @if ($product->status === 'draft')
                                        <option value="draft" {{ old('status', $product->status) == 'draft' ? 'selected' : '' }}>Draft (Work in Progress)</option>
                                        <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active (Publish)</option>
                                    @else
                                        <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active (Public Showcase)</option>
                                        <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive (Delisted / Paused)</option>
                                    @endif
                                </select>
                                <span class="text-[11px] text-slate-400 dark:text-slate-500 mt-1 block">
                                    {{ $product->status === 'draft' ? 'Draft products remain private until you publish as Active.' : 'Setting product to Inactive delists it and releases active customer holds.' }}
                                </span>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>

                            <div class="flex items-center pt-6">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="rounded border-slate-300 dark:border-slate-700 text-amber-600 focus:ring-amber-500 bg-white dark:bg-slate-900">
                                    <span class="ms-2 text-sm font-semibold text-slate-700 dark:text-slate-300">Feature this product</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-between border-t border-slate-100 dark:border-slate-800 pt-6">
                        <a href="{{ route('vendor.products.show', $product) }}" class="text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200">Cancel</a>
                        <x-primary-button>Update Product Details</x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Product Variants & SKU Management Card -->
            <div class="bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-xl transition-colors">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 dark:border-slate-800 pb-4 mb-6">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                            <span>2. Product Variants & SKUs</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800">
                                {{ $product->variants->count() }} Variant(s)
                            </span>
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Each variant defines a unique SKU (Stock Keeping Unit), metal purity, size, pricing, inventory stock, and gemstone setup.
                        </p>
                    </div>

                    <a href="{{ route('vendor.products.variants.create', $product) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold rounded-xl shadow-md transition self-start sm:self-auto">
                        <span>+ Add New SKU / Variant</span>
                    </a>
                </div>

                @if ($product->variants->isNotEmpty())
                    <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 dark:bg-slate-800/80 text-slate-500 dark:text-slate-400 font-semibold border-b border-slate-200 dark:border-slate-800">
                                <tr>
                                    <th class="px-4 py-3">SKU</th>
                                    <th class="px-4 py-3">Specifications</th>
                                    <th class="px-4 py-3">Price</th>
                                    <th class="px-4 py-3">Stock</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach ($product->variants as $variant)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                                        <td class="px-4 py-3.5 font-mono font-bold text-slate-900 dark:text-white">
                                            {{ $variant->sku }}
                                        </td>
                                        <td class="px-4 py-3.5 space-y-0.5">
                                            <div class="font-medium text-slate-800 dark:text-slate-200">
                                                {{ $variant->purity }} {{ $variant->metal }} {{ $variant->colour ? "• {$variant->colour}" : '' }}
                                            </div>
                                            <div class="text-[11px] text-slate-500 dark:text-slate-400">
                                                Size: {{ $variant->size ?? 'Standard' }} | Weight: {{ $variant->weight ? $variant->weight.'g' : 'N/A' }}
                                                @if ($variant->stones->isNotEmpty())
                                                    | {{ $variant->stones->count() }} Gemstone(s)
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-3.5 font-bold text-amber-600 dark:text-amber-400">
                                            ₹{{ number_format($variant->price, 2) }}
                                        </td>
                                        <td class="px-4 py-3.5 font-bold {{ $variant->stock > 0 ? 'text-slate-900 dark:text-white' : 'text-rose-500' }}">
                                            {{ $variant->stock }} units
                                        </td>
                                        <td class="px-4 py-3.5">
                                            <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-full {{ $variant->status === 'active' ? 'bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800' : 'bg-rose-50 dark:bg-rose-950 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-800' }}">
                                                {{ $variant->status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3.5 text-right space-x-2">
                                            <a href="{{ route('vendor.products.variants.edit', [$product, $variant]) }}" class="inline-flex items-center gap-1 text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline">
                                                <span>Edit SKU & Pricing &rarr;</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8 text-center bg-slate-50 dark:bg-slate-800/30 rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 space-y-3">
                        <div class="w-12 h-12 mx-auto rounded-full bg-amber-50 dark:bg-amber-950 flex items-center justify-center text-amber-600 dark:text-amber-400 text-xl font-bold">
                            🏷️
                        </div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">No Variants or SKUs Created Yet</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 max-w-md mx-auto">
                            Every product requires at least one variant defining the SKU code, metal purity, pricing, and stock quantity before it can be published.
                        </p>
                        <a href="{{ route('vendor.products.variants.create', $product) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold rounded-xl shadow-md transition">
                            <span>+ Add First Variant & SKU</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
