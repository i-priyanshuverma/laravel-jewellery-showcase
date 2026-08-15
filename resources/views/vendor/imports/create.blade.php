<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-xl text-slate-900 dark:text-slate-100 leading-tight">
                {{ __('Bulk Product CSV Import') }}
            </h2>
            <a href="{{ route('vendor.imports.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200">
                &larr; {{ __('View Past Imports') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-900 dark:text-slate-100 transition-colors duration-200">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash-message />

            <!-- Sample Download & Quick Start Card -->
            <div class="bg-gradient-to-br from-amber-50 to-amber-100/60 dark:from-slate-900 dark:to-amber-950/30 p-6 sm:p-8 rounded-3xl border border-amber-200 dark:border-amber-500/20 shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2 text-amber-900 dark:text-amber-300 font-extrabold text-base">
                            <span>💎</span>
                            <span>{{ __('Standard CSV Import Template') }}</span>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-400 max-w-xl">
                            {{ __('Download the pre-formatted sample CSV containing example jewellery items, multiple variants per product, correct data types, and lookup codes.') }}
                        </p>
                    </div>

                    <a href="{{ route('vendor.imports.sample') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold rounded-2xl shadow-md hover:shadow-lg transition flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span>{{ __('Download Sample CSV') }}</span>
                    </a>
                </div>
            </div>

            <!-- Ingress Instructions & Pre-Flight Checklist -->
            <div class="bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-xl space-y-6 transition-colors">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                        <span>📋 {{ __('Ingress Instructions & Validation Rules') }}</span>
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        {{ __('Follow these strict guidelines to ensure your CSV file passes validation without errors.') }}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <!-- Rule 1: Draft Mode -->
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/80 space-y-1">
                        <div class="font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                            <span class="text-amber-600 dark:text-amber-400">🛡️</span>
                            <span>{{ __('Automatic Draft Protection') }}</span>
                        </div>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                            {{ __('All imported products will be saved in Draft status. This allows you to safely inspect listings, review data, and upload image galleries before taking them live.') }}
                        </p>
                    </div>

                    <!-- Rule 2: Multi-Variant Grouping -->
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/80 space-y-1">
                        <div class="font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                            <span class="text-amber-600 dark:text-amber-400">🔗</span>
                            <span>{{ __('Multi-Variant Grouping') }}</span>
                        </div>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                            {{ __('Rows sharing the exact same product_name and category will automatically group as multiple variants under a single product.') }}
                        </p>
                    </div>

                    <!-- Rule 3: Numeric Formatting -->
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/80 space-y-1">
                        <div class="font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                            <span class="text-amber-600 dark:text-amber-400">🔢</span>
                            <span>{{ __('Price & Weight Formatting') }}</span>
                        </div>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                            {{ __('price and weight must be plain numbers. Do not include currency symbols or thousand commas.') }}
                        </p>
                    </div>

                    <!-- Rule 4: Unique SKUs -->
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/80 space-y-1">
                        <div class="font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                            <span class="text-amber-600 dark:text-amber-400">🏷️</span>
                            <span>{{ __('SKU Requirements') }}</span>
                        </div>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                            {{ __('Every row must contain a unique sku (letters, numbers, hyphens, and underscores only). Re-uploading an existing SKU will update that variant.') }}
                        </p>
                    </div>
                </div>

                <!-- Column Reference Table -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                        {{ __('Column Dictionary & Accepted Values') }}
                    </h4>

                    <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 dark:bg-slate-800/80 text-slate-500 dark:text-slate-400 font-semibold border-b border-slate-200 dark:border-slate-800">
                                <tr>
                                    <th class="px-4 py-3">{{ __('Column Name') }}</th>
                                    <th class="px-4 py-3">{{ __('Required?') }}</th>
                                    <th class="px-4 py-3">{{ __('Format / Type') }}</th>
                                    <th class="px-4 py-3">{{ __('Example') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 font-mono text-[11px]">
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                    <td class="px-4 py-2.5 font-bold text-amber-600 dark:text-amber-400">product_name</td>
                                    <td class="px-4 py-2.5 text-emerald-600 dark:text-emerald-400 font-sans font-bold">{{ __('Required') }}</td>
                                    <td class="px-4 py-2.5 text-slate-600 dark:text-slate-400 font-sans">Text (2-255 chars)</td>
                                    <td class="px-4 py-2.5 text-slate-800 dark:text-slate-200">Gold Solitaire Ring</td>
                                </tr>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                    <td class="px-4 py-2.5 font-bold text-amber-600 dark:text-amber-400">category</td>
                                    <td class="px-4 py-2.5 text-emerald-600 dark:text-emerald-400 font-sans font-bold">{{ __('Required') }}</td>
                                    <td class="px-4 py-2.5 text-slate-600 dark:text-slate-400 font-sans">Category Name</td>
                                    <td class="px-4 py-2.5 text-slate-800 dark:text-slate-200">Rings, Bangles, Necklaces, etc.</td>
                                </tr>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                    <td class="px-4 py-2.5 font-bold text-amber-600 dark:text-amber-400">sku</td>
                                    <td class="px-4 py-2.5 text-emerald-600 dark:text-emerald-400 font-sans font-bold">{{ __('Required') }}</td>
                                    <td class="px-4 py-2.5 text-slate-600 dark:text-slate-400 font-sans">Alphanumeric Code</td>
                                    <td class="px-4 py-2.5 text-slate-800 dark:text-slate-200">GSR-18K-YG-12</td>
                                </tr>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                    <td class="px-4 py-2.5 font-bold text-amber-600 dark:text-amber-400">price</td>
                                    <td class="px-4 py-2.5 text-emerald-600 dark:text-emerald-400 font-sans font-bold">{{ __('Required') }}</td>
                                    <td class="px-4 py-2.5 text-slate-600 dark:text-slate-400 font-sans">Positive Decimal</td>
                                    <td class="px-4 py-2.5 text-slate-800 dark:text-slate-200">45000.00</td>
                                </tr>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                    <td class="px-4 py-2.5 font-bold text-amber-600 dark:text-amber-400">stock</td>
                                    <td class="px-4 py-2.5 text-emerald-600 dark:text-emerald-400 font-sans font-bold">{{ __('Required') }}</td>
                                    <td class="px-4 py-2.5 text-slate-600 dark:text-slate-400 font-sans">Integer (0 to 100,000)</td>
                                    <td class="px-4 py-2.5 text-slate-800 dark:text-slate-200">10</td>
                                </tr>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                    <td class="px-4 py-2.5 font-bold text-amber-600 dark:text-amber-400">description</td>
                                    <td class="px-4 py-2.5 text-slate-400 font-sans">{{ __('Optional') }}</td>
                                    <td class="px-4 py-2.5 text-slate-600 dark:text-slate-400 font-sans">Text description</td>
                                    <td class="px-4 py-2.5 text-slate-800 dark:text-slate-200">18K handcrafted yellow gold ring...</td>
                                </tr>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                    <td class="px-4 py-2.5 font-bold text-amber-600 dark:text-amber-400">metal</td>
                                    <td class="px-4 py-2.5 text-slate-400 font-sans">{{ __('Optional') }}</td>
                                    <td class="px-4 py-2.5 text-slate-600 dark:text-slate-400 font-sans">Gold, Silver, Platinum</td>
                                    <td class="px-4 py-2.5 text-slate-800 dark:text-slate-200">Gold</td>
                                </tr>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                    <td class="px-4 py-2.5 font-bold text-amber-600 dark:text-amber-400">purity</td>
                                    <td class="px-4 py-2.5 text-slate-400 font-sans">{{ __('Optional') }}</td>
                                    <td class="px-4 py-2.5 text-slate-600 dark:text-slate-400 font-sans">18K, 22K, 925 Silver, etc.</td>
                                    <td class="px-4 py-2.5 text-slate-800 dark:text-slate-200">18K</td>
                                </tr>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                    <td class="px-4 py-2.5 font-bold text-amber-600 dark:text-amber-400">colour</td>
                                    <td class="px-4 py-2.5 text-slate-400 font-sans">{{ __('Optional') }}</td>
                                    <td class="px-4 py-2.5 text-slate-600 dark:text-slate-400 font-sans">Yellow, White, Rose, Silver</td>
                                    <td class="px-4 py-2.5 text-slate-800 dark:text-slate-200">Yellow Gold</td>
                                </tr>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                    <td class="px-4 py-2.5 font-bold text-amber-600 dark:text-amber-400">size</td>
                                    <td class="px-4 py-2.5 text-slate-400 font-sans">{{ __('Optional') }}</td>
                                    <td class="px-4 py-2.5 text-slate-600 dark:text-slate-400 font-sans">Category Size Option</td>
                                    <td class="px-4 py-2.5 text-slate-800 dark:text-slate-200">Size 12 / 2.4 / 18 Inch</td>
                                </tr>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                    <td class="px-4 py-2.5 font-bold text-amber-600 dark:text-amber-400">weight</td>
                                    <td class="px-4 py-2.5 text-slate-400 font-sans">{{ __('Optional') }}</td>
                                    <td class="px-4 py-2.5 text-slate-600 dark:text-slate-400 font-sans">Grams Decimal</td>
                                    <td class="px-4 py-2.5 text-slate-800 dark:text-slate-200">4.200</td>
                                </tr>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                    <td class="px-4 py-2.5 font-bold text-amber-600 dark:text-amber-400">is_featured</td>
                                    <td class="px-4 py-2.5 text-slate-400 font-sans">{{ __('Optional') }}</td>
                                    <td class="px-4 py-2.5 text-slate-600 dark:text-slate-400 font-sans">yes, no, 1, 0</td>
                                    <td class="px-4 py-2.5 text-slate-800 dark:text-slate-200">yes</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Upload Form -->
                <form method="POST" action="{{ route('vendor.imports.store') }}" enctype="multipart/form-data" class="pt-6 border-t border-slate-100 dark:border-slate-800 space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="csv_file" :value="__('Select CSV File for Ingress')" />
                        <input id="csv_file" name="csv_file" type="file" accept=".csv,text/csv" class="mt-2 block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-bold file:uppercase file:tracking-wider file:bg-amber-600 file:text-white hover:file:bg-amber-500 cursor-pointer shadow-sm" required />
                        <x-input-error class="mt-2" :messages="$errors->get('csv_file')" />
                    </div>

                    <div class="flex items-center justify-between border-t border-slate-100 dark:border-slate-800 pt-6">
                        <a href="{{ route('vendor.imports.index') }}" class="text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200">{{ __('Cancel') }}</a>
                        <x-primary-button>{{ __('Upload & Start Ingress Processing →') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
