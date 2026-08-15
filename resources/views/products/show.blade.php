<x-app-layout>
    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-900 dark:text-slate-100 transition-colors duration-200" x-data="productReservation()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Breadcrumbs -->
            <nav class="flex text-xs text-slate-500 dark:text-slate-400 space-x-2">
                <a href="{{ route('products.index') }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">Catalogue</a>
                <span>/</span>
                <span class="text-slate-400 dark:text-slate-500">{{ $product->category->name }}</span>
                <span>/</span>
                <span class="text-amber-600 dark:text-amber-400 font-semibold">{{ $product->name }}</span>
            </nav>

            <x-flash-message />

            @php
                $fallbackSvg = asset('images/products/placeholder.svg');
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                <!-- Gallery Section (Left - 7 Cols) -->
                <div class="lg:col-span-7 space-y-4">
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl h-[460px] w-full shadow-sm dark:shadow-2xl flex items-center justify-center relative overflow-hidden transition-colors">
                        <img id="main-product-image" src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='{{ $fallbackSvg }}';">

                        <div class="absolute top-4 left-4 flex gap-2">
                            <span class="px-3 py-1 bg-white/90 dark:bg-slate-950/80 backdrop-blur-md border border-slate-200 dark:border-slate-800 text-xs font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400 rounded-full shadow-sm">
                                {{ $product->category->name }}
                            </span>
                        </div>
                    </div>

                    <!-- Thumbnails Gallery -->
                    @if ($product->images->count() > 1)
                        <div class="flex gap-3 overflow-x-auto pb-2">
                            @foreach ($product->images as $img)
                                <button type="button" onclick="document.getElementById('main-product-image').src = this.querySelector('img').src" class="w-20 h-20 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-amber-500 dark:hover:border-amber-500 rounded-xl overflow-hidden transition-all flex-shrink-0 group shadow-sm">
                                    <img src="{{ $img->url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" onerror="this.onerror=null; this.src='{{ $fallbackSvg }}';">
                                </button>
                            @endforeach
                        </div>
                    @endif

                    <!-- Product Description Card -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 space-y-3 shadow-sm transition-colors">
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Design & Craftsmanship Details</h3>
                        <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed">{{ $product->description }}</p>
                    </div>
                </div>

                <!-- Product & Variant Selection (Right - 5 Cols) -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm dark:shadow-2xl space-y-6 transition-colors">
                        <!-- Vendor Info -->
                        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Master Artisan Vendor</span>
                                <h4 class="font-bold text-slate-900 dark:text-slate-200 text-base flex items-center gap-1.5">
                                    <span>{{ $product->vendor->vendorProfile?->business_name ?? $product->vendor->name }}</span>
                                    <span class="text-emerald-500 text-xs" title="Certified Approved Vendor">✓</span>
                                </h4>
                            </div>
                            <span class="px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/80 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 text-[10px] font-bold uppercase tracking-wider rounded-full">
                                Certified Vendor
                            </span>
                        </div>

                        <!-- Product Title -->
                        <div>
                            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 dark:text-slate-100 leading-tight">
                                {{ $product->name }}
                            </h1>
                        </div>

                        <!-- Select Variant -->
                        <div class="space-y-3">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400">
                                Available Variations (Choose an option)
                            </label>

                            <div class="space-y-2 max-h-72 overflow-y-auto pr-1">
                                @foreach ($product->variants as $variant)
                                    <div @click="selectVariant({{ $variant->id }})"
                                         :class="selectedVariantId === {{ $variant->id }} ? 'bg-amber-500/10 border-amber-500 text-amber-900 dark:text-amber-300 ring-1 ring-amber-500' : 'bg-slate-50 dark:bg-slate-950/60 border-slate-200 dark:border-slate-800/80 text-slate-700 dark:text-slate-300 hover:border-slate-300 dark:hover:border-slate-700'"
                                         class="p-3.5 rounded-2xl border cursor-pointer transition-all flex flex-col gap-2 group shadow-sm">

                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-sm text-slate-900 dark:text-slate-100">{{ $variant->metal }} {{ $variant->purity }}</span>
                                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-amber-700 dark:text-amber-400 font-mono font-semibold">SKU: {{ $variant->sku }}</span>
                                            </div>

                                            <div class="text-right font-bold text-base text-amber-600 dark:text-amber-400">
                                                ₹{{ number_format($variant->price, 2) }}
                                            </div>
                                        </div>

                                        <div class="text-xs text-slate-500 dark:text-slate-400 flex flex-wrap items-center gap-2">
                                            <span>Color: {{ $variant->colour ?? 'N/A' }}</span>
                                            <span>•</span>
                                            <span>Size: {{ $variant->size ?? 'N/A' }}</span>
                                            <span>•</span>
                                            <span>Weight: {{ $variant->weight ? $variant->weight.'g' : 'N/A' }}</span>
                                        </div>

                                        @if ($variant->stones->isNotEmpty())
                                            <div class="flex flex-wrap items-center gap-1.5 pt-0.5">
                                                @foreach ($variant->stones as $stone)
                                                    <span class="px-2 py-0.5 bg-purple-50 dark:bg-purple-950/80 border border-purple-200 dark:border-purple-800 text-purple-700 dark:text-purple-300 text-[10px] rounded-full font-semibold">
                                                        💎 {{ $stone->stoneType?->name ?? 'Gem' }} {{ $stone->carat_weight ? "({$stone->carat_weight} ct)" : '' }} {{ $stone->clarity ? "• {$stone->clarity}" : '' }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif

                                        <!-- Stock & Hold Status Indicators -->
                                        <div class="flex items-center justify-between pt-1 border-t border-slate-100 dark:border-slate-800/40 text-[11px]">
                                            <div class="flex items-center gap-2">
                                                <span :class="getVariantStock({{ $variant->id }}) > 0 ? 'text-emerald-600 dark:text-emerald-400 font-semibold' : 'text-rose-600 dark:text-rose-400 font-semibold'">
                                                    <span x-text="getVariantStock({{ $variant->id }}) > 0 ? ('In Stock: ' + getVariantStock({{ $variant->id }})) : 'Out of Stock'">{{ $variant->stock > 0 ? 'In Stock: ' . $variant->stock : 'Out of Stock' }}</span>
                                                </span>
                                            </div>

                                            <!-- Green pill with hourglass and live timer only -->
                                            <template x-if="hasHoldForVariant({{ $variant->id }})">
                                                <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950 border border-emerald-300 dark:border-emerald-700 text-emerald-700 dark:text-emerald-400 text-[11px] font-mono font-bold flex items-center gap-1.5 shadow-sm">
                                                    <span>⏳</span>
                                                    <span x-text="getVariantCountdown({{ $variant->id }})"></span>
                                                </span>
                                            </template>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Reservation Form or Role Restriction Notice -->
                        @if (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isVendor()))
                            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 space-y-3">
                                <div class="p-4 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 rounded-2xl text-xs text-amber-700 dark:text-amber-400 font-semibold flex items-center gap-2">
                                    <span class="text-base">🔒</span>
                                    <span>Logged in as <strong>{{ auth()->user()->isAdmin() ? 'Administrator' : 'Vendor' }}</strong>. Stock reservation is available for public buyers/customers.</span>
                                </div>
                            </div>
                        @else
                            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 space-y-4">
                                <div>
                                    <div class="flex items-center justify-between mb-1.5">
                                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                            Quantity
                                        </label>
                                        <span class="text-[11px] font-semibold" :class="isOutOfStock ? 'text-rose-600 dark:text-rose-400' : 'text-slate-500 dark:text-slate-400'" x-show="selectedVariant">
                                            <span x-show="!isOutOfStock">Available in stock: <strong class="text-amber-600 dark:text-amber-400" x-text="selectedVariant ? selectedVariant.stock : 0"></strong></span>
                                            <span x-show="isOutOfStock" class="font-bold">Currently Sold Out</span>
                                        </span>
                                    </div>

                                    <!-- In-Stock Excess Quantity Warning -->
                                    <div x-show="!isOutOfStock && !activeHoldForSelectedVariant && selectedVariant && quantity > selectedVariant.stock"
                                         x-cloak
                                         class="mb-2 text-xs text-amber-800 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 px-3 py-2 rounded-xl flex items-center gap-2">
                                        <span>⚠️</span>
                                        <span>Only <strong x-text="selectedVariant.stock"></strong> unit(s) available in stock. Please choose a quantity between 1 and <span x-text="selectedVariant.stock"></span>.</span>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                                        <!-- Stepper Controls (Left - 5 Cols) -->
                                        <div :class="(isOutOfStock || activeHoldForSelectedVariant) ? 'opacity-40 pointer-events-none' : ''"
                                             class="sm:col-span-5 flex items-center bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl p-1 shadow-inner transition-opacity">
                                            <button type="button"
                                                    @click="decrementQuantity()"
                                                    :disabled="isOutOfStock || activeHoldForSelectedVariant || quantity <= 1"
                                                    class="w-10 h-10 flex items-center justify-center text-slate-700 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-slate-200 dark:hover:bg-slate-900 rounded-xl disabled:opacity-30 disabled:cursor-not-allowed transition-all font-bold text-base">
                                                –
                                            </button>
                                            <input type="number"
                                                   x-model.number="quantity"
                                                   @input="validateQuantityInput()"
                                                   :disabled="isOutOfStock || activeHoldForSelectedVariant"
                                                   min="1"
                                                   :max="selectedVariant ? selectedVariant.stock : 1"
                                                   class="w-full text-center bg-transparent border-0 text-slate-900 dark:text-slate-100 font-bold text-sm focus:ring-0 p-0 disabled:text-slate-400" />
                                            <button type="button"
                                                    @click="incrementQuantity()"
                                                    :disabled="isOutOfStock || activeHoldForSelectedVariant || !selectedVariant || quantity >= selectedVariant.stock"
                                                    class="w-10 h-10 flex items-center justify-center text-slate-700 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-slate-200 dark:hover:bg-slate-900 rounded-xl disabled:opacity-30 disabled:cursor-not-allowed transition-all font-bold text-base">
                                                +
                                            </button>
                                        </div>

                                        <!-- Action Button (Right - 7 Cols) -->
                                        <div class="sm:col-span-7">
                                            <!-- If Held: Green Release Button -->
                                            <template x-if="activeHoldForSelectedVariant">
                                                <form method="POST" :action="'/reservations/' + activeHoldForSelectedVariant.id">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs uppercase tracking-wider rounded-2xl shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-1.5">
                                                        <span>Release Hold</span>
                                                    </button>
                                                </form>
                                            </template>

                                            <!-- If Not Held: Yellow Reserve Button -->
                                            <template x-if="!activeHoldForSelectedVariant">
                                                <form method="POST" action="{{ route('reservations.store') }}" novalidate>
                                                    @csrf
                                                    <input type="hidden" name="product_variant_id" :value="selectedVariantId">
                                                    <input type="hidden" name="idempotency_key" :value="idempotencyKey">
                                                    <input type="hidden" name="quantity" :value="quantity">
                                                    <button type="submit"
                                                            :disabled="isOutOfStock || quantity < 1 || quantity > (selectedVariant ? selectedVariant.stock : 0)"
                                                            class="w-full py-3 bg-gold-gradient text-slate-950 font-bold text-xs uppercase tracking-wider rounded-2xl shadow-md hover:shadow-lg hover:brightness-105 disabled:opacity-40 disabled:cursor-not-allowed disabled:grayscale transition-all">
                                                        <span x-show="!isOutOfStock">Hold for 15 Mins</span>
                                                        <span x-show="isOutOfStock">Out of Stock</span>
                                                    </button>
                                                </form>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function productReservation() {
            const rawVariants = @json($product->variants);
            const userReservationsMap = @json($userReservations);

            const initialVariant = rawVariants.length > 0 ? rawVariants[0] : null;

            return {
                productId: {{ $product->id }},
                variants: rawVariants,
                selectedVariantId: initialVariant ? initialVariant.id : null,
                userReservations: userReservationsMap,
                quantity: (initialVariant && initialVariant.stock > 0) ? 1 : 0,
                idempotencyKey: 'idemp-' + Math.random().toString(36).substr(2, 9) + '-' + Date.now(),
                now: Date.now(),
                ticker: null,

                init() {
                    this.ticker = setInterval(() => {
                        this.now = Date.now();
                        this.cleanupExpiredHolds();
                    }, 1000);

                    // Subscribe to real-time WebSockets via Laravel Echo / Reverb
                    if (window.Echo) {
                        window.Echo.channel(`products.${this.productId}`)
                            .listen('.ProductStockUpdated', (e) => {
                                this.handleStockBroadcast(e);
                            });
                    }
                },

                handleStockBroadcast(e) {
                    const v = this.variants.find(item => item.id === e.variantId);
                    if (v) {
                        v.stock = e.stock;
                        v.status = e.status;
                        if (this.selectedVariantId === e.variantId) {
                            if (v.stock <= 0) {
                                this.quantity = 0;
                            } else if (this.quantity > v.stock) {
                                this.quantity = v.stock;
                            } else if (this.quantity === 0 && v.stock > 0 && !this.activeHoldForSelectedVariant) {
                                this.quantity = 1;
                            }
                        }
                    }
                },

                cleanupExpiredHolds() {
                    if (!this.userReservations) return;
                    for (const varId in this.userReservations) {
                        const hold = this.userReservations[varId];
                        if (hold && hold.expires_at) {
                            const diff = new Date(hold.expires_at).getTime() - this.now;
                            if (diff <= 0) {
                                // Restore stock dynamically to the reactive variant state
                                const v = this.variants.find(item => item.id == varId);
                                if (v && hold.quantity) {
                                    v.stock += hold.quantity;
                                    if (this.selectedVariantId == varId && (this.quantity === 0 || this.quantity > v.stock) && v.stock > 0) {
                                        this.quantity = 1;
                                    }
                                }
                                delete this.userReservations[varId];
                            }
                        }
                    }
                },

                getVariantStock(variantId) {
                    const v = this.variants.find(item => item.id === variantId);
                    return v ? v.stock : 0;
                },

                selectVariant(variantId) {
                    this.selectedVariantId = variantId;
                    const v = this.variants.find(item => item.id === variantId);
                    this.quantity = (v && v.stock > 0) ? 1 : 0;
                },

                get selectedVariant() {
                    return this.variants.find(v => v.id === this.selectedVariantId) || null;
                },

                get isOutOfStock() {
                    return !this.selectedVariant || this.selectedVariant.stock <= 0;
                },

                get activeHoldForSelectedVariant() {
                    if (!this.selectedVariantId || !this.userReservations) return null;
                    const hold = this.userReservations[this.selectedVariantId];
                    if (!hold || !hold.expires_at) return null;
                    return (new Date(hold.expires_at).getTime() > this.now) ? hold : null;
                },

                incrementQuantity() {
                    const max = this.selectedVariant ? this.selectedVariant.stock : 0;
                    if (this.quantity < max) {
                        this.quantity++;
                    }
                },

                decrementQuantity() {
                    if (this.quantity > 1) {
                        this.quantity--;
                    }
                },

                validateQuantityInput() {
                    if (this.isOutOfStock || this.activeHoldForSelectedVariant) {
                        this.quantity = 0;
                        return;
                    }
                    if (isNaN(this.quantity) || this.quantity < 1) {
                        this.quantity = 1;
                    }
                },

                hasHoldForVariant(variantId) {
                    if (!this.userReservations || !this.userReservations[variantId]) return false;
                    const hold = this.userReservations[variantId];
                    return hold && hold.expires_at && (new Date(hold.expires_at).getTime() > this.now);
                },

                getVariantCountdown(variantId) {
                    const hold = this.userReservations[variantId];
                    if (!hold || !hold.expires_at) return '';
                    const sec = Math.max(0, Math.floor((new Date(hold.expires_at).getTime() - this.now) / 1000));
                    const mins = Math.floor(sec / 60);
                    const secs = sec % 60;
                    return `${mins}:${secs < 10 ? '0' : ''}${secs}`;
                }
            };
        }
    </script>
</x-app-layout>
