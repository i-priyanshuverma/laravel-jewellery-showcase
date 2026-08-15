<x-app-layout>
    <x-slot name="header"><h2 class="font-extrabold text-xl text-slate-900 dark:text-slate-100 leading-tight">Edit Size: {{ $size->name }}</h2></x-slot>
    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-900 dark:text-slate-100 transition-colors duration-200">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-2xl transition-colors">
                <form method="POST" action="{{ route('admin.sizes.update', $size) }}" class="space-y-6">
                    @csrf @method('PUT')
                    <div><x-input-label for="category_id" :value="__('Category')" /><select id="category_id" name="category_id" class="mt-1 block w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-900 dark:text-slate-100 focus:ring-amber-500 focus:border-amber-500 transition-colors"><option value="">Global (All Categories)</option>@foreach ($categories as $cat)<option value="{{ $cat->id }}" {{ old('category_id', $size->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach</select><x-input-error class="mt-2" :messages="$errors->get('category_id')" /></div>
                    <div><x-input-label for="name" :value="__('Display Name')" /><x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $size->name)" required autofocus /><x-input-error class="mt-2" :messages="$errors->get('name')" /></div>
                    <div><x-input-label for="value" :value="__('System Value')" /><x-text-input id="value" name="value" type="text" class="mt-1 block w-full font-mono" :value="old('value', $size->value)" required /><x-input-error class="mt-2" :messages="$errors->get('value')" /></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><x-input-label for="sort_order" :value="__('Sort Order')" /><x-text-input id="sort_order" name="sort_order" type="number" min="0" class="mt-1 block w-full" :value="old('sort_order', $size->sort_order)" /></div>
                        <div><x-input-label for="status" :value="__('Status')" /><select id="status" name="status" class="mt-1 block w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-900 dark:text-slate-100 focus:ring-amber-500 focus:border-amber-500 transition-colors"><option value="active" {{ old('status', $size->status) === 'active' ? 'selected' : '' }}>Active</option><option value="inactive" {{ old('status', $size->status) === 'inactive' ? 'selected' : '' }}>Inactive</option></select></div>
                    </div>
                    <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <a href="{{ route('admin.sizes.index') }}" class="text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200">Cancel</a>
                        <x-primary-button>Update Size</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
