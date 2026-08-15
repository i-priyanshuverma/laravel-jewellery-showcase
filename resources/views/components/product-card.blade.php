@props(['product'])

<a href="{{ route('products.show', $product) }}" {{ $attributes->merge(['class' => 'group bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 hover:border-amber-500/50 dark:hover:border-amber-500/50 shadow-sm hover:shadow-md dark:shadow-md dark:hover:shadow-lg transition-all duration-200 flex flex-col overflow-hidden']) }}>
    <!-- Image Container -->
    <div class="relative w-full aspect-[4/3] bg-slate-100 dark:bg-slate-950 overflow-hidden border-b border-slate-100 dark:border-slate-800/50">
        <img src="{{ $product->primary_image_url }}"
             alt="{{ $product->name }}"
             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
             loading="lazy"
             onerror="this.onerror=null; this.src='{{ asset('images/products/placeholder.svg') }}';">

        <!-- Badge Tag: Category -->
        <div class="absolute top-2 left-2">
            <span class="px-2 py-0.5 bg-white/90 dark:bg-slate-950/80 backdrop-blur-md border border-slate-200 dark:border-slate-800 text-[9px] font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400 rounded-full shadow-sm">
                {{ $product->category?->name ?? 'Jewellery' }}
            </span>
        </div>
    </div>

    <!-- Content -->
    <div class="p-3.5 flex flex-col flex-grow justify-between space-y-2.5">
        <div>
            <h4 class="font-bold text-xs sm:text-sm text-slate-900 dark:text-slate-100 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors line-clamp-1 leading-snug" title="{{ $product->name }}">
                {{ $product->name }}
            </h4>
            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 flex items-center gap-1 font-medium truncate">
                <span class="text-slate-400 dark:text-slate-500">By</span>
                <span class="text-slate-700 dark:text-slate-300 font-semibold truncate">{{ $product->vendor?->vendorProfile?->business_name ?? $product->vendor?->name }}</span>
            </p>
        </div>

        <!-- Pricing & Stock Footer -->
        <div class="pt-2 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between gap-1">
            <div>
                <span class="text-[9px] uppercase tracking-wider text-slate-400 dark:text-slate-500 block font-semibold leading-none mb-0.5">Price</span>
                <span class="font-extrabold text-amber-600 dark:text-amber-400 text-xs sm:text-sm font-mono">
                    ₹{{ number_format($product->activeVariants->min('price') ?? 0, 2) }}
                </span>
            </div>

            <div class="text-right">
                @php $totalStock = $product->activeVariants->sum('stock'); @endphp
                @if ($totalStock > 0)
                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-[10px] font-semibold text-emerald-700 dark:text-emerald-400">
                        <span class="w-1 h-1 rounded-full bg-emerald-500 animate-pulse"></span>
                        In Stock
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-[10px] font-semibold text-rose-700 dark:text-rose-400">
                        <span class="w-1 h-1 rounded-full bg-rose-500"></span>
                        Out of Stock
                    </span>
                @endif
            </div>
        </div>
    </div>
</a>
