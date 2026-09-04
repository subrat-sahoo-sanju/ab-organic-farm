@props(['type' => 'info'])

@php
$palette = [
    'success' => 'bg-mint-50 border-forest-300 text-forest-700',
    'error'   => 'bg-rose-50 border-rose-200 text-rose-700',
    'warning' => 'bg-harvest-400/10 border-harvest-400/40 text-amber-800',
    'info'    => 'bg-sky-50 border-sky-200 text-sky-800',
];
$icons = ['success' => 'check-circle-2', 'error' => 'alert-circle', 'warning' => 'triangle-alert', 'info' => 'info'];
@endphp

<div {{ $attributes->merge(['class' => 'flex items-start gap-3 rounded-xl border p-4 text-sm '.$palette[$type]]) }} role="status">
    @svg('lucide-'.$icons[$type], 'h-5 w-5 shrink-0 mt-0.5')
    <div class="[&>p]:m-0">{{ $slot }}</div>
</div>
