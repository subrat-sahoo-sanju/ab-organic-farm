@php
    $cats = $data['categories'] ?? collect();
    $tabProducts = $data['tabProducts'] ?? [];
    $limit = $data['limit'] ?? 8;
    $gridId = 'focus-grid-'.$sec->key;
@endphp

@if($cats->count())
<section class="bg-[#FEFDF6] py-12">
  <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="text-center">
      <span class="inline-flex items-center gap-1.5 text-[11px] font-extrabold uppercase tracking-[0.18em] text-forest-600/70">
        <span class="h-px w-6 bg-forest-600/40"></span>Product in Focus<span class="h-px w-6 bg-forest-600/40"></span>
      </span>
      <h2 class="mt-2 font-display text-2xl font-bold tracking-tight text-charcoal-900 sm:text-3xl">{{ $sec->title }}</h2>
      <p class="mx-auto mt-1.5 max-w-xl text-sm text-charcoal-600/60">{{ $sec->subtitle }}</p>
    </div>

    <div x-data="tabGrid('{{ $gridId }}', '{{ $cats->first()->id }}')" class="mt-8">
      {{-- Tab pills --}}
      <div class="scrollbar-none flex items-center justify-start gap-2 overflow-x-auto pb-1 sm:justify-center">
        @foreach($cats as $cat)
          <button
            type="button"
            @click="pick('{{ $cat->id }}', '{{ route('api.category.products', $cat) }}?limit={{ $limit }}')"
            :class="active == '{{ $cat->id }}'
              ? 'rounded-full bg-forest-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-forest-600/25'
              : 'rounded-full border border-cream-200 bg-white px-5 py-2.5 text-sm font-semibold text-charcoal-700 transition hover:border-forest-500 hover:text-forest-700'"
          >{{ $cat->name }}</button>
        @endforeach
      </div>

      {{-- Grid: first tab server-rendered, others lazy via same tabGrid pick() --}}
      <div id="{{ $gridId }}" class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 sm:gap-4">
        @php $first = $cats->first(); @endphp
        @foreach(($tabProducts[$first->id] ?? collect()) as $product)
          <x-product-card :product="$product" />
        @endforeach
      </div>

      @if($data['categories'] ?? false)
        <div class="mt-7 text-center">
          <a href="{{ route('shop.category', $cats->first()->slug) }}" class="inline-flex items-center gap-2 rounded-full border border-forest-600/30 bg-white px-6 py-3 text-sm font-bold text-forest-700 transition hover:bg-forest-600 hover:text-white">
            Shop {{ $sec->title }}<x-lucide-arrow-right class="h-4 w-4" />
          </a>
        </div>
      @endif
    </div>
  </div>
</section>

@include('customer.sections._tabs-js')
@endif