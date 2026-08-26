@props([
    'action' => '',
    'method' => 'GET',
])

<form action="{{ $action }}" method="{{ $method }}" class="rounded-xl border border-sage/20 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
    @if(strtoupper($method) === 'POST')
        @csrf
    @endif

    <div class="flex flex-wrap items-end gap-3">
        {{ $slot }}

        <div class="flex items-center gap-2">
            <button type="submit" class="inline-flex h-9 items-center gap-1.5 rounded-lg bg-forest px-4 text-xs font-semibold text-white shadow-sm transition hover:bg-forest-700 active:scale-[.97] dark:bg-green-600 dark:hover:bg-green-700">
                <x-lucide-search class="h-3.5 w-3.5" />
                Search
            </button>

            @if(request()->hasAny(array_keys(request()->except('_token'))))
                <a href="{{ request()->url() }}" class="inline-flex h-9 items-center gap-1.5 rounded-lg border border-sage/20 px-4 text-xs font-semibold text-charcoal/60 transition hover:bg-sage/10 hover:text-charcoal dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                    <x-lucide-x class="h-3.5 w-3.5" />
                    Clear
                </a>
            @endif
        </div>
    </div>
</form>
