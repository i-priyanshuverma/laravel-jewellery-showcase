<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-xl text-slate-900 dark:text-slate-100 leading-tight">
                Import Status: {{ $import->filename }}
            </h2>
            <a href="{{ route('vendor.imports.index') }}" class="text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200">&larr; Back to Imports List</a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-900 dark:text-slate-100 transition-colors duration-200" x-data="csvImportStatus({{ $import->id }}, '{{ $import->status }}', {{ $import->progressPercentage() }})" x-init="initPolling()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash-message />

            <!-- Real-time Progress Card -->
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-4 transition-colors">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span>CSV Processing Dashboard</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase border"
                                  :class="{
                                      'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800': status === 'processing' || status === 'pending',
                                      'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800': status === 'completed',
                                      'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800': status === 'failed'
                                  }"
                                  x-text="status">
                                {{ $import->status }}
                            </span>
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Uploaded on {{ $import->created_at->format('M d, Y \a\t H:i') }}</p>
                    </div>

                    <div class="text-right">
                        <span class="text-2xl font-extrabold text-slate-900 dark:text-white" x-text="percentage + '%'">{{ $import->progressPercentage() }}%</span>
                        <p class="text-xs text-slate-400">Completed</p>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="w-full bg-slate-100 dark:bg-slate-800 h-3 rounded-full overflow-hidden">
                    <div class="bg-amber-500 h-full transition-all duration-500 rounded-full" :style="'width: ' + percentage + '%'"></div>
                </div>

                <!-- Live Counters -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-2">
                    <div class="p-3.5 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800">
                        <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Total Rows</span>
                        <div class="text-lg font-bold text-slate-900 dark:text-white" x-text="totalRows">{{ $import->total_rows ?? '...' }}</div>
                    </div>
                    <div class="p-3.5 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800">
                        <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Processed</span>
                        <div class="text-lg font-bold text-slate-900 dark:text-white" x-text="processedRows">{{ $import->processed_rows }}</div>
                    </div>
                    <div class="p-3.5 bg-emerald-50 dark:bg-emerald-950/40 rounded-xl border border-emerald-200 dark:border-emerald-800">
                        <span class="text-xs text-emerald-700 dark:text-emerald-400 font-bold">Successful Records</span>
                        <div class="text-lg font-bold text-emerald-700 dark:text-emerald-400" x-text="successfulRows">{{ $import->successful_rows }}</div>
                    </div>
                    <div class="p-3.5 bg-rose-50 dark:bg-rose-950/40 rounded-xl border border-rose-200 dark:border-rose-800">
                        <span class="text-xs text-rose-700 dark:text-rose-400 font-bold">Failed Records</span>
                        <div class="text-lg font-bold text-rose-700 dark:text-rose-400" x-text="failedRows">{{ $import->failed_rows }}</div>
                    </div>
                </div>
            </div>

            <!-- Detailed Row Results -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Failed Records with Error Reasons -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-4 transition-colors">
                    <h3 class="text-base font-bold text-rose-600 dark:text-rose-400 flex items-center justify-between">
                        <span>Failed Records ({{ $import->failed_rows }})</span>
                        <span class="text-xs text-slate-400 font-normal">Validation / Parsing Errors</span>
                    </h3>

                    <div class="space-y-3">
                        @forelse ($failedRows as $failed)
                            <div class="p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800/50 text-xs">
                                <div class="flex items-center justify-between font-bold text-rose-900 dark:text-rose-200">
                                    <span>Row #{{ $failed->row_number }}</span>
                                    <span class="font-mono text-[11px]">{{ $failed->data['sku'] ?? 'No SKU' }}</span>
                                </div>
                                <div class="mt-1 font-semibold text-slate-700 dark:text-slate-300">{{ $failed->data['product_name'] ?? 'Unnamed Row' }}</div>
                                @if ($failed->errors)
                                    <div class="mt-2 p-2 rounded-lg bg-white/80 dark:bg-slate-900/80 font-mono text-[11px] text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-900">
                                        @foreach ($failed->errors as $field => $errs)
                                            <div><strong>{{ $field }}:</strong> {{ implode(', ', (array)$errs) }}</div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-slate-500 dark:text-slate-400 py-4">No failed records.</p>
                        @endforelse
                    </div>

                    @if ($failedRows->hasPages())
                        <div class="pt-2">
                            {{ $failedRows->links() }}
                        </div>
                    @endif
                </div>

                <!-- Successful Records -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-4 transition-colors">
                    <h3 class="text-base font-bold text-emerald-600 dark:text-emerald-400 flex items-center justify-between">
                        <span>Successfully Imported ({{ $import->successful_rows }})</span>
                        <span class="text-xs text-slate-400 font-normal">Inserted / Updated</span>
                    </h3>

                    <div class="space-y-3">
                        @forelse ($successRows as $success)
                            <div class="p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/50 text-xs flex items-center justify-between">
                                <div>
                                    <span class="font-bold text-slate-900 dark:text-white">#{{ $success->row_number }} &bull; {{ $success->data['product_name'] ?? '' }}</span>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 font-mono mt-0.5">SKU: {{ $success->data['sku'] ?? '' }} &bull; Price: ₹{{ $success->data['price'] ?? '' }} &bull; Stock: {{ $success->data['stock'] ?? '' }}</div>
                                </div>
                                <span class="text-emerald-600 dark:text-emerald-400 font-bold">✓ Success</span>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500 dark:text-slate-400 py-4">No successful records yet.</p>
                        @endforelse
                    </div>

                    @if ($successRows->hasPages())
                        <div class="pt-2">
                            {{ $successRows->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function csvImportStatus(importId, initialStatus, initialPercentage) {
            return {
                importId: importId,
                status: initialStatus,
                percentage: initialPercentage,
                totalRows: {{ $import->total_rows ?? 0 }},
                processedRows: {{ $import->processed_rows }},
                successfulRows: {{ $import->successful_rows }},
                failedRows: {{ $import->failed_rows }},
                pollTimer: null,

                initPolling() {
                    if (this.status === 'processing' || this.status === 'pending') {
                        this.pollTimer = setInterval(() => {
                            this.fetchProgress();
                        }, 2000);
                    }
                },

                async fetchProgress() {
                    try {
                        const res = await fetch(`/vendor/imports/${this.importId}/status`);
                        const data = await res.json();
                        this.status = data.status;
                        this.percentage = data.percentage;
                        this.totalRows = data.total_rows;
                        this.processedRows = data.processed_rows;
                        this.successfulRows = data.successful_rows;
                        this.failedRows = data.failed_rows;

                        if (this.status === 'completed' || this.status === 'failed') {
                            clearInterval(this.pollTimer);
                            window.location.reload();
                        }
                    } catch (e) {
                        console.error('Polling error', e);
                    }
                }
            };
        }
    </script>
</x-app-layout>
