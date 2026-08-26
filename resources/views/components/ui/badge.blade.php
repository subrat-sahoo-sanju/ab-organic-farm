@props(['color' => 'neutral'])

@php
$palette = [
    'neutral' => 'bg-cream-200 text-charcoal-700',
    'green'   => 'bg-mint-100 text-forest-700 ring-1 ring-inset ring-forest-600/10',
    'amber'   => 'bg-harvest-400/15 text-amber-800 ring-1 ring-inset ring-amber-600/20',
    'red'     => 'bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-600/15',
    'blue'    => 'bg-sky-50 text-sky-700 ring-1 ring-inset ring-sky-600/15',
    'clay'    => 'bg-clay-500 text-white',
];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-semibold whitespace-nowrap '.$palette[$color]]) }}>
    {{ $slot }}
</span>
