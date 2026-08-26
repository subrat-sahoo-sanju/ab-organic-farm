@props([
    'status',
    'type' => null,
])

@php
$map = [
    'pending'            => ['bg' => 'bg-amber-50 dark:bg-amber-500/10',   'text' => 'text-amber-700 dark:text-amber-400',  'dot' => 'bg-amber-500'],
    'confirmed'          => ['bg' => 'bg-blue-50 dark:bg-blue-500/10',     'text' => 'text-blue-700 dark:text-blue-400',    'dot' => 'bg-blue-500'],
    'processing'         => ['bg' => 'bg-indigo-50 dark:bg-indigo-500/10', 'text' => 'text-indigo-700 dark:text-indigo-400','dot' => 'bg-indigo-500'],
    'out_for_delivery'   => ['bg' => 'bg-purple-50 dark:bg-purple-500/10', 'text' => 'text-purple-700 dark:text-purple-400','dot' => 'bg-purple-500'],
    'delivered'          => ['bg' => 'bg-green-50 dark:bg-green-500/10',   'text' => 'text-green-700 dark:text-green-400',  'dot' => 'bg-green-500'],
    'cancelled'          => ['bg' => 'bg-red-50 dark:bg-red-500/10',       'text' => 'text-red-700 dark:text-red-400',      'dot' => 'bg-red-500'],
    'failed'             => ['bg' => 'bg-red-50 dark:bg-red-500/10',       'text' => 'text-red-700 dark:text-red-400',      'dot' => 'bg-red-500'],
    'active'             => ['bg' => 'bg-green-50 dark:bg-green-500/10',   'text' => 'text-green-700 dark:text-green-400',  'dot' => 'bg-green-500'],
    'inactive'           => ['bg' => 'bg-gray-100 dark:bg-gray-600/30',    'text' => 'text-gray-600 dark:text-gray-400',    'dot' => 'bg-gray-400'],
    'approved'           => ['bg' => 'bg-green-50 dark:bg-green-500/10',   'text' => 'text-green-700 dark:text-green-400',  'dot' => 'bg-green-500'],
    'rejected'           => ['bg' => 'bg-red-50 dark:bg-red-500/10',       'text' => 'text-red-700 dark:text-red-400',      'dot' => 'bg-red-500'],
];

$statusStr = is_string($status) ? $status : ($status->value ?? (string) $status);
$statusKey = $type ?? strtolower(str_replace(' ', '_', $statusStr));
$style = $map[$statusKey] ?? ['bg' => 'bg-gray-100 dark:bg-gray-600/30', 'text' => 'text-gray-600 dark:text-gray-400', 'dot' => 'bg-gray-400'];
$label = is_string($status) ? ucfirst(str_replace('_', ' ', $status)) : ($status->label ?? ucfirst(str_replace('_', ' ', $statusStr)));
@endphp

<span class="adm-badge {{ $style['bg'] }} {{ $style['text'] }}">
    <span class="h-1.5 w-1.5 rounded-full {{ $style['dot'] }}"></span>
    {{ $label }}
</span>
