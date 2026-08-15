<x-app-layout>
    <x-slot name="header"><h2 class="font-extrabold text-xl text-slate-900 dark:text-slate-100 leading-tight">{{ __('Edit Stone Type:') }} {{ $stoneType->name }}</h2></x-slot>
    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-900 dark:text-slate-100 transition-colors duration-200">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-2xl transition-colors">
                <form method="POST" action="{{ route('admin.stone-types.update', $stoneType) }}" class="space-y-6">
                    @csrf @method('PUT')
                    <div><x-input-label for="name" :value="__('Stone Name')" /><x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $stoneType->name)" required autofocus /><x-input-error class="mt-2" :messages="$errors->get('name')" /></div>
                    <div><x-input-label for="sort_order" :value="__('Sort Order')" /><x-text-input id="sort_order" name="sort_order" type="number" min="0" class="mt-1 block w-full" :value="old('sort_order', $stoneType->sort_order)" /></div>
                    <div><x-input-label for="status" :value="__('Status')" /><select id="status" name="status" class="mt-1 block w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-900 dark:text-slate-100 focus:ring-amber-500 focus:border-amber-500 transition-colors"><option value="active" {{ old('status', $stoneType->status) === 'active' ? 'selected' : '' }}>{{ __('Active') }}</option><option value="inactive" {{ old('status', $stoneType->status) === 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option></select></div>
                    <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <a href="{{ route('admin.stone-types.index') }}" class="text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200">{{ __('Cancel') }}</a>
                        <x-primary-button>{{ __('Update Stone Type') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
