@php
    $tabs = $tabs ?? collect();
    $products = $data ?? collect();
    $limit = (int) ($sec->config['product_count'] ?? 12);
@endphp

<section class="relative overflow-hidden bg-gradient-to-b from-[#F5F0E4] via-[#FDFBF7] to-[#FDFBF7] py-12">
  <div class="pointer-events-none absolute -right-24 top-0 h-72 w-72 rounded-full bg-forest-100/50 blur-3xl"></div>
  <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="text-center">
      <span class="inline-flex items-center gap-1.5 text-[11px] font-extrabold uppercase tracking-[0.18em] text-forest-600/70">
        <span class="h-px w-6 bg-forest-600/40"></span><x-lucide-sprout class="h-4 w-4" /><span class="h-px w-6 bg-forest-600/40"></span>
      </span>
      <h2 class="mt-2 font-display text-3xl font-bold tracking-tight text-charcoal-900 sm:text-4xl">{{ $sec->title }}</h2>
      <p class="mx-auto mt-2 max-w-xl text-sm text-charcoal-600/60 sm:text-base">{{ $sec->subtitle }}</p>
    </div>

    {{-- Filter tabs --}}
    <div x-data="tabGrid('welcome-grid', 'all')" class="mt-8">
      <div class="scrollbar-none flex items-center justify-start gap-2 overflow-x-auto pb-1 sm:justify-center">
        <button
          type="button"
          @click="pick('all', '/')"
          :class="active === 'all'
            ? 'rounded-full bg-forest-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-forest-600/25'
            : 'rounded-full border border-cream-200 bg-white px-5 py-2.5 text-sm font-semibold text-charcoal-700 transition hover:border-forest-500 hover:text-forest-700'"
        >All</button>
        @foreach($tabs as $tab)
          <button
            type="button"
            @click="pick('{{ $tab->id }}', '{{ route('api.category.products', $tab) }}?limit={{ $limit }}')"
            :class="active == '{{ $tab->id }}'
              ? 'rounded-full bg-forest-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-forest-600/25'
              : 'rounded-full border border-cream-200 bg-white px-5 py-2.5 text-sm font-semibold text-charcoal-700 transition hover:border-forest-500 hover:text-forest-700'"
          >{{ $tab->name }}</button>
        @endforeach
      </div>

      {{-- Product grid (initial = All) --}}
      <div id="welcome-grid" class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 sm:gap-4">
        @foreach($products as $product)
          <x-product-card :product="$product" />
        @endforeach
        @if($products->isEmpty())
          <div class="col-span-full rounded-2xl border border-dashed border-cream-200 bg-white/60 py-14 text-center text-sm text-charcoal-600/50">Fresh products are being stocked — check back soon!</div>
        @endif
      </div>

      <div class="mt-7 text-center">
        <a href="{{ route('shop.categories') }}" class="inline-flex items-center gap-2 rounded-full border border-forest-600/30 bg-white px-6 py-3 text-sm font-bold text-forest-700 transition hover:bg-forest-600 hover:text-white">
          Explore All Products<x-lucide-arrow-right class="h-4 w-4" />
        </a>
      </div>
    </div>
  </div>
</section>

@include('customer.sections._tabs-js')