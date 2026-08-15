<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-xl text-slate-900 dark:text-slate-100 leading-tight">
                {{ __('Edit Variant:') }} {{ $variant->sku }}
            </h2>
            <span class="text-xs text-slate-600 dark:text-slate-400 px-3 py-1 bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-full">
                {{ __('Product:') }} <strong class="text-amber-700 dark:text-amber-400">{{ $product->name }}</strong>
            </span>
        </div>
    </x-slot>

    @php
        $puritiesByMetal = [];
        foreach ($metals as $m) {
            $puritiesByMetal[$m->name] = $m->activePurities->map(fn($p) => [
                'name' => $p->name,
                'value' => $p->value,
            ])->values()->all();
        }

        $existingStones = $variant->stones->map(fn($s) => [
            'stone_type_id' => (string) $s->stone_type_id,
            'carat_weight' => $s->carat_weight ? (string) $s->carat_weight : '',
            'clarity' => $s->clarity ?? '',
            'setting_type' => $s->setting_type ?? '',
        ])->values()->all();
    @endphp

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-900 dark:text-slate-100 transition-colors duration-200" x-data="variantEditForm()">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-2xl space-y-8 transition-colors">
                <x-flash-message />

                <form method="POST" action="{{ route('vendor.products.variants.update', [$product, $variant]) }}" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <!-- Primary Identifiers & Financials -->
                    <div>
                        <h3 class="text-xs font-bold text-amber-700 dark:text-amber-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <span>{{ __('1. SKU, Pricing & Inventory') }}</span>
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="sku" :value="__('SKU (Stock Keeping Unit)')" />
                                <x-text-input id="sku" name="sku" type="text" class="mt-1 block w-full uppercase font-mono" :value="old('sku', $variant->sku)" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('sku')" />
                            </div>

                            <div>
                                <x-input-label for="price" :value="__('Price (₹)')" />
                                <x-text-input id="price" name="price" type="number" step="0.01" min="0.01" class="mt-1 block w-full" :value="old('price', $variant->price)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('price')" />
                            </div>

                            <div>
                                <x-input-label for="stock" :value="__('Available Stock Quantity')" />
                                <x-text-input id="stock" name="stock" type="number" min="0" class="mt-1 block w-full" :value="old('stock', $variant->stock)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('stock')" />
                            </div>
                        </div>
                    </div>

                    <!-- Base Metal & Material Specifications -->
                    <div class="pt-6 border-t border-slate-100 dark:border-slate-800">
                        <h3 class="text-xs font-bold text-amber-700 dark:text-amber-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <span>{{ __('2. Standardized Metal & Attributes') }}</span>
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Metal Select -->
                            <div>
                                <x-input-label for="metal" :value="__('Base Metal')" />
                                <select id="metal" name="metal" x-model="selectedMetal" @change="onMetalChange()" class="mt-1 block w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-900 dark:text-slate-100 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                                    <option value="">-- {{ __('Select Metal') }} --</option>
                                    @foreach ($metals as $metal)
                                        <option value="{{ $metal->name }}">
                                            {{ $metal->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('metal')" />
                            </div>

                            <!-- Cascading Purity Select -->
                            <div>
                                <x-input-label for="purity" :value="__('Metal Purity')" />
                                <select id="purity" name="purity" x-model="selectedPurity" :disabled="availablePurities.length === 0" class="mt-1 block w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-900 dark:text-slate-100 focus:ring-amber-500 focus:border-amber-500 disabled:opacity-50 transition-colors">
                                    <option value="">-- {{ __('Select Purity') }} --</option>
                                    <template x-for="purity in availablePurities" :key="purity.value">
                                        <option :value="purity.value" x-text="purity.name" :selected="selectedPurity === purity.value"></option>
                                    </template>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('purity')" />
                            </div>

                            <!-- Colour Select -->
                            <div>
                                <x-input-label for="colour" :value="__('Metal Colour / Finish')" />
                                <select id="colour" name="colour" class="mt-1 block w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-900 dark:text-slate-100 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                                    <option value="">-- {{ __('Select Colour') }} --</option>
                                    @foreach ($colours as $colour)
                                        <option value="{{ $colour->name }}" {{ old('colour', $variant->colour) === $colour->name ? 'selected' : '' }}>
                                            {{ $colour->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('colour')" />
                            </div>
                        </div>
                    </div>

                    <!-- Category-Scoped Sizing & Weight -->
                    <div class="pt-6 border-t border-slate-100 dark:border-slate-800">
                        <h3 class="text-xs font-bold text-amber-700 dark:text-amber-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <span>{{ __('3. Sizing & Weight') }}</span>
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Size Select -->
                            <div>
                                <x-input-label for="size" :value="__('Size') . ' (' . ($product->category?->name ?? __('Universal')) . ')'" />
                                <select id="size" name="size" class="mt-1 block w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-900 dark:text-slate-100 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                                    <option value="">-- {{ __('Select Size') }} --</option>
                                    @foreach ($sizes as $size)
                                        <option value="{{ $size->value }}" {{ old('size', $variant->size) === $size->value ? 'selected' : '' }}>
                                            {{ $size->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('size')" />
                            </div>

                            <!-- Metal Weight -->
                            <div>
                                <x-input-label for="weight" :value="__('Gross Weight (Grams)')" />
                                <x-text-input id="weight" name="weight" type="number" step="0.001" min="0.001" class="mt-1 block w-full" :value="old('weight', $variant->weight)" placeholder="e.g. 4.500" />
                                <x-input-error class="mt-2" :messages="$errors->get('weight')" />
                            </div>

                            <!-- Variant Status -->
                            <div>
                                <x-input-label for="status" :value="__('Variant Status')" />
                                <select id="status" name="status" class="mt-1 block w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-900 dark:text-slate-100 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                                    <option value="active" {{ old('status', $variant->status) === 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                    <option value="inactive" {{ old('status', $variant->status) === 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                </select>
                                <span class="text-[11px] text-slate-400 dark:text-slate-500 mt-1 block">{{ __('Deactivating releases any active customer stock reservations.') }}</span>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>
                        </div>
                    </div>

                    <!-- Multi-Material Stones & Gemstones Section -->
                    <div class="pt-6 border-t border-slate-100 dark:border-slate-800">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-xs font-bold text-amber-700 dark:text-amber-400 uppercase tracking-widest flex items-center gap-2">
                                    <span>{{ __('4. Stones & Gemstones (Multi-Material Support)') }}</span>
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Manage diamonds, rubies, emeralds, or other gems set in this piece.') }}</p>
                            </div>
                            <button type="button" @click="addStone()" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-amber-700 dark:text-amber-400 text-xs font-bold rounded-xl border border-slate-200 dark:border-slate-700 transition shadow-sm">
                                + {{ __('Add Stone / Gem') }}
                            </button>
                        </div>

                        <div class="space-y-4">
                            <template x-for="(stone, index) in stones" :key="index">
                                <div class="bg-slate-50 dark:bg-slate-950 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 grid grid-cols-1 md:grid-cols-12 gap-4 items-end transition-colors">
                                    <!-- Stone Type -->
                                    <div class="md:col-span-3">
                                        <label class="text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider block mb-1">{{ __('Stone Type') }}</label>
                                        <select :name="'stones[' + index + '][stone_type_id]'" x-model="stone.stone_type_id" class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs text-slate-900 dark:text-slate-100 focus:ring-amber-500 focus:border-amber-500" required>
                                            <option value="">-- {{ __('Stone') }} --</option>
                                            @foreach ($stoneTypes as $st)
                                                <option value="{{ $st->id }}">{{ $st->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Carat Weight -->
                                    <div class="md:col-span-3">
                                        <label class="text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider block mb-1">{{ __('Carat Weight (ct)') }}</label>
                                        <input type="number" step="0.001" min="0.001" :name="'stones[' + index + '][carat_weight]'" x-model="stone.carat_weight" placeholder="e.g. 0.500" class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs text-slate-900 dark:text-slate-100">
                                    </div>

                                    <!-- Clarity -->
                                    <div class="md:col-span-3">
                                        <label class="text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider block mb-1">{{ __('Clarity / Grade') }}</label>
                                        <input type="text" :name="'stones[' + index + '][clarity]'" x-model="stone.clarity" placeholder="e.g. VS1, VVS" class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs text-slate-900 dark:text-slate-100">
                                    </div>

                                    <!-- Setting Type -->
                                    <div class="md:col-span-2">
                                        <label class="text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider block mb-1">{{ __('Setting') }}</label>
                                        <input type="text" :name="'stones[' + index + '][setting_type]'" x-model="stone.setting_type" placeholder="e.g. Prong, Pavé" class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs text-slate-900 dark:text-slate-100">
                                    </div>

                                    <!-- Remove Button -->
                                    <div class="md:col-span-1 text-right">
                                        <button type="button" @click="removeStone(index)" class="p-2 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/50 rounded-xl transition" title="{{ __('Remove stone') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <div x-show="stones.length === 0" class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-950/50 border border-dashed border-slate-200 dark:border-slate-800 text-center text-xs text-slate-500">
                                {{ __('No secondary stones added. (Optional — click + Add Stone / Gem if this item has diamonds or gems).') }}
                            </div>
                        </div>
                    </div>

                    <!-- Submission Controls -->
                    <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <a href="{{ route('vendor.products.show', $product) }}" class="text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition">{{ __('Cancel') }}</a>
                        <x-primary-button>{{ __('Update Variant') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function variantEditForm() {
            const puritiesMap = @json($puritiesByMetal);
            const initialMetal = @json(old('metal', $variant->metal ?? ''));
            const initialPurity = @json(old('purity', $variant->purity ?? ''));

            return {
                selectedMetal: initialMetal,
                selectedPurity: initialPurity,
                availablePurities: initialMetal && puritiesMap[initialMetal] ? puritiesMap[initialMetal] : [],
                stones: @json(old('stones', $existingStones)),

                onMetalChange() {
                    if (this.selectedMetal && puritiesMap[this.selectedMetal]) {
                        this.availablePurities = puritiesMap[this.selectedMetal];
                        if (!this.availablePurities.some(p => p.value === this.selectedPurity)) {
                            this.selectedPurity = '';
                        }
                    } else {
                        this.availablePurities = [];
                        this.selectedPurity = '';
                    }
                },

                addStone() {
                    this.stones.push({
                        stone_type_id: '',
                        carat_weight: '',
                        clarity: '',
                        setting_type: ''
                    });
                },

                removeStone(index) {
                    this.stones.splice(index, 1);
                }
            };
        }
    </script>
</x-app-layout>
