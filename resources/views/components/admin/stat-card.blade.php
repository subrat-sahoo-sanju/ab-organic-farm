@props([
    'title',
    'value',
    'icon' => null,
    'color' => 'green',
    'subtitle' => null,
    'trend' => null,
])

@php
$colors = [
    'green'  => ['accent' => 'bg-green-500',  'iconBg' => 'bg-green-50 dark:bg-green-500/10',  'iconText' => 'text-green-600 dark:text-green-400',  'trend' => 'text-green-600 dark:text-green-400'],
    'blue'   => ['accent' => 'bg-blue-500',   'iconBg' => 'bg-blue-50 dark:bg-blue-500/10',    'iconText' => 'text-blue-600 dark:text-blue-400',    'trend' => 'text-blue-600 dark:text-blue-400'],
    'amber'  => ['accent' => 'bg-amber-500',  'iconBg' => 'bg-amber-50 dark:bg-amber-500/10',   'iconText' => 'text-amber-600 dark:text-amber-400',   'trend' => 'text-amber-600 dark:text-amber-400'],
    'red'    => ['accent' => 'bg-red-500',    'iconBg' => 'bg-red-50 dark:bg-red-500/10',      'iconText' => 'text-red-600 dark:text-red-400',      'trend' => 'text-red-600 dark:text-red-400'],
    'purple' => ['accent' => 'bg-purple-500', 'iconBg' => 'bg-purple-50 dark:bg-purple-500/10', 'iconText' => 'text-purple-600 dark:text-purple-400', 'trend' => 'text-purple-600 dark:text-purple-400'],
];
$c = $colors[$color] ?? $colors['green'];
@endphp

<div class="relative overflow-hidden rounded-xl border border-sage/20 bg-white p-5 shadow-sm transition dark:border-gray-700 dark:bg-gray-800">
    <div class="absolute inset-y-0 left-0 w-1 {{ $c['accent'] }}"></div>
    <div class="flex items-start justify-between">
        <div class="min-w-0 flex-1">
            <p class="text-xs font-semibold uppercase tracking-wider text-charcoal/40 dark:text-gray-400">{{ $title }}</p>
            <p class="mt-2 text-3xl font-bold text-charcoal dark:text-white">{{ $value }}</p>
            @if($subtitle)
                <p class="mt-1 text-xs {{ $c['trend'] }}">{{ $subtitle }}</p>
            @endif
            @if($trend)
                <p class="mt-1 text-xs {{ $c['trend'] }}">{{ $trend }}</p>
            @endif
        </div>
        @if($icon)
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg {{ $c['iconBg'] }}">
                <x-dynamic-component :component="'lucide-'.$icon" class="h-5 w-5 {{ $c['iconText'] }}" />
            </div>
        @endif
    </div>
</div>
