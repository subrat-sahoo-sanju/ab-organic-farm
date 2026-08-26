@props([
    'variant' => 'primary',   // primary|secondary|outline|ghost|danger|clay
    'size' => 'md',           // sm|md|lg
    'type' => 'button',
    'loading' => false,
])

@php
$base = 'inline-flex items-center justify-center gap-2 font-semibold rounded-xl transition-all duration-200 focus-visible:outline-2 focus-visible:outline-offset-2 disabled:opacity-50 disabled:pointer-events-none active:scale-[.97]';

$variants = [
    'primary'   => 'bg-forest-600 text-white hover:bg-forest-700 shadow-card',
    'secondary' => 'bg-mint-100 text-forest-700 hover:bg-mint-200',
    'outline'   => 'border border-forest-600/30 text-forest-700 hover:border-forest-600 hover:bg-forest-50',
    'ghost'     => 'text-charcoal-700 hover:bg-cream-200',
    'danger'    => 'bg-rose-600 text-white hover:bg-rose-700',
    'clay'      => 'bg-clay-500 text-white hover:bg-clay-600 shadow-card',
];

$sizes = [
    'sm' => 'h-9 px-3.5 text-sm',
    'md' => 'h-11 px-5 text-sm',
    'lg' => 'h-13 px-7 text-base',
];
@endphp

<button {{ $attributes->merge(['type' => $type]) }}
    class="{{ $base }} {{ $variants[$variant] }} {{ $sizes[$size] }}"
    @if($loading) disabled @endif
>
    @if($loading)
        <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
        </svg>
    @endif
    {{ $slot }}
</button>
