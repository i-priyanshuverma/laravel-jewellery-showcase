<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-xl text-slate-900 dark:text-slate-100 leading-tight">
                {{ __('Jewellery Categories') }}
            </h2>
            <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-sm transition">{{ __('+ Add New Category') }}</a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-900 dark:text-slate-100 transition-colors duration-200">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash-message />

            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
                <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-950/60 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3.5">{{ __('Category Name') }}</th>
                            <th class="px-6 py-3.5">{{ __('Slug') }}</th>
                            <th class="px-6 py-3.5">{{ __('Total Products') }}</th>
                            <th class="px-6 py-3.5 text-right">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse ($categories as $category)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/50 transition">
                                <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">{{ $category->name }}</td>
                                <td class="px-6 py-4 text-xs font-mono text-slate-500 dark:text-slate-400">{{ $category->slug }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30 text-amber-700 dark:text-amber-400 text-xs font-semibold">{{ $category->products_count }} {{ __('products') }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <x-table-action-group :edit-url="route('admin.categories.edit', $category)"
                                                          :delete-url="$category->products_count === 0 ? route('admin.categories.destroy', $category) : null"
                                                          :delete-confirm="__('Delete category :name?', ['name' => $category->name])" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">{{ __('No categories found.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
