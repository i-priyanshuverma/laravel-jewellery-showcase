@props([
    'vendor',
    'size' => 'md',
])

@php
$sizeClasses = [
    'sm' => 'w-8 h-8 text-xs rounded-lg',
    'md' => 'w-10 h-10 text-sm rounded-xl',
    'lg' => 'w-14 h-14 text-lg rounded-2xl',
][$size] ?? 'w-10 h-10 text-sm rounded-xl';

$name = $vendor?->vendorProfile?->business_name ?? $vendor?->name ?? 'V';
$initials = strtoupper(substr($name, 0, 2));
@endphp

<div {{ $attributes->merge(['class' => "$sizeClasses bg-gold-gradient text-slate-950 flex items-center justify-center font-extrabold shadow-sm flex-shrink-0 select-none"]) }}
     title="{{ $name }}">
    {{ $initials }}
</div>
