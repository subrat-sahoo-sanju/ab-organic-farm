@props([
    'icon' => 'inbox',
    'title' => 'No data found',
    'description' => null,
])

<div class="flex flex-col items-center justify-center py-16 text-center">
    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-charcoal/[0.04] dark:bg-gray-700/40">
        <x-dynamic-component :component="'lucide-'.$icon" class="h-8 w-8 text-charcoal/25 dark:text-gray-500" />
    </div>
    <p class="text-sm font-semibold text-charcoal/60 dark:text-gray-300">{{ $title }}</p>
    @if($description)
        <p class="mt-1 max-w-xs text-xs text-charcoal/40 dark:text-gray-500">{{ $description }}</p>
    @endif
    @isset($slot)
        <div class="mt-4">{{ $slot }}</div>
    @endisset
</div>
