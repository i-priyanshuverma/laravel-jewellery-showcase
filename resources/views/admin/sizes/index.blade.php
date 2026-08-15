<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-xl text-slate-900 dark:text-slate-100 leading-tight">Jewellery Sizes</h2>
            <a href="{{ route('admin.sizes.create') }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-sm transition">+ Add Size</a>
        </div>
    </x-slot>
    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-900 dark:text-slate-100 transition-colors duration-200">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash-message />
            <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-4 transition-colors">
                <form method="GET" action="{{ route('admin.sizes.index') }}" class="flex items-center gap-4 w-full">
                    <select name="category_id" onchange="this.form.submit()" class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl pl-3.5 pr-9 py-2 text-xs text-slate-900 dark:text-slate-200 focus:ring-amber-500 focus:border-amber-500">
                        <option value="">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <select name="status" onchange="this.form.submit()" class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl pl-3.5 pr-9 py-2 text-xs text-slate-900 dark:text-slate-200 focus:ring-amber-500 focus:border-amber-500">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @if (request()->hasAny(['category_id', 'status']))
                        <a href="{{ route('admin.sizes.index') }}" class="text-xs font-semibold text-amber-600 dark:text-amber-400 hover:underline">Reset</a>
                    @endif
                </form>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
                <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-950/60 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[11px] border-b border-slate-100 dark:border-slate-800">
                        <tr><th class="px-6 py-3.5">Sort</th><th class="px-6 py-3.5">Category</th><th class="px-6 py-3.5">Display Name</th><th class="px-6 py-3.5">Value</th><th class="px-6 py-3.5">Status</th><th class="px-6 py-3.5 text-right">Actions</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse ($sizes as $size)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">
                                <td class="px-6 py-4 font-mono text-xs text-slate-400">{{ $size->sort_order }}</td>
                                <td class="px-6 py-4"><span class="px-2.5 py-1 bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30 text-[10px] font-bold uppercase text-amber-700 dark:text-amber-400 rounded-full">{{ $size->category?->name ?? 'Global' }}</span></td>
                                <td class="px-6 py-4 font-bold text-slate-900 dark:text-slate-100">{{ $size->name }}</td>
                                <td class="px-6 py-4 font-mono text-xs text-slate-500 dark:text-slate-400">{{ $size->value }}</td>
                                <td class="px-6 py-4"><span class="px-2.5 py-0.5 text-[10px] font-bold uppercase rounded-full {{ $size->status === 'active' ? 'bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800' : 'bg-rose-50 dark:bg-rose-950 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-800' }}">{{ $size->status }}</span></td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('admin.sizes.edit', $size) }}" class="text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline">Edit</a>
                                    <form method="POST" action="{{ route('admin.sizes.destroy', $size) }}" class="inline-block" onsubmit="event.preventDefault(); window.confirmAction({ title: 'Delete Size Option', message: 'Are you sure you want to delete size option &quot;{{ addslashes($size->name) }}&quot;?', confirmText: 'Delete Size', icon: 'danger', form: this });">@csrf @method('DELETE')<button type="submit" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Delete</button></form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">No sizes found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4 border-t border-slate-100 dark:border-slate-800">{{ $sizes->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
