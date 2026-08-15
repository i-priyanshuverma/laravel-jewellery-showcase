@props([
    'editUrl' => null,
    'deleteUrl' => null,
    'deleteConfirm' => 'Are you sure you want to delete this item?',
])

<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-3 text-xs font-semibold']) }}>
    @if ($editUrl)
        <a href="{{ $editUrl }}" class="font-bold text-amber-600 dark:text-amber-400 hover:underline transition">
            {{ __('Edit') }}
        </a>
    @endif

    @if ($deleteUrl)
        <form method="POST" action="{{ $deleteUrl }}" class="inline" onsubmit="event.preventDefault(); window.confirmAction({ title: '{{ __('Delete Confirmation') }}', message: '{{ addslashes($deleteConfirm) }}', confirmText: '{{ __('Delete Item') }}', icon: 'danger', form: this });">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-rose-600 dark:text-rose-400 hover:underline transition">
                {{ __('Delete') }}
            </button>
        </form>
    @endif

    {{ $slot }}
</div>
