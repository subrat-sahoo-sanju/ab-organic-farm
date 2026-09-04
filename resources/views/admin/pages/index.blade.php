@extends('layouts.admin', ['title' => 'Pages & Policies'])

@section('content')
<div class="space-y-5">
  <div class="flex flex-wrap items-center justify-between gap-4">
    <div>
      <h2 class="adm-page-title">Pages &amp; Policies <span class="adm-page-count">{{ $pages->total() }}</span></h2>
      <p class="adm-kicker">Manage every policy &amp; information page. Changes go live on the store instantly.</p>
    </div>
    <a href="{{ route('admin.pages.create') }}" class="adm-btn-primary inline-flex items-center gap-2">
      <x-lucide-plus class="h-4 w-4" /> New Page
    </a>
  </div>

  {{-- Tabs --}}
  <div class="flex flex-wrap items-center gap-1 rounded-xl border border-gray-200 bg-white p-1 dark:border-gray-700 dark:bg-gray-800">
    @foreach([
        ['key'=>'all','label'=>'All','count'=>$allCount,'icon'=>'layers'],
        ['key'=>'active','label'=>'Live','count'=>$activeCount,'icon'=>'check-circle'],
        ['key'=>'inactive','label'=>'Hidden','count'=>$inactiveCount,'icon'=>'eye-off'],
    ] as $t)
      <a href="{{ route('admin.pages.index', ['tab'=>$t['key'], 'q'=>$search]) }}"
         class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition
                {{ $tab === $t['key'] ? 'bg-forest text-white shadow-sm' : 'text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700' }}">
        @svg('lucide-'.$t['icon'], 'h-4 w-4')
        {{ $t['label'] }}
        <span class="rounded-full px-1.5 text-[11px] {{ $tab===$t['key'] ? 'bg-white/20' : 'bg-gray-100 dark:bg-gray-700' }}">{{ $t['count'] }}</span>
      </a>
    @endforeach
    <div class="ml-auto hidden sm:block">
      <form method="GET" action="{{ route('admin.pages.index') }}" class="flex items-center gap-1">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <input type="text" name="q" value="{{ $search }}" placeholder="Search pages…"
               class="adm-input py-1.5 text-sm" style="width:200px">
        <button type="submit" class="adm-btn-ghost px-2 py-1.5"><x-lucide-search class="h-4 w-4" /></button>
      </form>
    </div>
  </div>

  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr>
          <th>Page</th>
          <th>Slug</th>
          <th>Sections</th>
          <th>FAQs</th>
          <th>Order</th>
          <th>Status</th>
          <th class="text-right">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($pages as $page)
          <tr>
            <td>
              <div class="flex items-center gap-3">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-forest/10 text-forest">
                  @svg('lucide-'.$page->icon, 'h-4.5 w-4.5')
                </span>
                <div>
                  <div class="font-semibold text-charcoal dark:text-gray-100">{{ $page->title }}</div>
                  <div class="text-xs text-gray-400">{{ $page->hero ?: '—' }}</div>
                </div>
              </div>
            </td>
            <td class="adm-text-secondary font-mono text-xs">{{ $page->slug }}</td>
            <td><span class="adm-badge bg-anv-50 text-anv-700">{{ count($page->sections ?? []) }} sections</span></td>
            <td><span class="adm-badge bg-gold-400/15 text-gold-600">{{ count($page->faqs ?? []) }} FAQs</span></td>
            <td class="adm-text-secondary text-xs">{{ $page->sort_order }}</td>
            <td>
              <form action="{{ route('admin.pages.toggle', $page) }}" method="POST">
                @csrf
                <button type="submit" class="adm-chip {{ $page->is_active ? 'adm-chip-live' : 'adm-chip-draft' }}">
                  {{ $page->is_active ? 'Live' : 'Hidden' }}
                </button>
              </form>
            </td>
            <td class="text-right">
              <div class="inline-flex items-center gap-1">
                <a href="/{{ $page->slug }}" target="_blank" class="adm-action-link" title="View page">
                  <x-lucide-eye class="h-4 w-4" />
                </a>
                <a href="{{ route('admin.pages.edit', $page) }}" class="adm-action-link" title="Edit">
                  <x-lucide-pencil class="h-4 w-4" />
                </a>
                <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" onsubmit="return confirm('Delete this page? This cannot be undone.')">
                  @csrf @method('DELETE')
                  <button type="submit" class="adm-action-link text-red-500" title="Delete"><x-lucide-trash-2 class="h-4 w-4" /></button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" class="adm-empty">No pages found. Click "New Page" to create your first policy page.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div>
    {{ $pages->links() }}
  </div>

  {{-- Quick pointer to the owner-facing link list --}}
  <div class="rounded-xl border border-gold-300/30 bg-gold-400/5 p-4 text-sm text-gray-600 dark:text-gray-300">
    <strong class="text-gold-700 dark:text-gold-400">Tip:</strong> Pages you mark <em>Live</em> appear instantly under the footer's Policies column and at <code class="text-xs">/{{ optional($pages->first())->slug ?? 'your-page-slug' }}</code>.
  </div>
</div>
@endsection