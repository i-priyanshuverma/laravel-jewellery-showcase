<x-app-layout>
    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-900 dark:text-slate-100 transition-colors duration-200" x-data="reservationsPage()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200 dark:border-slate-800 pb-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 dark:text-slate-100">
                        My Active Reservations
                    </h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        Jewellery pieces currently locked exclusively for your session via 15-minute concurrency holds.
                    </p>
                </div>

                @if ($reservations->isNotEmpty())
                    <form method="POST" action="{{ route('reservations.destroyAll') }}" onsubmit="event.preventDefault(); window.confirmAction({ title: 'Release All Reserved Items', message: 'Are you sure you want to release all your active stock reservations? These items will return to available stock for other buyers immediately.', confirmText: 'Release All Holds', icon: 'warning', form: this });">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-white dark:bg-slate-900 hover:bg-rose-50 dark:hover:bg-rose-950 border border-slate-300 dark:border-slate-700 hover:border-rose-300 dark:hover:border-rose-700 text-rose-600 dark:text-rose-400 text-xs font-bold rounded-xl transition shadow-sm">
                            ✕ Release All Holds
                        </button>
                    </form>
                @endif
            </div>

            <x-flash-message />

            @if ($reservations->isEmpty())
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-16 text-center space-y-6 max-w-2xl mx-auto shadow-sm">
                    <div class="w-20 h-20 bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full flex items-center justify-center text-4xl mx-auto shadow-inner">
                        ⏱
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-bold text-slate-800 dark:text-slate-200">No Active Reservations</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 max-w-md mx-auto leading-relaxed">
                            You currently do not have any jewellery pieces on hold. When you reserve a variant on a product page, it will be locked here for 15 minutes.
                        </p>
                    </div>
                    <a href="{{ route('products.index') }}" class="inline-block px-6 py-3 bg-gold-gradient text-slate-950 font-bold text-xs uppercase tracking-wider rounded-xl shadow-md hover:shadow-lg hover:brightness-105 transition">
                        Explore Fine Jewellery Catalogue
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <div class="lg:col-span-8 space-y-4">
                        @foreach ($reservations as $res)
                            @php
                                $variant = $res->variant;
                                $product = $variant->product;
                                $subtotal = $variant->price * $res->quantity;
                                $expiryTimestamp = $res->expires_at->timestamp * 1000;
                            @endphp

                            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-xl p-6 flex flex-col sm:flex-row gap-6 items-start justify-between relative overflow-hidden transition-colors"
                                 x-data="reservationItem({{ $res->id }}, {{ $expiryTimestamp }})">

                                <div class="flex gap-5 items-start">
                                    <a href="{{ route('products.show', $product) }}" class="w-24 h-24 rounded-2xl bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 overflow-hidden flex-shrink-0 group shadow-sm">
                                        <img src="{{ $product->primary_image_url }}"
                                             alt="{{ $product->name }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform"
                                             onerror="this.onerror=null; this.src='{{ asset('images/products/placeholder.svg') }}';">
                                    </a>

                                    <div class="space-y-2">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-[10px] font-bold text-amber-700 dark:text-amber-400 rounded-full">
                                                {{ $product->category?->name ?? 'Fine Jewellery' }}
                                            </span>
                                            <span class="text-[11px] text-slate-500 dark:text-slate-400 font-semibold flex items-center gap-1">
                                                <span>🏪</span> {{ $product->vendor?->vendorProfile?->business_name ?? $product->vendor?->name }}
                                            </span>
                                        </div>

                                        <h3 class="font-bold text-base text-slate-900 dark:text-slate-100 hover:text-amber-600 dark:hover:text-amber-400 transition-colors">
                                            <a href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
                                        </h3>

                                        <div class="text-xs text-slate-500 dark:text-slate-400 flex flex-wrap items-center gap-2">
                                            <span class="font-mono text-amber-700 dark:text-amber-300 font-medium">SKU: {{ $variant->sku }}</span>
                                            <span>•</span>
                                            <span>{{ $variant->metal }} {{ $variant->purity }}</span>
                                            <span>•</span>
                                            <span>Color: {{ $variant->colour ?? 'N/A' }}</span>
                                            <span>•</span>
                                            <span>Size: {{ $variant->size ?? 'N/A' }}</span>
                                        </div>

                                        @if ($variant->stones->isNotEmpty())
                                            <div class="flex flex-wrap items-center gap-1.5 pt-1">
                                                @foreach ($variant->stones as $stone)
                                                    <span class="px-2 py-0.5 bg-purple-50 dark:bg-purple-950/80 border border-purple-200 dark:border-purple-800 text-purple-700 dark:text-purple-300 text-[10px] rounded-full font-semibold">
                                                        💎 {{ $stone->stoneType?->name ?? 'Gem' }} {{ $stone->carat_weight ? "({$stone->carat_weight} ct)" : '' }} {{ $stone->clarity ? "• {$stone->clarity}" : '' }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-row sm:flex-col items-between sm:items-end justify-between w-full sm:w-auto border-t sm:border-t-0 border-slate-100 dark:border-slate-800 pt-4 sm:pt-0 space-y-3">
                                    <div class="text-left sm:text-right">
                                        <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Quantity Held</span>
                                        <span class="font-bold text-sm text-slate-800 dark:text-slate-100">{{ $res->quantity }} unit(s)</span>
                                        <span class="font-bold text-amber-600 dark:text-amber-400 text-lg block mt-0.5">
                                            ₹{{ number_format($subtotal, 2) }}
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 dark:bg-amber-950/50 border border-amber-200 dark:border-amber-500/40 rounded-xl text-xs font-mono font-bold text-amber-800 dark:text-amber-300">
                                        <span>⏱</span>
                                        <span x-text="countdown"></span>
                                    </div>

                                    <form method="POST" action="{{ route('reservations.destroy', $res) }}" onsubmit="event.preventDefault(); window.confirmAction({ title: 'Release Stock Hold', message: 'Release hold on {{ addslashes($variant->product->name) }} ({{ addslashes($variant->sku) }})? This item will return to available stock for other customers.', confirmText: 'Release Hold', icon: 'warning', form: this });">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline transition">
                                            Release Hold
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="lg:col-span-4 space-y-6">
                        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-xl space-y-6 sticky top-24 transition-colors">
                            <h3 class="font-bold text-slate-900 dark:text-slate-100 text-lg border-b border-slate-100 dark:border-slate-800 pb-3">
                                Summary
                            </h3>

                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between text-slate-500 dark:text-slate-400">
                                    <span>Total Held Items</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-100">{{ $reservations->sum('quantity') }} unit(s)</span>
                                </div>
                                <div class="flex justify-between text-slate-500 dark:text-slate-400">
                                    <span>Unique Variations</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-100">{{ $reservations->count() }}</span>
                                </div>
                                <div class="flex justify-between text-base font-bold text-slate-900 dark:text-slate-100 pt-3 border-t border-slate-100 dark:border-slate-800">
                                    <span>Total Value</span>
                                    <span class="font-bold text-amber-600 dark:text-amber-400 text-xl">
                                        ₹{{ number_format($reservations->sum(fn($r) => $r->variant->price * $r->quantity), 2) }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-[11px] text-slate-600 dark:text-slate-400 space-y-2 leading-relaxed">
                                <div class="flex items-center gap-1.5 font-bold text-amber-700 dark:text-amber-400">
                                    <span>🔒</span>
                                    <span>How 15-Minute Holds Work</span>
                                </div>
                                <p>
                                    Stock is temporarily reserved using database row-level locking. If not confirmed before the timer expires, items are automatically returned to public inventory.
                                </p>
                            </div>

                            <a href="{{ route('products.index') }}" class="block text-center w-full py-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 hover:text-amber-600 dark:hover:text-amber-400 font-bold text-xs uppercase tracking-wider rounded-xl border border-slate-200 dark:border-slate-700 transition shadow-sm">
                                &larr; Continue Exploring
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function reservationsPage() {
            return {};
        }

        function reservationItem(id, expiryTimestamp) {
            return {
                id: id,
                expiry: expiryTimestamp,
                remainingSeconds: Math.max(0, Math.floor((expiryTimestamp - new Date().getTime()) / 1000)),
                timer: null,

                init() {
                    if (this.remainingSeconds > 0) {
                        this.timer = setInterval(() => {
                            this.remainingSeconds = Math.max(0, Math.floor((this.expiry - new Date().getTime()) / 1000));
                            if (this.remainingSeconds <= 0) {
                                clearInterval(this.timer);
                                window.location.reload();
                            }
                        }, 1000);
                    }
                },

                get countdown() {
                    const mins = Math.floor(this.remainingSeconds / 60);
                    const secs = this.remainingSeconds % 60;
                    return `${mins}:${secs < 10 ? '0' : ''}${secs}`;
                }
            };
        }
    </script>
</x-app-layout>
