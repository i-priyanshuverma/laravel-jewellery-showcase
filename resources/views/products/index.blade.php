<x-app-layout>
    <div class="py-6 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-900 dark:text-slate-100 transition-colors duration-200">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-flash-message />

            <div class="flex flex-col lg:flex-row gap-6 items-start">
                <!-- Sidebar Filters (Compact) -->
                <aside class="w-full lg:w-60 xl:w-64 flex-shrink-0">
                    <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-4 sticky top-24 transition-colors duration-200">
                        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                            <h3 class="font-bold text-slate-900 dark:text-slate-100 text-sm flex items-center gap-1.5">
                                <span>Filter Catalogue</span>
                            </h3>
                            @if (request()->hasAny(['search', 'category_id', 'vendor_id', 'min_price', 'max_price', 'metal', 'purity', 'colour', 'size', 'stone_type', 'in_stock']))
                                <a href="{{ route('products.index') }}" class="text-[11px] font-semibold text-amber-600 dark:text-amber-400 hover:underline">Reset All</a>
                            @endif
                        </div>

                        <form method="GET" action="{{ route('products.index') }}" class="space-y-3.5">
                            @if (request()->filled('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            @if (request()->filled('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                            @endif

                            <!-- Category Filter -->
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Category</label>
                                <select name="category_id" onchange="this.form.submit()" class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs text-slate-900 dark:text-slate-100 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                                    <option value="">All Categories</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Vendor Filter -->
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Jeweller / Vendor</label>
                                <select name="vendor_id" class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs text-slate-900 dark:text-slate-100 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                                    <option value="">All Vendors</option>
                                    @foreach ($vendors as $v)
                                        <option value="{{ $v->id }}" {{ request('vendor_id') == $v->id ? 'selected' : '' }}>{{ $v->vendorProfile?->business_name ?? $v->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Price Range -->
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Price Range (₹)</label>
                                <div class="grid grid-cols-2 gap-1.5">
                                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min ₹" class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-amber-500">
                                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max ₹" class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-amber-500">
                                </div>
                            </div>

                            <!-- Metal Filter -->
                            @if ($metals->count() > 0)
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Base Metal</label>
                                    <select name="metal" class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs text-slate-900 dark:text-slate-100 focus:ring-amber-500">
                                        <option value="">All Metals</option>
                                        @foreach ($metals as $m)
                                            <option value="{{ $m->name }}" {{ request('metal') == $m->name ? 'selected' : '' }}>{{ $m->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <!-- Colour Filter -->
                            @if ($colours->count() > 0)
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Colour / Finish</label>
                                    <select name="colour" class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs text-slate-900 dark:text-slate-100 focus:ring-amber-500">
                                        <option value="">All Colours</option>
                                        @foreach ($colours as $c)
                                            <option value="{{ $c->name }}" {{ request('colour') == $c->name ? 'selected' : '' }}>{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <!-- Stone Type Filter -->
                            @if ($stoneTypes->count() > 0)
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Stone / Gem</label>
                                    <select name="stone_type" class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs text-slate-900 dark:text-slate-100 focus:ring-amber-500">
                                        <option value="">All Gems & Stones</option>
                                        @foreach ($stoneTypes as $st)
                                            <option value="{{ $st->name }}" {{ request('stone_type') == $st->name ? 'selected' : '' }}>{{ $st->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <!-- Size Filter -->
                            @if ($sizes->count() > 0)
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Size</label>
                                    <select name="size" class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs text-slate-900 dark:text-slate-100 focus:ring-amber-500">
                                        <option value="">All Sizes</option>
                                        @foreach ($sizes as $sz)
                                            <option value="{{ $sz->value }}" {{ request('size') == $sz->value ? 'selected' : '' }}>{{ $sz->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <!-- Stock Filter Checkbox -->
                            <div class="pt-1">
                                <label class="inline-flex items-center cursor-pointer gap-2">
                                    <input type="checkbox" name="in_stock" value="1" {{ request('in_stock') ? 'checked' : '' }} class="rounded border-slate-300 dark:border-slate-700 text-amber-600 dark:text-amber-500 focus:ring-amber-500 bg-white dark:bg-slate-950">
                                    <span class="text-[11px] font-medium text-slate-700 dark:text-slate-300">In Stock Only</span>
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="w-full py-2 bg-gold-gradient text-slate-950 font-bold text-xs uppercase tracking-wider rounded-lg shadow-sm hover:brightness-105 transition-all">
                                Apply Filters
                            </button>
                        </form>
                    </div>
                </aside>

                <!-- Product Grid (Expanded & Responsive) -->
                <main class="flex-1 min-w-0 space-y-4">
                    <!-- Active Search Query Indicator -->
                    @if (request()->filled('search'))
                        <div class="p-3 bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30 rounded-xl flex items-center justify-between text-xs text-amber-800 dark:text-amber-300">
                            <div class="flex items-center gap-2">
                                <span>🔍</span>
                                <span>Results for keyword: <strong class="text-amber-900 dark:text-amber-200">"{{ request('search') }}"</strong></span>
                            </div>
                            <a href="{{ route('products.index', request()->except('search')) }}" class="px-2 py-0.5 bg-amber-100 dark:bg-amber-500/20 hover:bg-amber-200 dark:hover:bg-amber-500/30 rounded-lg text-[10px] font-bold text-amber-800 dark:text-amber-300 transition">
                                ✕ Clear Search
                            </a>
                        </div>
                    @endif

                    <!-- Top Bar (Results count + Sort) -->
                    <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3 transition-colors duration-200">
                        <div class="text-xs text-slate-600 dark:text-slate-400">
                            Showing <span class="font-bold text-slate-900 dark:text-slate-100">{{ $products->firstItem() ?? 0 }}</span> - <span class="font-bold text-slate-900 dark:text-slate-100">{{ $products->lastItem() ?? 0 }}</span> of <span class="font-bold text-amber-600 dark:text-amber-400">{{ $products->total() }}</span> fine pieces
                        </div>

                        <form method="GET" action="{{ route('products.index') }}" class="flex items-center gap-2">
                            @foreach (request()->except('sort') as $key => $val)
                                @if (is_array($val))
                                    @foreach ($val as $subVal)
                                        <input type="hidden" name="{{ $key }}[]" value="{{ $subVal }}">
                                    @endforeach
                                @else
                                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                                @endif
                            @endforeach
                            <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Sort By:</label>
                            <select name="sort" onchange="this.form.submit()" class="px-2.5 py-1 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs text-amber-700 dark:text-amber-400 font-semibold focus:ring-amber-500">
                                <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Newest Arrivals</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A-Z</option>
                                <option value="stock_desc" {{ request('sort') == 'stock_desc' ? 'selected' : '' }}>Available Stock</option>
                            </select>
                        </form>
                    </div>

                    <!-- Products Grid (Compact Multi-Column) -->
                    @if ($products->isEmpty())
                        @if (request()->hasAny(['search', 'category_id', 'vendor_id', 'min_price', 'max_price', 'metal_id', 'purity_id', 'colour_id', 'stone_type_id', 'size_id', 'in_stock']))
                            <x-empty-state icon="🔍"
                                           title="No jewellery matching your criteria"
                                           description="Try clearing some of your filters or searching with different keywords."
                                           :action-url="route('products.index')"
                                           action-label="Reset Filters" />
                        @else
                            <x-empty-state icon="✨"
                                           title="No Jewellery Pieces Available Yet"
                                           description="The showcase catalogue is currently empty. Vendors will be publishing certified pieces soon!" />
                        @endif
                    @else
                        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-3.5 sm:gap-4">
                            @foreach ($products as $product)
                                <x-product-card :product="$product" />
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="pt-4">
                            {{ $products->links() }}
                        </div>
                    @endif
                </main>
            </div>
        </div>
    </div>
</x-app-layout>
