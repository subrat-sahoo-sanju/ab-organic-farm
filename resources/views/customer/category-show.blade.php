@extends('layouts.app', ['title' => ($category->seo_title ?: $category->name) . ' — AB Organic Farm'])

@section('meta')
@if($category->meta_description)
  <meta name="description" content="{{ $category->meta_description }}">
@endif
@endsection

@section('content')
{{-- ═══════════════════════════════════════════════════════════════
     HERO BANNER — full-width, admin-manageable per category
═══════════════════════════════════════════════════════════════ --}}
@php
    $bannerBg   = $category->banner_bg_color ?: '#00584b';
    $bannerImg  = $category->banner_image;
    $bannerH    = $category->banner_heading ?: $category->name;
    $bannerSub  = $category->banner_subheading;
    $bannerCta  = $category->banner_cta_text;
    $bannerUrl  = $category->banner_cta_url ?: '#';
    $brandLogo  = $category->brand_logo;
    $brandName  = $category->brand_name ?: 'AB Organic';
@endphp

<div class="relative w-full overflow-hidden" style="background:{{ $bannerBg }}">
  @if($bannerImg)
    <img src="{{ asset('storage/'.$bannerImg) }}" alt="{{ $bannerH }}" class="absolute inset-0 h-full w-full object-cover" loading="eager">
  @endif

  <div class="relative mx-auto flex max-w-7xl items-center px-4 py-10 sm:px-6 sm:py-14 lg:px-8 lg:py-16">
    <div class="max-w-xl">
      @if($brandLogo || $brandName)
        <div class="mb-3 flex items-center gap-2">
          @if($brandLogo)
            <img src="{{ asset('storage/'.$brandLogo) }}" alt="{{ $brandName }}" class="h-8 w-auto object-contain">
          @endif
          <span class="text-sm font-bold uppercase tracking-wider text-white/80">{{ $brandName }}</span>
        </div>
      @endif

      <h1 class="font-display text-3xl font-extrabold text-white sm:text-4xl lg:text-5xl">{{ $bannerH }}</h1>

      @if($bannerSub)
        <p class="mt-3 max-w-md text-base text-white/80 sm:text-lg">{{ $bannerSub }}</p>
      @endif

      @if($bannerCta && $bannerCta !== '#')
        <a href="{{ $bannerUrl }}" class="mt-5 inline-flex items-center gap-2 rounded-full bg-white px-6 py-3 text-sm font-bold shadow-lg transition hover:shadow-xl hover:scale-[1.02]" style="color:{{ $bannerBg }}">
          {{ $bannerCta }}
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </a>
      @endif
    </div>
  </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════
     CATEGORY ICON NAV — horizontal circular icons
═══════════════════════════════════════════════════════════════ --}}
@if(isset($rootCategories) && $rootCategories->count())
<div class="border-b border-sage-100 bg-white">
  <div class="mx-auto max-w-7xl overflow-x-auto px-4 py-4 sm:px-6 lg:px-8">
    <div class="flex items-center gap-4 sm:gap-6" style="min-width:max-content">
      @foreach($rootCategories as $cat)
        @php $active = $cat->id === $category->id || $cat->id === $category->parent_id; @endphp
        <a href="{{ route('shop.category', $cat->slug) }}" class="flex flex-col items-center gap-1.5 transition group" style="min-width:64px">
          <div class="grid h-14 w-14 place-items-center rounded-full border-2 transition {{ $active ? 'border-anv-600 bg-anv-50 shadow-sm' : 'border-sage-200 bg-leaf-50 group-hover:border-anv-400 group-hover:bg-anv-50/50' }}">
            @if($cat->image_path)
              <img src="{{ asset('storage/'.$cat->image_path) }}" alt="{{ $cat->name }}" class="h-9 w-9 rounded-full object-cover">
            @elseif($cat->icon)
              <span class="text-xl">{{ $cat->icon }}</span>
            @else
              <span class="text-lg opacity-40">🏷️</span>
            @endif
          </div>
          <span class="text-center text-[11px] font-semibold leading-tight {{ $active ? 'text-anv-700' : 'text-charcoal-600 group-hover:text-anv-700' }}" style="max-width:72px">{{ $cat->name }}</span>
        </a>
      @endforeach
    </div>
  </div>
</div>
@endif

{{-- ═══════════════════════════════════════════════════════════════
     ADMIN-CONFIGURED SECTIONS — rendered in order between
     the icon nav and the main product grid
═══════════════════════════════════════════════════════════════ --}}
@if(!empty($category->sections) && count($category->sections))
  @foreach($category->sections as $idx => $section)
    @php
        $secType = $section['type'] ?? '';
        $secVisible = $section['visible'] ?? true;
        $secData = $sectionData["section_{$idx}"] ?? collect();
    @endphp
    @if($secVisible && view()->exists("customer.category-sections.{$secType}"))
      @include("customer.category-sections.{$secType}", [
          'section' => $section,
          'sectionData' => $secData,
      ])
    @endif
  @endforeach
@endif

{{-- ═══════════════════════════════════════════════════════════════
     MAIN PRODUCT SECTION — breadcrumbs, filters, grid
═══════════════════════════════════════════════════════════════ --}}
<div class="bg-[#fafcfa]">
  <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

    {{-- Breadcrumbs --}}
    <nav class="mb-4 text-xs text-charcoal-600/50">
      <a href="{{ route('shop.index') }}" class="transition-colors hover:text-anv-700">Home</a>
      <span class="mx-1.5">/</span>
      <a href="{{ route('shop.categories') }}" class="transition-colors hover:text-anv-700">Shop</a>
      <span class="mx-1.5">/</span>
      <span class="font-medium text-charcoal-800">{{ $category->name }}</span>
    </nav>

    {{-- Subcategory pills --}}
    @if($subcategories->count())
      <div class="mb-6 flex flex-wrap gap-2">
        <a href="{{ route('shop.category', $category->slug) }}"
           class="rounded-full border px-4 py-1.5 text-xs font-bold transition {{ !request('sub') ? 'border-anv-600 bg-anv-600 text-white' : 'border-sage-200 bg-white text-charcoal-700 hover:border-anv-500 hover:text-anv-700' }}">
          All {{ $category->name }}
        </a>
        @foreach($subcategories as $sub)
          <a href="{{ route('shop.category', $category->slug) }}?sub={{ $sub->slug }}"
             class="rounded-full border px-4 py-1.5 text-xs font-bold transition {{ request('sub') === $sub->slug ? 'border-anv-600 bg-anv-600 text-white' : 'border-sage-200 bg-white text-charcoal-700 hover:border-anv-500 hover:text-anv-700' }}">
            {{ $sub->name }}
          </a>
        @endforeach
      </div>
    @endif

    {{-- Product count + Sort --}}
    <div class="mb-6 flex items-center justify-between">
      <p class="text-sm font-medium text-charcoal-600/60">
        <span class="font-bold text-charcoal-800">{{ $products->total() }}</span> product{{ $products->total() !== 1 ? 's' : '' }}
      </p>
      <form method="GET" class="flex gap-2">
        <select name="sort" onchange="this.form.submit()" class="rounded-full border border-sage-200 bg-white px-3 py-1.5 text-xs font-medium text-charcoal-700 transition focus:border-anv-500 focus:ring-1 focus:ring-anv-500/30 focus:outline-none">
          <option value="">Sort: Popular</option>
          <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low → High</option>
          <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High → Low</option>
          <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest First</option>
          <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Top Rated</option>
          <option value="discount" {{ request('sort') === 'discount' ? 'selected' : '' }}>Biggest Discount</option>
        </select>
      </form>
    </div>

    {{-- Product grid --}}
    @if($products->count())
      <div
          x-data="{
              nextUrl: @json($products->nextPageUrl()),
              hasMore: @json($products->hasMorePages()),
              loading: false,
              failed: false,
              loadMore() {
                  if (!this.nextUrl || this.loading) return
                  this.loading = true
                  this.failed = false
                  fetch(this.nextUrl, {
                      headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' },
                  })
                  .then(r => { if (!r.ok) throw new Error('Request failed'); return r.json() })
                  .then(d => {
                      this.$refs.grid.insertAdjacentHTML('beforeend', d.html)
                      this.nextUrl = d.nextPageUrl
                      this.hasMore = d.hasMorePages
                  })
                  .catch(() => { this.failed = true })
                  .finally(() => { this.loading = false })
              },
          }"
      >
        <div x-ref="grid" class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 sm:gap-4">
          @foreach($products as $product)
            <x-product-card :product="$product" />
          @endforeach
        </div>

        <div x-cloak x-show="hasMore" x-transition class="mt-10 flex flex-col items-center gap-3">
          <button
              type="button"
              @click="loadMore"
              :disabled="loading"
              class="inline-flex items-center gap-2 rounded-full border-2 border-anv-600 bg-white px-8 py-3 text-sm font-bold text-anv-700 shadow-sm transition hover:bg-anv-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-anv-500/40 disabled:cursor-not-allowed disabled:opacity-60"
          >
            <svg x-show="loading" x-cloak class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4z"></path>
            </svg>
            <span x-text="loading ? 'Loading…' : 'Show More Products'"></span>
          </button>
          <p x-cloak x-show="failed" class="text-sm text-red-500">Something went wrong. Please try again.</p>
        </div>
      </div>
    @else
      <div class="rounded-2xl border border-sage-100 bg-white py-16 text-center">
        <div class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-leaf-100 text-3xl">🌿</div>
        <p class="mt-4 text-sm font-medium text-charcoal-600/50">No products found in this category yet.</p>
        <a href="{{ route('shop.index') }}" class="mt-4 inline-block rounded-full bg-anv-600 px-5 py-2 text-xs font-bold text-white transition hover:bg-anv-700">Browse All Products →</a>
      </div>
    @endif
  </div>
</div>
@endsection
