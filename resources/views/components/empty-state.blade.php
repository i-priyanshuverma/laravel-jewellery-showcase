@props([
    'icon' => '💍',
    'title' => 'No records found',
    'description' => null,
    'actionUrl' => null,
    'actionLabel' => null,
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-12 text-center space-y-3 shadow-sm transition-colors']) }}>
    <div class="text-4xl select-none mb-1">{{ $icon }}</div>
    <h4 class="font-extrabold text-base text-slate-900 dark:text-slate-100">{{ $title }}</h4>
    @if ($description)
        <p class="text-xs text-slate-500 dark:text-slate-400 max-w-md mx-auto leading-relaxed">{{ $description }}</p>
    @endif
    @if ($actionUrl && $actionLabel)
        <div class="pt-2">
            <a href="{{ $actionUrl }}" class="inline-block px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-sm transition">
                {{ $actionLabel }}
            </a>
        </div>
    @endif
    {{ $slot }}
</div>
