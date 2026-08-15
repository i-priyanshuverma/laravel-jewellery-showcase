@props([
    'product',
    'isAdmin' => false,
])

<div class="space-y-6"
     x-data="productDetailTracker({{ $product->id }}, {{ $product->user_id }}, '{{ $product->primaryImage() ? $product->primaryImage()->url : '' }}', {{ $isAdmin ? 'true' : 'false' }})">

    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 transition-colors">
        <div class="flex flex-col lg:flex-row gap-8 items-start">
            
            <div class="w-full lg:w-80 flex-shrink-0 space-y-3">
                <div class="w-full h-72 rounded-2xl bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 overflow-hidden flex items-center justify-center shadow-inner relative group">
                    <template x-if="activeImage">
                        <img :src="activeImage" alt="{{ $product->name }}" class="w-full h-full object-cover transition-all duration-300">
                    </template>
                    <template x-if="!activeImage">
                        <div class="flex flex-col items-center justify-center text-slate-400">
                            <span class="text-5xl mb-2">💍</span>
                            <span class="text-xs font-medium">No Image Uploaded</span>
                        </div>
                    </template>
                </div>

                @if ($product->images->isNotEmpty())
                    <div class="flex items-center gap-2 overflow-x-auto pb-1.5 scrollbar-thin">
                        @foreach ($product->images as $img)
                            <button type="button"
                                    @click="activeImage = '{{ $img->url }}'"
                                    class="w-14 h-14 rounded-xl border overflow-hidden flex-shrink-0 transition-all cursor-pointer shadow-sm"
                                    :class="activeImage === '{{ $img->url }}' ? 'ring-2 ring-amber-500 border-amber-500 scale-105' : 'border-slate-200 dark:border-slate-800 opacity-60 hover:opacity-100 hover:border-slate-400'">
                                <img src="{{ $img->url }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif

                @if (!$isAdmin)
                    <a href="{{ route('vendor.products.images.index', $product) }}"
                       class="w-full flex items-center justify-center gap-1.5 py-2 px-3 text-xs font-bold text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30 rounded-xl hover:bg-amber-100 dark:hover:bg-amber-500/20 transition shadow-sm">
                        <span>📷 Manage Gallery ({{ $product->images->count() }})</span>
                    </a>
                @else
                    <div class="text-center py-1 text-xs text-slate-400 font-mono">
                        {{ $product->images->count() }} Studio Image(s)
                    </div>
                @endif
            </div>

            <div class="flex-1 min-w-0 space-y-6">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Variants</span>
                        <p class="text-xl font-extrabold text-slate-900 dark:text-slate-100 mt-0.5">{{ $product->variants->count() }}</p>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Available Stock</span>
                        <p class="text-xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-0.5 font-mono" x-text="totalStock">{{ $product->variants->sum('stock') }}</p>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Customer Holds</span>
                        <p class="text-xl font-extrabold text-purple-600 dark:text-purple-400 mt-0.5 font-mono" x-text="totalHolds">{{ $product->variants->sum(fn($v) => $v->activeReservations->sum('quantity')) }}</p>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Price Range</span>
                        <p class="text-sm font-bold text-slate-900 dark:text-slate-100 mt-1.5 truncate font-mono">
                            @if ($product->variants->isNotEmpty())
                                ₹{{ number_format($product->variants->min('price')) }} - ₹{{ number_format($product->variants->max('price')) }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                </div>

                <div class="space-y-2">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Craftsmanship & Description</h4>
                    <div class="p-4 rounded-2xl bg-slate-50/70 dark:bg-slate-950/40 border border-slate-100 dark:border-slate-800/80 text-sm text-slate-700 dark:text-slate-300 leading-relaxed max-h-48 overflow-y-auto scrollbar-thin">
                        {{ $product->description ?? 'No detailed description provided for this jewellery piece.' }}
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-4 text-xs text-slate-500 dark:text-slate-400 pt-3 border-t border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <span>Product Listed: <strong class="text-slate-700 dark:text-slate-300">{{ $product->created_at->format('M d, Y') }}</strong></span>
                        <span>&bull;</span>
                        <span>Product Updated: <strong class="text-slate-700 dark:text-slate-300">{{ $product->updated_at->diffForHumans() }}</strong></span>
                    </div>

                    @if ($isAdmin && $product->vendor)
                        <div class="flex items-center gap-2">
                            <span>Vendor: <strong class="text-slate-800 dark:text-slate-200">{{ $product->vendor?->vendorProfile?->business_name ?? $product->vendor?->name }}</strong></span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 space-y-4 transition-colors">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Product Variants ({{ $product->variants->count() }})</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1.5 mt-0.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Real-time inventory feed & customer holds</span>
                </p>
            </div>
            @if (!$isAdmin)
                <a href="{{ route('vendor.products.variants.create', $product) }}"
                   class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-sm hover:shadow transition">
                    + Add New Variant
                </a>
            @endif
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-100 dark:border-slate-800">
            <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-950/60 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5">SKU</th>
                        <th class="px-4 py-3.5">Metal & Purity</th>
                        <th class="px-4 py-3.5">Colour / Size</th>
                        <th class="px-4 py-3.5">Weight</th>
                        <th class="px-4 py-3.5">Price</th>
                        <th class="px-4 py-3.5">Available Stock</th>
                        <th class="px-4 py-3.5">Active Holds</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5">Added / Updated</th>
                        @if (!$isAdmin)
                            <th class="px-4 py-3.5 text-right">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($product->variants as $variant)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/50 transition">
                            <td class="px-4 py-3.5 font-mono font-bold text-slate-900 dark:text-white">{{ $variant->sku }}</td>
                            <td class="px-4 py-3.5">
                                <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $variant->metal ?? '-' }} / {{ $variant->purity ?? '-' }}</div>
                                @if ($variant->stones->isNotEmpty())
                                    <div class="text-[11px] text-purple-600 dark:text-purple-400 font-semibold mt-0.5 flex items-center gap-1">
                                        <span>💎</span>
                                        <span>{{ $variant->stones->map(fn($s) => ($s->stoneType?->name ?? 'Stone') . ($s->carat_weight ? " ({$s->carat_weight}ct)" : ''))->join(', ') }}</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3.5">{{ $variant->colour ?? '-' }} / {{ $variant->size ?? '-' }}</td>
                            <td class="px-4 py-3.5">{{ $variant->weight ? $variant->weight . 'g' : '-' }}</td>
                            <td class="px-4 py-3.5 font-bold text-emerald-600 dark:text-emerald-400 font-mono">₹{{ number_format($variant->price, 2) }}</td>
                            <td class="px-4 py-3.5 font-bold text-slate-900 dark:text-slate-100 font-mono" x-text="getVariantStock({{ $variant->id }}, {{ $variant->stock }})">{{ $variant->stock }}</td>
                            <td class="px-4 py-3.5">
                                <template x-if="getVariantHolds({{ $variant->id }}, {{ $variant->reserved_quantity }}) > 0">
                                    <span class="px-2.5 py-0.5 rounded-full bg-purple-50 dark:bg-purple-950 border border-purple-200 dark:border-purple-800 text-purple-700 dark:text-purple-300 text-xs font-bold font-mono">
                                        <span x-text="getVariantHolds({{ $variant->id }}, {{ $variant->reserved_quantity }})"></span> held
                                    </span>
                                </template>
                                <template x-if="getVariantHolds({{ $variant->id }}, {{ $variant->reserved_quantity }}) <= 0">
                                    <span class="text-xs text-slate-400">None</span>
                                </template>
                            </td>
                            <td class="px-4 py-3.5"><x-status-badge :status="$variant->status" /></td>
                            <td class="px-4 py-3.5 text-xs text-slate-500 dark:text-slate-400">
                                <div class="font-medium text-slate-700 dark:text-slate-300 whitespace-nowrap">{{ $variant->created_at ? $variant->created_at->format('M d, Y') : '-' }}</div>
                                <div class="text-[10px] text-slate-400 font-mono mt-0.5 whitespace-nowrap">Upd: {{ $variant->updated_at ? $variant->updated_at->diffForHumans() : '-' }}</div>
                            </td>
                            @if (!$isAdmin)
                                <td class="px-4 py-3.5 text-right space-x-3">
                                    <a href="{{ route('vendor.products.variants.edit', [$product, $variant]) }}" class="text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline">Edit</a>
                                    <form method="POST" action="{{ route('vendor.products.variants.destroy', [$product, $variant]) }}" class="inline" onsubmit="event.preventDefault(); window.confirmAction({ title: 'Delete Product Variant', message: 'Are you sure you want to delete variant {{ addslashes($variant->sku) }}? This will permanently remove this variant specification.', confirmText: 'Delete Variant', icon: 'danger', form: this });">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Delete</button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $isAdmin ? 9 : 10 }}" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">
                                No variants exist for this product.
                                @if (!$isAdmin)
                                    <a href="{{ route('vendor.products.variants.create', $product) }}" class="text-amber-600 dark:text-amber-400 font-semibold underline ml-1">Add first variant</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function productDetailTracker(productId, vendorId, initialImage, isAdmin) {
        const rawVariants = @json($product->variants);
        const initialStockMap = {};
        const initialHoldsMap = {};

        rawVariants.forEach(v => {
            initialStockMap[v.id] = v.stock;
            initialHoldsMap[v.id] = v.reserved_quantity || 0;
        });

        return {
            productId: productId,
            vendorId: vendorId,
            activeImage: initialImage,
            stockMap: initialStockMap,
            holdsMap: initialHoldsMap,

            get totalStock() {
                return Object.values(this.stockMap).reduce((a, b) => a + Number(b), 0);
            },

            get totalHolds() {
                return Object.values(this.holdsMap).reduce((a, b) => a + Number(b), 0);
            },

            init() {
                if (window.Echo) {
                    if (isAdmin) {
                        window.Echo.private('admin.inventory')
                            .listen('.ProductStockUpdated', (e) => {
                                if (e && e.productId === this.productId) {
                                    this.stockMap[e.variantId] = e.stock;
                                    this.holdsMap[e.variantId] = e.activeHoldsCount;
                                }
                            });
                    } else {
                        window.Echo.private(`vendor.${this.vendorId}`)
                            .listen('.ProductStockUpdated', (e) => {
                                if (e && e.productId === this.productId) {
                                    this.stockMap[e.variantId] = e.stock;
                                    this.holdsMap[e.variantId] = e.activeHoldsCount;
                                }
                            });
                    }

                    window.Echo.channel(`products.${this.productId}`)
                        .listen('.ProductStockUpdated', (e) => {
                            if (e) {
                                this.stockMap[e.variantId] = e.stock;
                                this.holdsMap[e.variantId] = e.activeHoldsCount;
                            }
                        });
                }
            },

            getVariantStock(variantId, defaultStock) {
                return this.stockMap[variantId] !== undefined ? this.stockMap[variantId] : defaultStock;
            },

            getVariantHolds(variantId, defaultHolds) {
                return this.holdsMap[variantId] !== undefined ? this.holdsMap[variantId] : defaultHolds;
            }
        };
    }
</script>
