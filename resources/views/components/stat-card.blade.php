@props([
    'title',
    'value',
    'icon' => null,
    'color' => 'amber',
    'linkUrl' => null,
    'linkLabel' => 'View All →',
])

@php
$colorClasses = [
    'amber' => 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400',
    'emerald' => 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400',
    'purple' => 'bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400',
    'rose' => 'bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400',
    'sky' => 'bg-sky-50 dark:bg-sky-500/10 text-sky-600 dark:text-sky-400',
][$color] ?? 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400';
@endphp

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-3 transition-colors']) }}>
    <div class="flex items-center justify-between">
        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __($title) }}</span>
        @if ($icon)
            <span class="p-2 rounded-xl {{ $colorClasses }} text-base">{{ $icon }}</span>
        @endif
    </div>
    <div class="flex items-baseline justify-between">
        <span class="text-3xl font-extrabold text-slate-900 dark:text-slate-100 font-mono">{{ $value }}</span>
        @if ($linkUrl)
            <a href="{{ $linkUrl }}" class="text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline">{{ __($linkLabel) }}</a>
        @endif
    </div>
    @if ($slot->isNotEmpty())
        <div class="text-[11px] text-slate-500 dark:text-slate-400 pt-2 border-t border-slate-100 dark:border-slate-800">
            {{ $slot }}
        </div>
    @endif
</div>
