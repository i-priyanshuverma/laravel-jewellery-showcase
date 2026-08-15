<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.products.index') }}" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 transition text-sm shadow-sm" title="{{ __('Back to Products') }}">
                    &larr;
                </a>
                <div>
                    <div class="flex items-center gap-2.5">
                        <h2 class="font-extrabold text-xl text-slate-900 dark:text-slate-100 leading-tight">
                            {{ $product->name }}
                        </h2>
                        <x-status-badge :status="$product->status" />
                        @if ($product->is_featured)
                            <span class="px-2.5 py-0.5 text-[10px] uppercase tracking-wider font-extrabold bg-amber-100 dark:bg-amber-950/80 text-amber-800 dark:text-amber-300 rounded-full border border-amber-300 dark:border-amber-700">
                                ⭐ {{ __('Featured') }}
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-400 font-mono mt-0.5">{{ __('Category:') }} <span class="font-bold text-slate-600 dark:text-slate-300">{{ $product->category->name }}</span> &bull; {{ __('Slug:') }} {{ $product->slug }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('admin.products.index') }}" class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white dark:bg-slate-100 dark:hover:bg-white dark:text-slate-950 text-xs font-bold rounded-xl transition shadow-sm">
                    &larr; {{ __('Back to Catalog') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-900 dark:text-slate-100 transition-colors duration-200">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash-message />

            <x-product-detail-view :product="$product" :is-admin="true" />
        </div>
    </div>
</x-app-layout>
