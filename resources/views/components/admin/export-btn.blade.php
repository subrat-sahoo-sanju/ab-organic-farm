@props([
    'url',
    'label' => 'Export CSV',
])

<a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
   class="inline-flex items-center gap-1.5 rounded-lg border border-sage/20 bg-white px-3.5 py-2 text-xs font-semibold text-charcoal/60 transition hover:border-forest/30 hover:text-forest dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:border-green-700 dark:hover:text-green-400">
    <x-lucide-download class="h-3.5 w-3.5" />
    {{ $label }}
</a>
