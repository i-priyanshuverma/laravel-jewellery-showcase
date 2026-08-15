<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-xl text-slate-900 dark:text-slate-100 leading-tight">
                {{ __('CSV Product Bulk Imports') }}
            </h2>
            <a href="{{ route('vendor.imports.create') }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-sm transition">+ Upload New CSV</a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-900 dark:text-slate-100 transition-colors duration-200">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash-message />

            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
                <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-950/60 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3.5">File Name</th>
                            <th class="px-6 py-3.5">Total Rows</th>
                            <th class="px-6 py-3.5">Processed</th>
                            <th class="px-6 py-3.5">Success / Fail</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5">Date Uploaded</th>
                            <th class="px-6 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse ($imports as $import)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/50 transition">
                                <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">{{ $import->filename }}</td>
                                <td class="px-6 py-4 font-semibold">{{ $import->total_rows ?? 'Counting...' }}</td>
                                <td class="px-6 py-4 font-semibold">{{ $import->processed_rows }} / {{ $import->total_rows ?? '?' }}</td>
                                <td class="px-6 py-4 text-xs font-semibold">
                                    <span class="text-emerald-600 dark:text-emerald-400">✓ {{ $import->successful_rows }}</span> &bull;
                                    <span class="text-rose-600 dark:text-rose-400">✗ {{ $import->failed_rows }}</span>
                                </td>
                                <td class="px-6 py-4"><x-status-badge :status="$import->status" /></td>
                                <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400">{{ $import->created_at->format('M d, Y H:i') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('vendor.imports.show', $import) }}" class="text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline">View Progress & Results &rarr;</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">No CSV imports uploaded yet. <a href="{{ route('vendor.imports.create') }}" class="text-amber-600 dark:text-amber-400 font-semibold underline">Upload your first CSV file</a></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($imports->hasPages())
                    <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                        {{ $imports->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
