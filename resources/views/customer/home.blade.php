@extends('layouts.app', ['title' => 'AB Organic Farm — Organic Food Delivered Fresh to Your Door'])

@section('content')

{{-- ========== SECTION 1: FULL-WIDTH HERO BANNER ========== --}}
@if($heroBanners->count())
<section x-data="{ active: 0, total: {{ $heroBanners->count() }} }" x-init="setInterval(() => { active = (active + 1) % total }, 5000)" class="relative w-full overflow-hidden bg-[#0C831F]">
  <div class="relative h-[300px] w-full overflow-hidden">
      @foreach($heroBanners as $index => $banner)
      <div
        x-show="active === {{ $index }}"
        x-transition:enter="transition duration-700 ease-in-out"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition duration-700 ease-in-out"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0"
      >
        @if(!empty($banner->show_text) && $banner->show_text)
          {{-- UNIFORM FIXED-HEIGHT + TEXT OVERLAY --}}
          @if(!empty($banner->mobile_image))
            <img
              src="{{ asset('storage/'.$banner->mobile_image) }}"
              alt="{{ $banner->title }}"
              class="absolute inset-0 h-full w-full object-cover sm:hidden"
              loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
            />
          @endif
          <img
            src="{{ asset('storage/'.$banner->desktop_image) }}"
            alt="{{ $banner->title }}"
            class="absolute inset-0 h-full w-full object-cover {{ !empty($banner->mobile_image) ? 'hidden sm:block' : 'block' }}"
            loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
          />
          <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/30 to-transparent"></div>
          <div class="absolute inset-0 flex items-center">
            <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
              <div class="max-w-lg">
                @if($banner->subtitle)
                  <span class="mb-3 inline-block rounded-full bg-[#74C9A1]/20 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-[#74C9A1] backdrop-blur-sm">{{ $banner->subtitle }}</span>
                @endif
                <h2 class="font-display text-3xl font-extrabold text-white sm:text-4xl lg:text-5xl leading-tight">{{ $banner->title }}</h2>
                @if($banner->button_text && $banner->button_url)
                  <a href="{{ $banner->button_url }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-[#74C9A1] px-7 py-3.5 text-sm font-bold text-[#0C831F] shadow-lg transition duration-300 hover:bg-white hover:shadow-xl">
                    {{ $banner->button_text }}
                    <x-lucide-arrow-right class="h-4 w-4" />
                  </a>
                @endif
              </div>
            </div>
          </div>
        @else
          {{-- UNIFORM FIXED-HEIGHT image only --}}
          @if($banner->button_url)
            <a href="{{ $banner->button_url }}" class="absolute inset-0 block" title="{{ $banner->title }}">
          @endif
            @if(!empty($banner->mobile_image))
              <img
                src="{{ asset('storage/'.$banner->mobile_image) }}"
                alt="{{ $banner->title }}"
                class="absolute inset-0 h-full w-full object-cover sm:hidden"
                loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
              />
            @endif
            <img
              src="{{ asset('storage/'.$banner->desktop_image) }}"
              alt="{{ $banner->title }}"
              class="absolute inset-0 h-full w-full object-cover {{ !empty($banner->mobile_image) ? 'hidden sm:block' : 'block' }}"
              loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
            />
          @if($banner->button_url)
            </a>
          @endif
        @endif
      </div>
      @endforeach

    {{-- Dots --}}
    @if($heroBanners->count() > 1)
      <div class="absolute bottom-6 left-1/2 z-10 flex -translate-x-1/2 gap-2">
        @foreach($heroBanners as $index => $banner)
          <button @click="active = {{ $index }}" :class="active === {{ $index }} ? 'w-8 bg-[#74C9A1]' : 'w-2 bg-white/60'" class="h-2 rounded-full transition-all duration-300 shadow"></button>
        @endforeach
      </div>
    @endif
  </div>

  {{-- Delivery Badge Strip --}}
  <div class="bg-[#0C831F] py-2">
    <div class="mx-auto flex max-w-7xl items-center justify-center gap-6 px-4 text-xs font-medium text-white/90 sm:gap-8 sm:text-sm">
      <span class="flex items-center gap-1.5">
        <span class="relative flex h-2 w-2"><span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#74C9A1] opacity-75"></span><span class="relative inline-flex h-2 w-2 rounded-full bg-[#74C9A1]"></span></span>
        10-min delivery
      </span>
      <span class="flex items-center gap-1.5"><span class="inline-block h-1.5 w-1.5 rounded-full bg-[#74C9A1]"></span>100% Organic</span>
      <span class="flex items-center gap-1.5"><span class="inline-block h-1.5 w-1.5 rounded-full bg-[#74C9A1]"></span>{{ setting('home.delivery_charge_text', 'Free delivery ₹499+') }}</span>
    </div>
  </div>
</section>
@else
{{-- Fallback Hero --}}
<section class="relative w-full overflow-hidden bg-gradient-to-br from-[#0C831F] via-[#1a7a3a] to-[#2d9a4e]">
  <div class="absolute inset-0 opacity-10">
    <div class="absolute -top-20 -right-20 h-80 w-80 rounded-full bg-white/20 blur-3xl"></div>
    <div class="absolute -bottom-32 -left-20 h-96 w-96 rounded-full bg-white/10 blur-3xl"></div>
    <svg class="absolute bottom-0 left-0 w-full opacity-5" viewBox="0 0 1440 320"><path fill="white" d="M0,224L48,213.3C96,203,192,181,288,186.7C384,192,480,224,576,218.7C672,213,768,171,864,165.3C960,160,1056,192,1152,197.3C1248,203,1344,181,1392,170.7L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>
  </div>
  <div class="relative mx-auto max-w-7xl px-4 py-10 sm:px-6 sm:py-14 lg:px-8">
    <div class="text-center">
      <div class="mb-5 inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 text-sm font-medium text-white backdrop-blur-sm">
        <x-lucide-zap class="h-4 w-4 text-yellow-300" />
        <span>Lightning-fast organic delivery</span>
      </div>
      <h1 class="font-display text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
        Groceries delivered in
        <span class="relative">
          <span class="relative z-10 text-[#74C9A1]">10 minutes</span>
          <span class="absolute bottom-1 left-0 z-0 h-3 w-full bg-[#74C9A1]/30"></span>
        </span>
      </h1>
      <p class="mx-auto mt-4 max-w-xl text-lg text-white/80">
        100% organic, farm-fresh groceries at your doorstep. No chemicals, no compromise.
      </p>

      {{-- Search Bar --}}
      <div class="mx-auto mt-8 max-w-2xl">
        <div class="flex items-center overflow-hidden rounded-2xl bg-white shadow-2xl shadow-black/10">
          <div class="flex items-center gap-2 pl-5 text-charcoal/40">
            <x-lucide-search class="h-5 w-5" />
          </div>
          <input
            type="text"
            placeholder="Search for organic groceries, spices, grains..."
            class="flex-1 bg-transparent px-3 py-4 text-base text-charcoal outline-none placeholder:text-charcoal/40"
          />
          <button class="mr-2 rounded-xl bg-[#0C831F] px-6 py-3 text-sm font-bold text-white transition duration-300 hover:bg-[#096818]">
            Search
          </button>
        </div>
        <div class="mt-4 flex flex-wrap items-center justify-center gap-2 text-sm text-white/60">
          <span>Popular:</span>
          @foreach(['Cold-Pressed Oil', 'Millets', 'Turmeric', 'Jaggery'] as $tag)
            <span class="cursor-pointer rounded-full border border-white/20 px-3 py-1 text-xs text-white/80 transition duration-300 hover:border-white/50 hover:text-white">{{ $tag }}</span>
          @endforeach
        </div>
      </div>

      <div class="mt-8 flex flex-wrap items-center justify-center gap-6 text-sm font-medium text-white/70">
        <div class="flex items-center gap-2">
          <x-lucide-clock class="h-4 w-4 text-[#74C9A1]" />
          <span>10-min delivery</span>
        </div>
        <div class="flex items-center gap-2">
          <x-lucide-shield-check class="h-4 w-4 text-[#74C9A1]" />
          <span>Certified organic</span>
        </div>
        <div class="flex items-center gap-2">
          <x-lucide-badge-percent class="h-4 w-4 text-[#74C9A1]" />
          <span>Best prices</span>
        </div>
      </div>
    </div>
  </div>
</section>
@endif

{{-- ========== SECTION 2: CATEGORY PILLS (Horizontal Scroll) ========== --}}
@if($categories->count())
<section class="border-b border-sage/10 bg-white py-5">
  <div class="w-full px-4 sm:px-6 lg:px-8">
    <div class="scrollbar-none flex items-center gap-3 overflow-x-auto pb-1" style="-webkit-overflow-scrolling: touch;">
      @foreach($categories as $category)
        <a
          href="{{ route('shop.category', $category->slug) }}"
          class="flex flex-shrink-0 items-center gap-2.5 rounded-full border border-sage/30 bg-[#FDFBF7]/80 px-4 py-2.5 text-sm font-medium text-charcoal transition duration-300 hover:border-[#0C831F] hover:bg-[#0C831F]/5 hover:text-[#0C831F]"
        >
          @if($category->image_path)
            <img
              src="{{ asset('storage/'.$category->image_path) }}"
              alt="{{ $category->name }}"
              class="h-8 w-8 rounded-full object-cover"
              loading="lazy"
            />
          @else
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#0C831F]/10 text-base leading-none">{{ $category->icon ?? '🌱' }}</span>
          @endif
          <span class="whitespace-nowrap">{{ $category->name }}</span>
        </a>
      @endforeach
      <a
        href="{{ route('shop.categories') }}"
        class="flex flex-shrink-0 items-center gap-1 rounded-full border border-dashed border-[#0C831F]/40 px-4 py-2.5 text-sm font-semibold text-[#0C831F] transition duration-300 hover:bg-[#0C831F]/5"
      >
        View All
        <x-lucide-arrow-right class="h-3.5 w-3.5" />
      </a>
    </div>
  </div>
</section>
@endif

{{-- ========== SECTION 3: BRAND SHOWCASE (Full Width Tabs) ========== --}}
@if($brands->count())
<section class="w-full bg-gradient-to-b from-[#FDFBF7] to-[#f5f9f0] py-10">
  <div class="w-full px-4 sm:px-6 lg:px-8">
    <div class="text-center">
      <h2 class="font-display text-xl font-bold text-charcoal sm:text-2xl">{{ setting('home.brand_title', 'Shop by Brand') }}</h2>
      <p class="mt-1 text-sm text-charcoal/50">{{ setting('home.brand_subtitle', 'Explore our trusted organic brands') }}</p>
    </div>

    <div x-data="{ activeBrand: {{ $brands->first()?->id ?? 0 }} }">
      {{-- Brand Tabs --}}
      <div class="scrollbar-none mt-6 flex items-center justify-start gap-2 overflow-x-auto pb-2 sm:justify-center" style="-webkit-overflow-scrolling: touch;">
        @foreach($brands as $brand)
          <button
            @click="activeBrand = {{ $brand->id }}"
            :class="activeBrand === {{ $brand->id }}
              ? 'border-[#0C831F] bg-[#0C831F] text-white shadow-lg shadow-[#0C831F]/20'
              : 'border-sage/30 bg-white text-charcoal hover:border-[#0C831F]/40 hover:bg-[#0C831F]/5'"
            class="flex flex-shrink-0 items-center gap-2 rounded-full border px-5 py-2.5 text-sm font-semibold transition duration-300"
          >
            @if($brand->logo_path)
              <img
                src="{{ asset('storage/'.$brand->logo_path) }}"
                alt="{{ $brand->name }}"
                class="h-6 w-6 rounded-full object-contain"
                loading="lazy"
                :class="activeBrand === {{ $brand->id }} ? 'brightness-0 invert' : ''"
              />
            @else
              <span class="flex h-6 w-6 items-center justify-center rounded-full text-xs font-bold" :class="activeBrand === {{ $brand->id }} ? 'bg-white/20 text-white' : 'bg-[#0C831F]/10 text-[#0C831F]'">
                {{ strtoupper(substr($brand->name, 0, 1)) }}
              </span>
            @endif
            <span class="whitespace-nowrap">{{ $brand->name }}</span>
            <span class="text-xs opacity-60">({{ $brand->products_count }})</span>
          </button>
        @endforeach
      </div>

      {{-- Brand Product Grids --}}
      @foreach($brands as $brand)
        <div
          x-show="activeBrand === {{ $brand->id }}"
          x-transition:enter="transition duration-400 ease-out"
          x-transition:enter-start="opacity-0 translate-y-2"
          x-transition:enter-end="opacity-100 translate-y-0"
        >
          @if(isset($brandProducts[$brand->id]) && $brandProducts[$brand->id]->count())
            <div
              x-data="brandProductLoader({ brandId: {{ $brand->id }}, brandSlug: '{{ $brand->slug }}', initialCount: {{ $brandProducts[$brand->id]->count() }}, totalCount: {{ $brandTotalCounts[$brand->id] ?? 0 }})"
              x-init="checkInitial()"
            >
              <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 sm:gap-4" x-ref="grid">
                @foreach($brandProducts[$brand->id] as $product)
                  <x-product-card :product="$product" />
                @endforeach
              </div>
              <div class="mt-6 text-center">
                <div x-show="hasMore" x-cloak class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <button
                      @click="loadMore()"
                      :disabled="loading"
                      class="inline-flex items-center gap-2 rounded-full border border-[#0C831F]/30 bg-white px-6 py-2.5 text-sm font-semibold text-[#0C831F] transition duration-300 hover:border-[#0C831F] hover:bg-[#0C831F] hover:text-white disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                      <template x-if="!loading">
                        Load More
                        <x-lucide-chevron-down class="h-4 w-4" />
                      </template>
                      <template x-if="loading">
                        <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        Loading...
                      </template>
                    </button>
                    <button
                      @click="loadAll()"
                      :disabled="loading"
                      class="inline-flex items-center gap-2 rounded-full border border-[#0C831F]/30 bg-[#0C831F] px-6 py-2.5 text-sm font-semibold text-white transition duration-300 hover:bg-[#0A6E1A] disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                      <template x-if="!loading">
                        View All {{ $brand->name }} Products
                        <x-lucide-arrow-right class="h-4 w-4" />
                      </template>
                      <template x-if="loading">
                        <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        Loading All...
                      </template>
                    </button>
                  </div>
                <span x-show="!hasMore && totalLoaded >= totalCount && totalCount > 0" x-cloak
                  class="inline-flex items-center gap-1.5 rounded-full border border-sage/30 bg-white px-6 py-2.5 text-sm font-semibold text-charcoal/50">
                  All {{ $brand->name }} Products Loaded
                </span>
              </div>
            </div>
          @else
            <div class="mt-6 rounded-2xl border border-dashed border-sage/30 bg-white/50 py-12 text-center">
              <x-lucide-package-open class="mx-auto h-10 w-10 text-charcoal/20" />
              <p class="mt-3 text-sm text-charcoal/40">Products coming soon</p>
            </div>
          @endif
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ========== SECTION 4: PROMOTIONAL BANNERS ========== --}}
@if($promotionalBanners->count())
<section class="py-6">
  <div class="w-full px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
      @foreach($promotionalBanners as $banner)
        @if(!empty($banner->show_text) && $banner->show_text)
          <a href="{{ $banner->button_url ?? '#' }}" class="group relative block overflow-hidden rounded-2xl bg-gray-100 shadow-lg transition duration-300 hover:shadow-xl">
            @if(!empty($banner->mobile_image))
              <img
                src="{{ asset('storage/'.$banner->mobile_image) }}"
                alt="{{ $banner->title }}"
                class="block h-auto w-full sm:hidden"
                loading="lazy"
              />
            @endif
            <img
              src="{{ asset('storage/'.$banner->desktop_image) }}"
              alt="{{ $banner->title }}"
              class="{{ !empty($banner->mobile_image) ? 'hidden sm:block' : 'block' }} h-auto w-full"
              loading="lazy"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
            <div class="absolute inset-0 flex flex-col justify-end p-6">
              @if($banner->subtitle)
                <span class="mb-1 inline-block w-fit rounded-full bg-[#74C9A1]/90 px-3 py-0.5 text-[10px] font-bold uppercase tracking-wider text-white">{{ $banner->subtitle }}</span>
              @endif
              <h3 class="font-display text-xl font-extrabold text-white sm:text-2xl">{{ $banner->title }}</h3>
              @if($banner->button_text)
                <span class="mt-2 inline-flex w-fit items-center gap-1 text-sm font-bold text-[#74C9A1] transition duration-300 group-hover:text-white">
                  {{ $banner->button_text }}
                  <x-lucide-arrow-right class="h-4 w-4 transition duration-300 group-hover:translate-x-1" />
                </span>
              @endif
            </div>
          </a>
        @else
          <a href="{{ $banner->button_url ?? '#' }}" class="group relative block overflow-hidden rounded-2xl bg-gray-100 shadow-lg transition duration-300 hover:shadow-xl">
            @if(!empty($banner->mobile_image))
              <img
                src="{{ asset('storage/'.$banner->mobile_image) }}"
                alt="{{ $banner->title }}"
                class="block h-auto w-full sm:hidden"
                loading="lazy"
              />
            @endif
            <img
              src="{{ asset('storage/'.$banner->desktop_image) }}"
              alt="{{ $banner->title }}"
              class="{{ !empty($banner->mobile_image) ? 'hidden sm:block' : 'block' }} h-auto w-full"
              loading="lazy"
            />
          </a>
        @endif
      @endforeach
    </div>
  </div>
</section>
@else
{{-- Fallback Promo Cards --}}
@php
    $promoGradients = [
        'orange' => 'from-[#ff6b35] to-[#ff9a5c]',
        'green' => 'from-[#0C831F] to-[#2d9a4e]',
        'blue' => 'from-[#1d7fd4] to-[#4aa3e8]',
        'forest' => 'from-[#14532d] to-[#1a7a3a]',
    ];
    $promoTextColor = ['orange' => 'text-[#ff6b35]', 'green' => 'text-[#0C831F]', 'blue' => 'text-[#1d7fd4]', 'forest' => 'text-[#14532d]'];
    $promoCards = setting_json('home.promo_cards', [
        ['color' => 'orange', 'badge' => 'Limited Time', 'title' => 'Flat 20% Off', 'subtitle' => 'On your first organic order. Use code', 'code' => 'ORGANIC20', 'cta' => 'Order Now', 'link' => '/categories/all'],
        ['color' => 'green', 'badge' => 'Free Delivery', 'title' => 'Free Delivery on ₹499+', 'subtitle' => 'No minimum order. Get fresh organic produce delivered free!', 'code' => '', 'cta' => 'Shop Now', 'link' => '/categories/all'],
    ]);
@endphp
@if($promoCards && count($promoCards))
<section class="py-6">
  <div class="w-full px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-{{ min(2, count($promoCards)) }}">
      @foreach($promoCards as $card)
        @if(!empty($card['title']))
        @php $c = $card['color'] ?? 'green'; $grad = $promoGradients[$c] ?? 'from-[#0C831F] to-[#2d9a4e]'; @endphp
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br {{ $grad }} p-6 text-white shadow-lg transition duration-300 hover:shadow-xl sm:p-8">
          <div class="absolute -right-6 -top-6 h-32 w-32 rounded-full bg-white/10"></div>
          <div class="absolute -bottom-4 -right-4 h-24 w-24 rounded-full bg-white/10"></div>
          <div class="relative">
            @if(!empty($card['badge']))
              <span class="inline-block rounded-full bg-white/20 px-3 py-1 text-xs font-bold uppercase tracking-wide">{{ $card['badge'] }}</span>
            @endif
            <h3 class="mt-3 text-2xl font-extrabold sm:text-3xl">{{ $card['title'] }}</h3>
            <p class="mt-1 text-sm text-white/80">{{ $card['subtitle'] ?? '' }}@if(!empty($card['code'])) <span class="font-bold">{{ $card['code'] }}</span>@endif</p>
            @if(!empty($card['cta']))
              <a href="{{ $card['link'] ?: route('shop.index') }}" class="mt-4 inline-flex items-center gap-1 rounded-xl bg-white px-5 py-2.5 text-sm font-bold {{ $promoTextColor[$c] ?? 'text-[#0C831F]' }} transition duration-300 hover:bg-white/90">
                {{ $card['cta'] }}
                <x-lucide-arrow-right class="h-4 w-4" />
              </a>
            @endif
          </div>
        </div>
        @endif
      @endforeach
    </div>
  </div>
</section>
@endif
@endif

{{-- ========== SECTION 5: FEATURED PRODUCTS ========== --}}
@if($featuredProducts->count())
<section class="py-8">
  <div class="w-full px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="font-display text-xl font-bold text-charcoal sm:text-2xl">{{ setting('home.featured_title', 'Featured Products') }}</h2>
        <p class="mt-0.5 text-sm text-charcoal/50">{{ setting('home.featured_subtitle', 'Hand-picked organic favourites') }}</p>
      </div>
      <a href="{{ route('shop.index') }}" class="flex items-center gap-1 text-sm font-semibold text-[#0C831F] transition duration-300 hover:text-[#0C831F]/80">
        See All
        <x-lucide-chevron-right class="h-4 w-4" />
      </a>
    </div>
    <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 sm:gap-4">
      @foreach($featuredProducts as $product)
        <x-product-card :product="$product" />
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ========== SECTION 5b: BEST SELLERS ========== --}}
@if($bestSellers->count())
<section class="bg-[#fefdf5] py-8">
  <div class="w-full px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="font-display text-xl font-bold text-charcoal sm:text-2xl">{{ setting('home.best_title', 'Best Sellers') }}</h2>
        <p class="mt-0.5 text-sm text-charcoal/50">{{ setting('home.best_subtitle', 'What our community loves most') }}</p>
      </div>
      <a href="{{ route('shop.index') }}" class="flex items-center gap-1 text-sm font-semibold text-[#0C831F] transition duration-300 hover:text-[#0C831F]/80">
        See All
        <x-lucide-chevron-right class="h-4 w-4" />
      </a>
    </div>
    <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 sm:gap-4">
      @foreach($bestSellers as $product)
        <x-product-card :product="$product" />
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ========== SECTION 5c: NEW ARRIVALS ========== --}}
@if($newArrivals->count())
<section class="py-8">
  <div class="w-full px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="font-display text-xl font-bold text-charcoal sm:text-2xl">{{ setting('home.new_title', 'New Arrivals') }}</h2>
        <p class="mt-0.5 text-sm text-charcoal/50">{{ setting('home.new_subtitle', 'Just stocked fresh from the farms') }}</p>
      </div>
      <a href="{{ route('shop.index') }}" class="flex items-center gap-1 text-sm font-semibold text-[#0C831F] transition duration-300 hover:text-[#0C831F]/80">
        See All
        <x-lucide-chevron-right class="h-4 w-4" />
      </a>
    </div>
    <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 sm:gap-4">
      @foreach($newArrivals as $product)
        <x-product-card :product="$product" />
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ========== SECTION 6: WHY CHOOSE US ========== --}}
@php
    $whyIconMap = ['leaf' => 'leaf', 'truck' => 'truck', 'hand_coins' => 'hand-coins', 'shield_check' => 'shield-check', 'sprout' => 'sprout', 'sparkles' => 'sparkles', 'recycle' => 'recycle', 'heart' => 'heart'];
    $whyItems = setting_json('home.why_items', [
        ['icon' => 'leaf', 'title' => '100% Organic', 'text' => 'Certified organic with zero pesticides and chemicals'],
        ['icon' => 'truck', 'title' => '10-Min Delivery', 'text' => 'Lightning-fast delivery of fresh organic produce'],
        ['icon' => 'hand_coins', 'title' => 'Direct from Farms', 'text' => 'Fair prices to farmers, fresher produce for you'],
        ['icon' => 'shield_check', 'title' => 'Lab Tested', 'text' => 'Every product undergoes rigorous quality testing'],
    ]);
@endphp
@if($whyItems && count($whyItems))
<section class="border-t border-sage/10 bg-white py-10">
  <div class="w-full px-4 sm:px-6 lg:px-8">
    <h2 class="text-center font-display text-xl font-bold text-charcoal sm:text-2xl">{{ setting('home.why_title', 'Why Choose AB Organic Farm?') }}</h2>
    <div class="mt-8 grid grid-cols-2 gap-4 lg:grid-cols-{{ min(4, max(1, count($whyItems))) }}">
      @foreach($whyItems as $item)
        @if(!empty($item['title']))
        <div class="rounded-2xl border border-sage/20 bg-[#FDFBF7]/60 p-5 text-center transition duration-300 hover:border-[#0C831F]/30 hover:shadow-sm">
          <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-[#0C831F]/10 text-[#0C831F]">
            @php
                $icon = $whyIconMap[$item['icon'] ?? 'leaf'] ?? 'leaf';
                echo app(\BladeUI\Icons\Factory::class)->svg('lucide-'.$icon, 'h-6 w-6')->toHtml();
            @endphp
          </div>
          <h3 class="text-sm font-bold text-charcoal">{{ $item['title'] }}</h3>
          <p class="mt-1.5 text-xs leading-relaxed text-charcoal/50">{{ $item['text'] ?? '' }}</p>
        </div>
        @endif
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ========== SECTION 7: TESTIMONIALS ========== --}}
@if($testimonials->count())
<section class="bg-[#0C831F]/5 py-10">
  <div class="w-full px-4 sm:px-6 lg:px-8">
    <h2 class="text-center font-display text-xl font-bold text-charcoal sm:text-2xl">{{ setting('home.testimonial_title', 'What Our Customers Say') }}</h2>
    <div class="scrollbar-none mt-8 flex gap-4 overflow-x-auto pb-2 sm:justify-center" style="-webkit-overflow-scrolling: touch;">
      @foreach($testimonials as $review)
        <div class="w-72 flex-shrink-0 rounded-2xl border border-sage/20 bg-white p-5 shadow-sm transition duration-300 hover:shadow-md sm:w-80">
          <div class="mb-2 flex items-center gap-1 text-amber-400">
            @for($i = 1; $i <= 5; $i++)
              @if($i <= $review->rating)
                <x-lucide-star class="h-4 w-4 fill-amber-400" />
              @else
                <x-lucide-star class="h-4 w-4 text-charcoal/15" />
              @endif
            @endfor
          </div>
          <p class="text-sm leading-relaxed text-charcoal/70">"{{ Str::limit($review->body, 140) }}"</p>
          <div class="mt-4 flex items-center gap-3 border-t border-sage/10 pt-3">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[#0C831F]/10 text-xs font-bold text-[#0C831F]">
              {{ strtoupper(substr($review->user->name ?? 'V', 0, 1)) }}
            </div>
            <div>
              <div class="text-sm font-semibold text-charcoal">{{ $review->user->name ?? 'Verified Buyer' }}</div>
              <div class="text-xs text-charcoal/40">Verified Buyer</div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ========== SECTION 8: CTA FOOTER BANNER ========== --}}
<section class="relative overflow-hidden bg-gradient-to-r from-[#0C831F] via-[#1a7a3a] to-[#0C831F] py-12">
  <div class="absolute inset-0 opacity-10">
    <div class="absolute -left-20 -top-20 h-60 w-60 rounded-full bg-white/20 blur-3xl"></div>
    <div class="absolute -bottom-16 -right-16 h-48 w-48 rounded-full bg-[#74C9A1]/20 blur-3xl"></div>
  </div>
  <div class="relative mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8">
    <h2 class="font-display text-3xl font-extrabold text-white sm:text-4xl">
      {{ setting('home.cta_title', 'Go Organic. Go Fresh. Go Fast.') }}
    </h2>
    <p class="mx-auto mt-3 max-w-lg text-base text-white/70">
      {{ setting('home.cta_subtitle', 'Join thousands of families who trust AB Organic Farm for their daily groceries. Your first delivery is on us!') }}
    </p>
    <div class="mt-7 flex flex-wrap items-center justify-center gap-4">
      <a href="{{ setting('home.cta_link', route('shop.index')) }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-7 py-3.5 text-sm font-bold text-[#0C831F] shadow-lg transition duration-300 hover:bg-white/90 hover:shadow-xl">
        <x-lucide-shopping-cart class="h-4 w-4" />
        {{ setting('home.cta_button', 'Start Shopping') }}
      </a>
      <a href="{{ route('shop.categories') }}" class="inline-flex items-center gap-2 rounded-xl border-2 border-white/30 px-7 py-3.5 text-sm font-bold text-white transition duration-300 hover:border-white/60 hover:bg-white/10">
        Browse Categories
      </a>
    </div>
  </div>
</section>

<style>
  .scrollbar-none {
    scrollbar-width: none;
    -ms-overflow-style: none;
  }
  .scrollbar-none::-webkit-scrollbar {
    display: none;
  }
  .rail-scroll { scroll-behavior: smooth; }
  .rail-scroll::-webkit-scrollbar { height: 4px; }
  .rail-scroll::-webkit-scrollbar-track { background: transparent; }
  .rail-scroll::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 9999px; }
  .rail-scroll:hover::-webkit-scrollbar-thumb { background: #0C831F; }
</style>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('brandProductLoader', (config) => ({
        brandId: config.brandId,
        brandSlug: config.brandSlug,
        offset: config.initialCount,
        totalLoaded: config.initialCount,
        totalCount: config.totalCount,
        loading: false,
        hasMore: true,

        checkInitial() {
            this.hasMore = this.totalLoaded < this.totalCount;
        },

        async loadMore() {
            if (this.loading || !this.hasMore) return;
            this.loading = true;
            try {
                const url = `/api/brands/${this.brandId}/products?offset=${this.offset}&limit=12`;
                const resp = await fetch(url, { headers: { 'Accept': 'application/json' } });
                const data = await resp.json();
                if (data.html && data.html.trim()) {
                    this.$refs.grid.insertAdjacentHTML('beforeend', data.html);
                    this.offset += data.count;
                    this.totalLoaded += data.count;
                    this.hasMore = data.hasMore;
                } else {
                    this.hasMore = false;
                }
            } catch (e) {
                console.error('Load more failed:', e);
                this.hasMore = false;
            } finally {
                this.loading = false;
            }
        },

        async loadAll() {
            if (this.loading || !this.hasMore) return;
            this.loading = true;
            try {
                // Load all remaining at once
                const remaining = this.totalCount - this.totalLoaded;
                const url = `/api/brands/${this.brandId}/products?offset=${this.offset}&limit=${remaining}`;
                const resp = await fetch(url, { headers: { 'Accept': 'application/json' } });
                const data = await resp.json();
                if (data.html && data.html.trim()) {
                    this.$refs.grid.insertAdjacentHTML('beforeend', data.html);
                    this.offset += data.count;
                    this.totalLoaded += data.count;
                    this.hasMore = data.hasMore;
                } else {
                    this.hasMore = false;
                }
            } catch (e) {
                console.error('Load all failed:', e);
                this.hasMore = false;
            } finally {
                this.loading = false;
            }
        },
    }));
});
</script>

@endsection
