<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-xl text-slate-900 dark:text-slate-100 leading-tight">{{ __('Metals & Purities') }}</h2>
            <a href="{{ route('admin.metals.create') }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-sm transition">{{ __('+ Add Metal') }}</a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-900 dark:text-slate-100 transition-colors duration-200">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash-message />

            @foreach ($metals as $metal)
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-xl overflow-hidden transition-colors">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <span class="text-lg font-bold text-slate-900 dark:text-slate-100">{{ $metal->name }}</span>
                            <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase rounded-full {{ $metal->status === 'active' ? 'bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800' : 'bg-rose-50 dark:bg-rose-950 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-800' }}">{{ __($metal->status) }}</span>
                            <span class="text-[11px] text-slate-400">{{ __('Sort:') }} {{ $metal->sort_order }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.metals.edit', $metal) }}" class="text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline">{{ __('Edit') }}</a>
                            <form method="POST" action="{{ route('admin.metals.destroy', $metal) }}" class="inline" onsubmit="event.preventDefault(); window.confirmAction({ title: '{{ __('Delete Metal Specification') }}', message: '{{ __('Are you sure you want to delete') }} {{ addslashes($metal->name) }} {{ __('and all of its associated purity levels? This action cannot be undone.') }}', confirmText: '{{ __('Delete Metal') }}', icon: 'danger', form: this });">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">{{ __('Delete') }}</button>
                            </form>
                        </div>
                    </div>
                    @if ($metal->purities->isNotEmpty())
                        <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                            <thead class="bg-slate-50 dark:bg-slate-950/60 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                                <tr>
                                    <th class="px-6 py-2.5">{{ __('Purity Name') }}</th>
                                    <th class="px-6 py-2.5">{{ __('Value') }}</th>
                                    <th class="px-6 py-2.5">{{ __('Sort') }}</th>
                                    <th class="px-6 py-2.5">{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                                @foreach ($metal->purities->sortBy('sort_order') as $purity)
                                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/30 transition">
                                        <td class="px-6 py-2.5 font-medium text-slate-900 dark:text-white">{{ $purity->name }}</td>
                                        <td class="px-6 py-2.5 font-mono text-xs text-amber-600 dark:text-amber-400 font-semibold">{{ $purity->value }}</td>
                                        <td class="px-6 py-2.5 text-xs text-slate-500">{{ $purity->sort_order }}</td>
                                        <td class="px-6 py-2.5">
                                            <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-full {{ $purity->status === 'active' ? 'bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800' : 'bg-rose-50 dark:bg-rose-950 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-800' }}">{{ __($purity->status) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="px-6 py-4 text-sm text-slate-500 italic">{{ __('No purities defined. Edit this metal to add purities.') }}</p>
                    @endif
                </div>
            @endforeach

            @if ($metals->isEmpty())
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-12 text-center text-slate-500 shadow-sm">{{ __('No metals found.') }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
