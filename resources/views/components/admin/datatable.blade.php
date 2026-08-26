@props([
    'id' => 'datatable',
    'columns' => [],
    'rows' => collect(),
    'perPage' => 10,
    'searchable' => true,
    'exportable' => true,
    'exportUrl' => null,
    'title' => null,
    'subtitle' => null,
    'emptyIcon' => 'table',
    'emptyTitle' => 'No data found',
    'emptyDescription' => 'There are no records to display.',
    'actionsColumn' => true,
    'filters' => [],
    'rowActionsHtml' => [],
])

@php
    $rowsCollection = $rows instanceof \Illuminate\Support\Collection ? $rows->values() : collect($rows);
    $columnsJson = collect($columns)->map(fn($c) => [
        'key'         => $c['key'] ?? '',
        'label'       => $c['label'] ?? $c['key'] ?? '',
        'sortable'    => $c['sortable'] ?? false,
        'searchable'  => $c['searchable'] ?? false,
        'class'       => $c['class'] ?? '',
        'hideOnMobile'=> $c['hideOnMobile'] ?? false,
    ])->values()->toArray();
    $rowsJson = $rowsCollection->map(function($row) {
        if ($row instanceof \Illuminate\Database\Eloquent\Model) return $row->toArray();
        return (array) $row;
    })->values()->toArray();
    $filtersJson = collect($filters)->map(fn($f) => [
        'key'     => $f['key'] ?? '',
        'label'   => $f['label'] ?? '',
        'options' => $f['options'] ?? [],
    ])->values()->toArray();

    $actionsHtmlMap = is_array($rowActionsHtml) ? $rowActionsHtml : $rowActionsHtml->toArray();
@endphp

<div
    x-data="datatable_{{ $id }}()"
    x-init="init()"
    class="space-y-4"
>
    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            @if($title)
                <h3 class="text-lg font-bold text-charcoal dark:text-white">{{ $title }}</h3>
            @endif
            @if($subtitle)
                <p class="text-sm text-charcoal/50 dark:text-gray-400">{{ $subtitle }}</p>
            @endif
            <p class="text-sm text-charcoal/50 dark:text-gray-400">
                <span x-text="'Showing ' + showingFrom + '–' + showingTo + ' of ' + totalRows"></span>
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @foreach($filters as $filter)
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button
                        @click="open = !open"
                        type="button"
                        class="inline-flex h-9 items-center gap-1.5 rounded-lg border border-sage/20 bg-white px-3 text-xs font-semibold text-charcoal/60 transition hover:border-forest/30 hover:text-forest dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:border-green-700 dark:hover:text-green-400"
                    >
                        <x-lucide-filter class="h-3.5 w-3.5" />
                        <span x-text="filters['{{ $filter['key'] }}'] || '{{ $filter['label'] }}'"></span>
                        <x-lucide-chevron-down class="h-3 w-3" />
                    </button>
                    <div
                        x-show="open"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 z-30 mt-1 w-48 origin-top-right rounded-xl border border-sage/20 bg-white py-1 shadow-lg dark:border-gray-700 dark:bg-gray-800"
                    >
                        <button
                            @click="filters['{{ $filter['key'] }}'] = ''; open = false; page = 1"
                            type="button"
                            class="w-full px-3 py-2 text-left text-xs font-medium transition hover:bg-forest-50 dark:hover:bg-gray-700"
                            :class="filters['{{ $filter['key'] }}'] === '' ? 'text-forest-600 dark:text-green-400' : 'text-charcoal/60 dark:text-gray-400'"
                        >All</button>
                        @foreach($filter['options'] as $opt)
                            <button
                                @click="filters['{{ $filter['key'] }}'] = '{{ $opt }}'; open = false; page = 1"
                                type="button"
                                class="w-full px-3 py-2 text-left text-xs font-medium transition hover:bg-forest-50 dark:hover:bg-gray-700"
                                :class="filters['{{ $filter['key'] }}'] === '{{ $opt }}' ? 'text-forest-600 dark:text-green-400' : 'text-charcoal/60 dark:text-gray-400'"
                            >{{ $opt }}</button>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <template x-if="hasActiveFilters">
                <button
                    @click="resetFilters()"
                    type="button"
                    class="inline-flex h-9 items-center gap-1.5 rounded-lg border border-rose-300 bg-rose-50 px-3 text-xs font-semibold text-rose-600 transition hover:bg-rose-100 dark:border-rose-700 dark:bg-rose-500/10 dark:text-rose-400 dark:hover:bg-rose-500/20"
                >
                    <x-lucide-x class="h-3.5 w-3.5" />
                    Reset
                </button>
            </template>

            @if($searchable)
                <div class="relative">
                    <x-lucide-search class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-charcoal/30 dark:text-gray-500" />
                    <input
                        x-model.debounce.300ms="search"
                        type="text"
                        placeholder="Search..."
                        class="h-9 w-full rounded-full border border-sage/20 bg-white py-2 pl-9 pr-4 text-xs text-charcoal placeholder-charcoal/30 transition focus:border-forest/40 focus:outline-none focus:ring-2 focus:ring-forest/10 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500 dark:focus:border-green-700 dark:focus:ring-green-700/20 sm:w-64"
                    />
                </div>
            @endif

            @if($exportable)
                <button
                    @click="exportCSV()"
                    type="button"
                    class="inline-flex h-9 items-center gap-1.5 rounded-lg border border-sage/20 bg-white px-3.5 text-xs font-semibold text-charcoal/60 transition hover:border-forest/30 hover:text-forest dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:border-green-700 dark:hover:text-green-400"
                >
                    <x-lucide-download class="h-3.5 w-3.5" />
                    Export CSV
                </button>
            @endif
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border border-sage/20 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    @if(isset($head))
                        {{ $head }}
                    @else
                        <tr class="border-b border-sage/20 bg-charcoal/[0.02] dark:border-gray-700 dark:bg-gray-700/30">
                            <th class="w-10 px-4 py-3">
                                <input
                                    type="checkbox"
                                    :checked="selectedRows.length === pagedRows.length && pagedRows.length > 0"
                                    @change="toggleSelectAll($event)"
                                    class="h-4 w-4 rounded border-sage-300 text-forest focus:ring-forest/20 dark:border-gray-600 dark:bg-gray-700 dark:focus:ring-green-700/20"
                                />
                            </th>
                            @foreach($columns as $col)
                                <th
                                    class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-charcoal/50 dark:text-gray-400 {{ $col['class'] ?? '' }} {{ ($col['hideOnMobile'] ?? false) ? 'hidden lg:table-cell' : '' }} {{ ($col['sortable'] ?? false) ? 'cursor-pointer select-none hover:text-charcoal/70 dark:hover:text-gray-300' : '' }}"
                                    @if($col['sortable'] ?? false) @click="toggleSort('{{ $col['key'] }}')" @endif
                                >
                                    <div class="flex items-center gap-1">
                                        <span>{{ $col['label'] ?? $col['key'] ?? '' }}</span>
                                        @if($col['sortable'] ?? false)
                                            <span class="inline-flex flex-col -space-y-1">
                                                <svg
                                                    class="h-3 w-3 transition"
                                                    :class="sortKey === '{{ $col['key'] }}' && sortDir === 'asc' ? 'text-forest dark:text-green-400' : 'text-charcoal/20 dark:text-gray-600'"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                                                >
                                                    <path d="M18 15l-6-6-6 6"/>
                                                </svg>
                                                <svg
                                                    class="h-3 w-3 transition -mt-1.5"
                                                    :class="sortKey === '{{ $col['key'] }}' && sortDir === 'desc' ? 'text-forest dark:text-green-400' : 'text-charcoal/20 dark:text-gray-600'"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                                                >
                                                    <path d="M6 9l6 6 6-6"/>
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                </th>
                            @endforeach
                            @if($actionsColumn)
                                <th class="w-20 px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-charcoal/50 dark:text-gray-400">Actions</th>
                            @endif
                        </tr>
                    @endif
                </thead>
                <tbody class="divide-y divide-sage/10 dark:divide-gray-700/50">
                    <template x-if="pagedRows.length === 0">
                        <tr>
                            <td :colspan="{{ count($columns) + 2 }}" class="px-4 py-12">
                                @if(isset($empty))
                                    {{ $empty }}
                                @else
                                    <div class="flex flex-col items-center justify-center py-8 text-center">
                                        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-charcoal/[0.04] dark:bg-gray-700/40">
                                            <x-dynamic-component :component="'lucide-'.$emptyIcon" class="h-8 w-8 text-charcoal/25 dark:text-gray-500" />
                                        </div>
                                        <p class="text-sm font-semibold text-charcoal/60 dark:text-gray-300">{{ $emptyTitle }}</p>
                                        <p class="mt-1 max-w-xs text-xs text-charcoal/40 dark:text-gray-500">{{ $emptyDescription }}</p>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    </template>
                    <template x-for="(row, rowIndex) in pagedRows" :key="rowIndex">
                        <tr
                            class="transition-colors"
                            :class="[
                                selectedRows.includes(row._originalIndex) ? 'bg-forest-50/50 dark:bg-green-500/5' : '',
                                rowIndex % 2 === 1 && !selectedRows.includes(row._originalIndex) ? 'bg-charcoal/[0.015] dark:bg-gray-800/50' : '',
                                'hover:bg-forest-50/30 dark:hover:bg-gray-700/30',
                            ].join(' ')"
                        >
                            <td class="w-10 px-4 py-3">
                                <input
                                    type="checkbox"
                                    :checked="selectedRows.includes(row._originalIndex)"
                                    @change="toggleRow(row._originalIndex)"
                                    class="h-4 w-4 rounded border-sage-300 text-forest focus:ring-forest/20 dark:border-gray-600 dark:bg-gray-700 dark:focus:ring-green-700/20"
                                />
                            </td>
                            @foreach($columns as $col)
                                <td
                                    class="whitespace-nowrap px-4 py-3 text-charcoal/70 dark:text-gray-300 {{ $col['class'] ?? '' }} {{ ($col['hideOnMobile'] ?? false) ? 'hidden lg:table-cell' : '' }}"
                                    x-text="row['{{ $col['key'] }}'] ?? '—'"
                                ></td>
                            @endforeach
                            @if($actionsColumn)
                                <td
                                    class="whitespace-nowrap px-4 py-3 text-right"
                                    x-html="actionsHtml[row._originalIndex] || ''"
                                ></td>
                            @endif
                        </tr>
                    </template>
                    {{ $slot }}
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex flex-col items-center justify-between gap-3 border-t border-sage/20 bg-charcoal/[0.02] px-4 py-3 dark:border-gray-700 dark:bg-gray-700/20 sm:flex-row">
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-1.5 text-xs text-charcoal/50 dark:text-gray-400">
                    <span>Rows:</span>
                    <select
                        x-model.number="perPage"
                        @change="page = 1"
                        class="h-7 rounded-lg border border-sage/20 bg-white px-2 text-xs font-medium text-charcoal focus:border-forest/40 focus:outline-none focus:ring-2 focus:ring-forest/10 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:border-green-700"
                    >
                        <template x-for="opt in perPageOptions" :key="opt">
                            <option :value="opt" x-text="opt"></option>
                        </template>
                    </select>
                </div>
                <template x-if="selectedRows.length > 0">
                    <span class="text-xs font-medium text-forest-600 dark:text-green-400" x-text="selectedRows.length + ' selected'"></span>
                </template>
            </div>

            <div class="flex items-center gap-1">
                <button
                    @click="prevPage()"
                    :disabled="page <= 1"
                    type="button"
                    class="inline-flex h-8 items-center justify-center rounded-lg px-3 text-xs font-medium text-charcoal/60 transition hover:bg-sage/10 hover:text-charcoal disabled:cursor-not-allowed disabled:opacity-30 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                >Prev</button>

                <template x-for="p in visiblePages" :key="p">
                    <button
                        @click="goToPage(p)"
                        type="button"
                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-xs font-semibold transition"
                        :class="p === page
                            ? 'bg-forest text-white shadow-sm dark:bg-green-600'
                            : 'text-charcoal/60 hover:bg-sage/10 dark:text-gray-400 dark:hover:bg-gray-700'"
                        x-text="p"
                    ></button>
                </template>

                <button
                    @click="nextPage()"
                    :disabled="page >= totalPages"
                    type="button"
                    class="inline-flex h-8 items-center justify-center rounded-lg px-3 text-xs font-medium text-charcoal/60 transition hover:bg-sage/10 hover:text-charcoal disabled:cursor-not-allowed disabled:opacity-30 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                >Next</button>
            </div>
        </div>
    </div>
</div>

<script>
function datatable_{{ $id }}() {
    return {
        search: '',
        sortKey: '',
        sortDir: 'asc',
        page: 1,
        perPage: {{ $perPage }},
        perPageOptions: [10, 25, 50, 100],
        filters: {},
        selectedRows: [],
        allRows: @js($rowsJson),
        columns: @js($columnsJson),
        filterDefs: @js($filtersJson),
        exportUrl: @js($exportUrl),
        actionsHtml: @js($actionsHtmlMap),

        init() {
            this.filterDefs.forEach(f => { this.filters[f.key] = ''; });
            this.$watch('search', () => { this.page = 1; });
            this.$watch('perPage', () => { this.page = 1; });
        },

        get hasActiveFilters() {
            return Object.values(this.filters).some(v => v !== '');
        },

        get filteredRows() {
            let rows = [...this.allRows];

            if (this.search.trim()) {
                const q = this.search.toLowerCase().trim();
                const keys = this.columns.filter(c => c.searchable).map(c => c.key);
                if (keys.length === 0) {
                    keys.push(...this.columns.map(c => c.key));
                }
                rows = rows.filter(row =>
                    keys.some(k => {
                        const val = row[k];
                        return val !== null && val !== undefined && String(val).toLowerCase().includes(q);
                    })
                );
            }

            this.filterDefs.forEach(f => {
                if (this.filters[f.key] && this.filters[f.key] !== '') {
                    rows = rows.filter(row => String(row[f.key]) === String(this.filters[f.key]));
                }
            });

            if (this.sortKey) {
                rows.sort((a, b) => {
                    let va = a[this.sortKey] ?? '';
                    let vb = b[this.sortKey] ?? '';
                    if (typeof va === 'string') va = va.toLowerCase();
                    if (typeof vb === 'string') vb = vb.toLowerCase();
                    if (va < vb) return this.sortDir === 'asc' ? -1 : 1;
                    if (va > vb) return this.sortDir === 'asc' ? 1 : -1;
                    return 0;
                });
            }

            return rows.map((r, i) => {
                const origIdx = this.allRows.indexOf(r);
                return { ...r, _originalIndex: origIdx };
            });
        },

        get pagedRows() {
            const start = (this.page - 1) * this.perPage;
            return this.filteredRows.slice(start, start + this.perPage);
        },

        get totalPages() {
            return Math.max(1, Math.ceil(this.filteredRows.length / this.perPage));
        },

        get showingFrom() {
            if (this.filteredRows.length === 0) return 0;
            return (this.page - 1) * this.perPage + 1;
        },

        get showingTo() {
            return Math.min(this.page * this.perPage, this.filteredRows.length);
        },

        get totalRows() {
            return this.filteredRows.length;
        },

        get visiblePages() {
            const total = this.totalPages;
            const current = this.page;
            const pages = [];
            let start = Math.max(1, current - 2);
            let end = Math.min(total, start + 4);
            start = Math.max(1, end - 4);
            for (let i = start; i <= end; i++) pages.push(i);
            return pages;
        },

        toggleSort(key) {
            if (this.sortKey === key) {
                this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortKey = key;
                this.sortDir = 'asc';
            }
        },

        nextPage() {
            if (this.page < this.totalPages) this.page++;
        },

        prevPage() {
            if (this.page > 1) this.page--;
        },

        goToPage(p) {
            if (p >= 1 && p <= this.totalPages) this.page = p;
        },

        toggleSelectAll(event) {
            if (event.target.checked) {
                this.selectedRows = this.pagedRows.map(r => r._originalIndex);
            } else {
                this.selectedRows = [];
            }
        },

        toggleRow(index) {
            const idx = this.selectedRows.indexOf(index);
            if (idx === -1) {
                this.selectedRows.push(index);
            } else {
                this.selectedRows.splice(idx, 1);
            }
        },

        resetFilters() {
            this.search = '';
            this.sortKey = '';
            this.sortDir = 'asc';
            this.page = 1;
            this.selectedRows = [];
            this.filterDefs.forEach(f => { this.filters[f.key] = ''; });
        },

        exportCSV() {
            if (this.exportUrl) {
                window.open(this.exportUrl, '_blank');
                return;
            }

            const cols = this.columns.filter(c => c.key);
            const header = cols.map(c => '"' + (c.label || c.key).replace(/"/g, '""') + '"').join(',');

            const rows = this.filteredRows.map(row =>
                cols.map(c => {
                    let val = row[c.key];
                    if (val === null || val === undefined) val = '';
                    val = String(val).replace(/\r?\n/g, ' ');
                    if (val.includes(',') || val.includes('"') || val.includes('\n')) {
                        val = '"' + val.replace(/"/g, '""') + '"';
                    }
                    return val;
                }).join(',')
            );

            const csv = [header, ...rows].join('\r\n');
            const blob = new Blob(['\uFEFF' + csv], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = (document.title || 'export').replace(/[^a-z0-9]/gi, '_').toLowerCase() + '.csv';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
        },
    };
}
</script>
