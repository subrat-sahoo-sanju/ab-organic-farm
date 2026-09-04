@extends('layouts.app', ['title' => 'All Products'])

@section('content')
<style>
  .allp-grid{
    display:grid;
    grid-template-columns:repeat(2, minmax(0,1fr));
    gap:.9rem;
  }
  @media(min-width:640px){.allp-grid{grid-template-columns:repeat(3,minmax(0,1fr));gap:1rem}}
  @media(min-width:1024px){.allp-grid{grid-template-columns:repeat(4,minmax(0,1fr));gap:1.15rem}}
  @media(min-width:1280px){.allp-grid{grid-template-columns:repeat(5,minmax(0,1fr));gap:1.25rem}}
</style>

<div class="bg-[#fafcfa]">
  <div class="mx-auto max-w-[1440px] px-4 py-6 sm:px-6 lg:px-8" x-data="allProducts()">

    {{-- Header --}}
    <div class="flex flex-col items-start gap-1">
      <nav class="mb-1 text-xs text-charcoal-600/50">
        <a href="{{ route('shop.index') }}" class="hover:text-anv-700">Home</a>
        <span class="mx-1.5">/</span>
        <span class="font-medium text-charcoal-800">All Products</span>
      </nav>
      <h1 class="font-display text-2xl font-extrabold text-charcoal-900 sm:text-3xl">All Products</h1>
      <p class="text-sm text-charcoal-600/60">Every certified organic item we grow &amp; deliver — fresh from farm to home.</p>
    </div>

    {{-- Category quick filter pills --}}
    @if($rootCategories->count())
      <div class="mt-5 flex gap-2 overflow-x-auto pb-1" style="scrollbar-width:none">
        <a href="{{ route('shop.all') }}" class="shrink-0 rounded-full border px-4 py-1.5 text-xs font-bold transition {{ !request('cat') ? 'border-anv-600 bg-anv-600 text-white' : 'border-sage-200 bg-white text-charcoal-700 hover:border-anv-500 hover:text-anv-700' }}">All</a>
        @foreach($rootCategories as $cat)
          <a href="{{ route('shop.all', array_filter(['cat' => $cat->slug])) }}"
             class="shrink-0 flex items-center gap-1.5 rounded-full border px-4 py-1.5 text-xs font-bold transition {{ request('cat') === $cat->slug ? 'border-anv-600 bg-anv-600 text-white' : 'border-sage-200 bg-white text-charcoal-700 hover:border-anv-500 hover:text-anv-700' }}">
            @if($cat->image_path)
              <img src="{{ asset('storage/'.$cat->image_path) }}" alt="" class="h-4 w-4 rounded-full object-cover">
            @elseif($cat->icon)
              <span class="text-sm leading-none">{{ $cat->icon }}</span>
            @endif
            {{ $cat->name }}
          </a>
        @endforeach
      </div>
    @endif

    @if(request('cat'))
      <p class="mt-3 text-xs font-semibold text-anv-600">
        Filtered: {{ $products->first()?->category?->name ?? 'Selected category' }}
      </p>
    @endif

    {{-- Count + scroll hint --}}
    <div class="mt-5 flex items-center justify-between">
      <p class="text-xs font-medium text-charcoal-600/60">
        <span class="font-bold text-charcoal-800" x-text="loadedCount"></span> of
        <span class="font-bold text-charcoal-800">{{ $products->total() }}</span> products
      </p>
      <span class="hidden items-center gap-1 text-[11px] font-semibold text-anv-600 sm:flex">
        <x-lucide-mouse-pointer class="h-3.5 w-3.5"/> Scroll for more
      </span>
    </div>

    {{-- Product grid --}}
    <div id="allp-grid" class="allp-grid mt-4">
      @forelse($products->items() as $product)
        <x-product-card :product="$product" />
      @empty
        <div class="col-span-full py-16 text-center text-sm text-charcoal-600/50">No products found in this category yet.</div>
      @endforelse
    </div>

    {{-- Infinite scroll sentinel + states --}}
    <div id="allp-sentinel" class="mt-8 flex flex-col items-center justify-center gap-3 py-6">
      <template x-if="loading">
        <div class="flex items-center gap-2 text-sm font-semibold text-anv-600">
          <svg class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
          </svg>
          Loading more products…
        </div>
      </template>
      <template x-if="!loading && !hasMore && page > 1">
        <div class="text-center">
          <div class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-leaf-50 text-anv-600"><x-lucide-check class="h-6 w-6"/></div>
          <p class="mt-2 text-sm font-semibold text-charcoal-700">You've seen all {{ $products->total() }} products!</p>
          <p class="text-xs text-charcoal-600/50">Fresh picks restock every week. Check back soon.</p>
        </div>
      </template>
      <template x-if="!loading && hasMore">
        <button @click="loadNext()" class="rounded-full border-2 border-anv-600 px-6 py-2.5 text-sm font-bold text-anv-600 transition hover:bg-anv-600 hover:text-white">Load More Products</button>
      </template>
    </div>
  </div>
</div>

<script>
function allProducts() {
  return {
    nextPage: @json($products->nextPageUrl()),
    hasMore: {{ $products->hasMorePages() ? 'true' : 'false' }},
    page: 1,
    loading: false,
    loadedCount: {{ $products->count() }},
    init() {
      const io = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting && this.hasMore && !this.loading) this.loadNext();
      }, { rootMargin: '400px' });
      const sentinel = document.getElementById('allp-sentinel');
      if (sentinel) io.observe(sentinel);
    },
    async loadNext() {
      if (!this.hasMore || this.loading) return;
      this.loading = true;
      try {
        const target = this.nextPage;
        const res = await fetch(target, { headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' } });
        if (!res.ok) throw new Error('bad response');
        const data = await res.json();
        if (data.html) {
          const grid = document.getElementById('allp-grid');
          grid.insertAdjacentHTML('beforeend', data.html);
          this.page += 1;
          this.loadedCount += (data.html.match(/<article/g) || []).length;
        }
        this.nextPage = data.nextPageUrl;
        this.hasMore = data.hasMorePages;
      } catch (e) {
        // allow manual retry via button
      } finally {
        this.loading = false;
      }
    },
  };
}
</script>
@endsection
