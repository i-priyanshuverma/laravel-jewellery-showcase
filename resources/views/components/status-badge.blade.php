@props(['status'])

@php
$statusValue = is_object($status) && isset($status->value) ? $status->value : (string) $status;
$statusLabel = is_object($status) && method_exists($status, 'label') ? $status->label() : $statusValue;

$classes = match(strtolower($statusValue)) {
    'approved', 'active', 'completed', 'success' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800',
    'pending', 'processing', 'draft' => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800',
    'suspended', 'inactive', 'failed', 'expired', 'released' => 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800',
    default => 'bg-slate-50 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700',
};
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border capitalize {$classes}"]) }}>
    {{ __($statusLabel) }}
</span>
