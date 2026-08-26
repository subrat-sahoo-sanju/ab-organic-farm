@props([
    'columns' => [],
    'collection' => null,
])

<div class="overflow-hidden rounded-xl border border-sage/20 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b border-sage/20 bg-charcoal/[0.02] dark:border-gray-700 dark:bg-gray-700/30">
                    @foreach($columns as $col)
                        <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-charcoal/50 dark:text-gray-400">
                            {{ $col['label'] ?? $col['key'] ?? '' }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-sage/10 dark:divide-gray-700/50">
                @forelse($collection as $index => $item)
                    <tr class="transition hover:bg-charcoal/[0.02] dark:hover:bg-gray-700/30 {{ $index % 2 === 1 ? 'bg-charcoal/[0.015] dark:bg-gray-800/50' : '' }}">
                        {{ $slot }}
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columns) }}" class="px-4 py-12 text-center text-sm text-charcoal/40 dark:text-gray-500">
                            {{ $empty ?? 'No records found.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($collection instanceof \Illuminate\Pagination\LengthAwarePaginator && $collection->hasPages())
        <div class="flex items-center justify-between border-t border-sage/20 px-4 py-3 dark:border-gray-700">
            <p class="text-xs text-charcoal/40 dark:text-gray-500">
                Showing {{ $collection->firstItem() ?? 0 }}–{{ $collection->lastItem() ?? 0 }} of {{ $collection->total() }}
            </p>
            <div class="flex items-center gap-1">
                @if($collection->onFirstPage())
                    <span class="inline-flex h-8 cursor-not-allowed items-center justify-center rounded-lg px-3 text-xs font-medium text-charcoal/30 dark:text-gray-600">Prev</span>
                @else
                    <a href="{{ $collection->previousPageUrl() }}" class="inline-flex h-8 items-center justify-center rounded-lg px-3 text-xs font-medium text-charcoal/60 transition hover:bg-sage/10 hover:text-charcoal dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Prev</a>
                @endif

                @foreach($collection->getUrlRange(max(1, $collection->currentPage() - 2), min($collection->lastPage(), $collection->currentPage() + 2)) as $page => $url)
                    <a href="{{ $url }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-xs font-semibold transition {{ $page === $collection->currentPage() ? 'bg-forest text-white shadow-sm' : 'text-charcoal/60 hover:bg-sage/10 dark:text-gray-400 dark:hover:bg-gray-700' }}">{{ $page }}</a>
                @endforeach

                @if($collection->hasMorePages())
                    <a href="{{ $collection->nextPageUrl() }}" class="inline-flex h-8 items-center justify-center rounded-lg px-3 text-xs font-medium text-charcoal/60 transition hover:bg-sage/10 hover:text-charcoal dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a>
                @else
                    <span class="inline-flex h-8 cursor-not-allowed items-center justify-center rounded-lg px-3 text-xs font-medium text-charcoal/30 dark:text-gray-600">Next</span>
                @endif
            </div>
        </div>
    @endif
</div>
