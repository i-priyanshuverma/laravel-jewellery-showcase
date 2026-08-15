<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-xl text-slate-900 dark:text-slate-100 leading-tight">{{ __('Edit Metal:') }} {{ $metal->name }}</h2>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-900 dark:text-slate-100 transition-colors duration-200">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-2xl transition-colors" x-data="metalEditForm()">
                <form method="POST" action="{{ route('admin.metals.update', $metal) }}" class="space-y-6">
                    @csrf @method('PUT')

                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-2">
                            <x-input-label for="name" :value="__('Metal Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $metal->name)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>
                        <div>
                            <x-input-label for="sort_order" :value="__('Sort Order')" />
                            <x-text-input id="sort_order" name="sort_order" type="number" min="0" class="mt-1 block w-full" :value="old('sort_order', $metal->sort_order)" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="status" :value="__('Status')" />
                        <select id="status" name="status" class="mt-1 block w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-900 dark:text-slate-100 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                            <option value="active" {{ old('status', $metal->status) == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                            <option value="inactive" {{ old('status', $metal->status) == 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                        </select>
                    </div>

                    <!-- Inline Purities -->
                    <div class="pt-6 border-t border-slate-100 dark:border-slate-800">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xs font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wider">{{ __('Purities') }}</h3>
                            <button type="button" @click="addPurity()" class="text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline">{{ __('+ Add Purity') }}</button>
                        </div>

                        <template x-for="(p, index) in purities" :key="index">
                            <div class="grid grid-cols-12 gap-2 mb-3 items-end">
                                <input type="hidden" :name="'purities[' + index + '][id]'" :value="p.id || ''">
                                <div class="col-span-4">
                                    <label class="text-[10px] text-slate-500 dark:text-slate-400 uppercase font-semibold">{{ __('Display Name') }}</label>
                                    <input type="text" :name="'purities[' + index + '][name]'" x-model="p.name" class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-sm text-slate-900 dark:text-slate-100 px-3 py-2" placeholder="22K (916)">
                                </div>
                                <div class="col-span-2">
                                    <label class="text-[10px] text-slate-500 dark:text-slate-400 uppercase font-semibold">{{ __('Value') }}</label>
                                    <input type="text" :name="'purities[' + index + '][value]'" x-model="p.value" class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-sm text-slate-900 dark:text-slate-100 px-3 py-2" placeholder="22K">
                                </div>
                                <div class="col-span-2">
                                    <label class="text-[10px] text-slate-500 dark:text-slate-400 uppercase font-semibold">{{ __('Sort') }}</label>
                                    <input type="number" :name="'purities[' + index + '][sort_order]'" x-model="p.sort_order" min="0" class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-sm text-slate-900 dark:text-slate-100 px-3 py-2">
                                </div>
                                <div class="col-span-2">
                                    <label class="text-[10px] text-slate-500 dark:text-slate-400 uppercase font-semibold">{{ __('Status') }}</label>
                                    <select :name="'purities[' + index + '][status]'" x-model="p.status" class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-[11px] text-slate-900 dark:text-slate-100 px-2 py-2">
                                        <option value="active">{{ __('Active') }}</option>
                                        <option value="inactive">{{ __('Inactive') }}</option>
                                    </select>
                                </div>
                                <div class="col-span-2 text-right">
                                    <button type="button" @click="purities.splice(index, 1)" class="text-xs text-rose-600 dark:text-rose-400 hover:underline font-semibold">{{ __('Remove') }}</button>
                                </div>
                            </div>
                        </template>

                        <p x-show="purities.length === 0" class="text-xs text-slate-500 italic">{{ __('No purities. Click + Add Purity.') }}</p>
                    </div>

                    <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <a href="{{ route('admin.metals.index') }}" class="text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200">{{ __('Cancel') }}</a>
                        <x-primary-button>{{ __('Update Metal') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function metalEditForm() {
            return {
                purities: @json($metal->purities->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'value' => $p->value, 'sort_order' => $p->sort_order, 'status' => $p->status])),
                addPurity() {
                    this.purities.push({ id: null, name: '', value: '', sort_order: this.purities.length + 1, status: 'active' });
                }
            };
        }
    </script>
</x-app-layout>
