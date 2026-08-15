<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-xl text-slate-900 dark:text-slate-100 leading-tight">{{ __('Colours') }}</h2>
            <a href="{{ route('admin.colours.create') }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-sm transition">{{ __('+ Add Colour') }}</a>
        </div>
    </x-slot>
    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-900 dark:text-slate-100 transition-colors duration-200">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash-message />
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
                <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-950/60 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[11px] border-b border-slate-100 dark:border-slate-800">
                        <tr><th class="px-6 py-3.5">{{ __('Colour') }}</th><th class="px-6 py-3.5">{{ __('Sort') }}</th><th class="px-6 py-3.5">{{ __('Status') }}</th><th class="px-6 py-3.5 text-right">{{ __('Actions') }}</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse ($colours as $colour)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">
                                <td class="px-6 py-4 font-bold text-slate-900 dark:text-slate-100">{{ $colour->name }}</td>
                                <td class="px-6 py-4 text-xs text-slate-500">{{ $colour->sort_order }}</td>
                                <td class="px-6 py-4"><span class="px-2.5 py-0.5 text-[10px] font-bold uppercase rounded-full {{ $colour->status === 'active' ? 'bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800' : 'bg-rose-50 dark:bg-rose-950 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-800' }}">{{ __($colour->status) }}</span></td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('admin.colours.edit', $colour) }}" class="text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline">{{ __('Edit') }}</a>
                                    <form method="POST" action="{{ route('admin.colours.destroy', $colour) }}" class="inline-block" onsubmit="event.preventDefault(); window.confirmAction({ title: '{{ __('Delete Colour') }}', message: '{{ __('Are you sure you want to delete colour') }} &quot;{{ addslashes($colour->name) }}&quot;?', confirmText: '{{ __('Delete Colour') }}', icon: 'danger', form: this });">@csrf @method('DELETE')<button type="submit" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">{{ __('Delete') }}</button></form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">{{ __('No colours found.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
