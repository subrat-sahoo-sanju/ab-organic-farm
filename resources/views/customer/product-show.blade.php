@extends('layouts.app', ['title' => $product->seo_title ?: $product->name])

@push('meta')
<meta name="description" content="{{ $product->meta_description ?: Str::limit(strip_tags($product->short_description), 160) }}">
<meta property="og:title" content="{{ $product->seo_title ?: $product->name }}">
<meta property="og:description" content="{{ $product->meta_description ?: Str::limit(strip_tags($product->short_description), 160) }}">
@if($product->primaryImage)
  <meta property="og:image" content="{{ asset('storage/'.$product->primaryImage->path) }}">
@endif
@endpush

@php
$images = $product->images()->orderBy('sort_order')->get();
$hasVariants = $product->variants()->count() > 1;
$activeVariants = $product->variants()->with('inventory')->where('is_active', true)->get();
$inventory = $product->defaultVariant?->inventory;
$inStock = $inventory && $inventory->available() > 0;
@endphp

@section('content')
<div class="mx-auto max-w-[1440px] px-4 py-8 sm:px-6 lg:px-8" x-data="productPage()">
  <nav class="mb-6 text-sm text-charcoal/50">
    <a href="{{ route('shop.index') }}" class="hover:text-forest">Home</a>
    <span class="mx-1">/</span>
    @if($product->category)
      <a href="{{ route('shop.category', $product->category->slug) }}" class="hover:text-forest">{{ $product->category->name }}</a>
      <span class="mx-1">/</span>
    @endif
    <span class="text-charcoal">{{ $product->name }}</span>
  </nav>

  <div class="grid gap-10 lg:grid-cols-[1fr_1.2fr]">
    <div>
      <div class="rounded-2xl border border-sage/20 bg-white p-6 shadow-sm">
        @if($images->count())
          <div class="relative aspect-square overflow-hidden rounded-xl bg-forest/5">
            <img x-bind:src="images[activeImage]" :alt="imagesAlt[activeImage]" class="h-full w-full object-contain p-4">
            @if($product->is_organic)
              <span class="absolute top-3 left-3 rounded-full bg-forest px-2.5 py-0.5 text-xs font-semibold text-white shadow-sm">Organic Certified</span>
            @endif
          </div>
          @if($images->count() > 1)
            <div class="mt-3 flex gap-2 overflow-x-auto pb-1">
              @foreach($images as $i => $image)
                <button @click="activeImage = {{ $i }}" class="h-16 w-16 shrink-0 rounded-lg border-2 transition p-1 bg-white" :class="activeImage === {{ $i }} ? 'border-forest' : 'border-transparent hover:border-sage'">
                  <img src="{{ $image->thumb_path ? asset('storage/'.$image->thumb_path) : asset('storage/'.$image->path) }}" alt="{{ $image->alt_text }}" class="h-full w-full object-contain">
                </button>
              @endforeach
            </div>
          @endif
        @else
          <div class="flex aspect-square items-center justify-center bg-forest/5 rounded-xl text-8xl opacity-40">🌿</div>
        @endif
      </div>

      <div class="mt-8 rounded-2xl border border-sage/20 bg-white p-6 shadow-sm space-y-4 text-sm text-charcoal/80">
        @if($product->description)
          <div class="prose prose-sm prose-charcoal max-w-none">{!! $product->description !!}</div>
        @endif
        @if($product->ingredients)
          <div><span class="font-semibold text-charcoal">Ingredients:</span> {{ $product->ingredients }}</div>
        @endif
        @if($product->benefits)
          <div><span class="font-semibold text-charcoal">Benefits:</span> {{ $product->benefits }}</div>
        @endif
        @if($product->usage_instructions)
          <div><span class="font-semibold text-charcoal">How to Use:</span> {{ $product->usage_instructions }}</div>
        @endif
        @if($product->storage_instructions)
          <div><span class="font-semibold text-charcoal">Storage:</span> {{ $product->storage_instructions }}</div>
        @endif
      </div>
    </div>

    <div>
      <div class="flex items-center gap-3 mb-2">
        @if($product->brand)
          <span class="rounded-full bg-sage/10 px-2.5 py-0.5 text-xs font-semibold text-charcoal/60">{{ $product->brand->name }}</span>
        @endif
        @if($product->certification)
          <span class="rounded-full bg-forest/10 px-2.5 py-0.5 text-xs font-semibold text-forest">{{ $product->certification }}</span>
        @endif
      </div>

      <h1 class="font-display text-2xl font-bold text-charcoal lg:text-3xl">{{ $product->name }}</h1>

      @if($product->rating_avg > 0)
        <div class="mt-2 flex items-center gap-2 text-sm text-amber-500">
          @for($i = 1; $i <= 5; $i++)
            @if($i <= round($product->rating_avg))★@else☆@endif
          @endfor
          <span class="text-charcoal/50">({{ $product->review_count }} reviews)</span>
        </div>
      @endif

      @if($product->short_description)
        <p class="mt-3 text-charcoal/60">{{ $product->short_description }}</p>
      @endif

      <div class="mt-4 flex flex-wrap gap-4 text-xs text-charcoal/50">
        @if($product->origin)<span>📍 Origin: {{ $product->origin }}</span>@endif
        @if($product->farmer_source)<span>🌱 Source: {{ $product->farmer_source }}</span>@endif
        @if($product->unit_label)<span>📦 Unit: {{ $product->unit_label }}</span>@endif
      </div>

      <div class="mt-6 rounded-2xl border border-sage/20 bg-white p-6 shadow-sm">
        <form action="{{ route('cart.add') }}" method="POST" id="add-to-cart-form">
          @csrf

          @if($hasVariants)
            <div class="mb-5">
              <label class="mb-2 block text-sm font-semibold text-charcoal">Select Option:</label>
              <div class="flex flex-wrap gap-2">
                @foreach($activeVariants as $variant)
                  <label class="relative cursor-pointer">
                    <input type="radio" name="variant_id" value="{{ $variant->id }}" {{ $variant->id === $product->default_variant_id ? 'checked' : '' }} {{ $variant->inventory && $variant->inventory->available() > 0 ? '' : 'disabled' }} @change="selectedPrice = {{ $variant->sale_price ?? $variant->price }}; selectedComparePrice = {{ $variant->price }}" class="peer sr-only">
                    <div class="rounded-xl border-2 border-sage/30 px-4 py-2.5 text-sm transition peer-checked:border-forest peer-checked:bg-forest/5 peer-checked:text-forest peer-disabled:opacity-40 hover:border-forest/50">
                      <div class="font-semibold">{{ $variant->name }}</div>
                      <div class="text-xs mt-0.5">₹{{ number_format($variant->sale_price ?? $variant->price) }} @if($variant->sale_price && $variant->sale_price < $variant->price)<span class="line-through text-charcoal/40">₹{{ number_format($variant->price) }}</span>@endif</div>
                    </div>
                  </label>
                @endforeach
              </div>
              @error('variant_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
          @else
            <input type="hidden" name="variant_id" value="{{ $product->variants()->first()?->id }}">
          @endif

          <div class="mb-5">
            <label class="mb-2 block text-sm font-semibold text-charcoal">Quantity:</label>
            <div class="flex w-fit items-center rounded-xl border border-sage/30 bg-white">
              <button type="button" @click="qty = Math.max(1, qty - 1)" class="flex h-10 w-10 items-center justify-center text-lg text-charcoal/60 hover:text-forest transition">−</button>
              <input type="number" name="quantity" x-model.number="qty" min="1" readonly class="h-10 w-14 border-0 bg-transparent text-center font-semibold text-charcoal focus:ring-0">
              <button type="button" @click="qty++" class="flex h-10 w-10 items-center justify-center text-lg text-charcoal/60 hover:text-forest transition">+</button>
            </div>
          </div>

          <div class="mb-5 flex items-baseline gap-3">
            <span class="text-3xl font-bold text-charcoal">₹<span x-text="formatPrice(selectedPrice)"></span></span>
            <template x-if="selectedComparePrice > selectedPrice">
              <span class="text-sm text-charcoal/40 line-through">₹<span x-text="formatPrice(selectedComparePrice)"></span></span>
            </template>
          </div>

          @if($inStock)
            <button type="submit" class="btn btn-primary w-full btn-lg" x-bind:disabled="!selectedVariantAvailable">
              <span x-text="selectedVariantAvailable ? 'Add to Basket — ₹' + formatPrice(selectedPrice * qty) : 'Select an Option'"></span>
            </button>
          @else
            <button disabled class="btn w-full bg-charcoal/10 text-charcoal/40 cursor-not-allowed btn-lg">Out of Stock</button>
          @endif

          <p class="mt-4 text-center text-xs text-charcoal/40">✓ COD Available • Free delivery above ₹{{ (float) setting('delivery.free_above', 499) }}</p>
        </form>
      </div>

      @if($product->tags?->count())
        <div class="mt-6">
          <span class="text-sm font-semibold text-charcoal">Tags:</span>
          <div class="mt-2 flex flex-wrap gap-2">
            @foreach($product->tags as $tag)
              <a href="{{ route('shop.index') }}?tag={{ $tag->slug }}" class="rounded-full bg-sage/10 px-3 py-1 text-xs text-charcoal/60 hover:bg-sage/20 transition">{{ $tag->name }}</a>
            @endforeach
          </div>
        </div>
      @endif
    </div>
  </div>
</div>

<script>
function productPage() {
  return {
    images: @json($images->map(fn($img) => asset('storage/'.$img->path))),
    imagesAlt: @json($images->pluck('alt_text')),
    activeImage: 0,
    qty: 1,
    selectedPrice: {{ $product->defaultVariant?->sale_price ?? $product->defaultVariant?->price ?? 0 }},
    selectedComparePrice: {{ $product->defaultVariant?->price ?? 0 }},
    formatPrice(v) { return Number(v).toLocaleString('en-IN', {minimumFractionDigits: 0, maximumFractionDigits: 0}); },
    get selectedVariantAvailable() {
      const checked = document.querySelector('input[name=variant_id]:checked');
      if (checked) return !checked.disabled;
      // Single-variant / non-selectable products use a hidden variant input that is
      // never :checked, so they are always "available" (the renderer already shows a
      // disabled "Out of Stock" state when the stock is gone).
      return true;
    }
  }
}
</script>

<script>
  (function () {
    try {
      var id = @js($product->id);
      var list = JSON.parse(localStorage.getItem('ab_recent_views') || '[]');
      if (!Array.isArray(list)) list = [];
      list = list.filter(function (n) { return n !== id; });
      list.unshift(id);
      localStorage.setItem('ab_recent_views', JSON.stringify(list.slice(0, 12)));
    } catch (e) {}
  })();
</script>

@if($reviews->count())
  <div class="mx-auto max-w-[1440px] px-4 py-16 sm:px-6 lg:px-8">
    <h2 class="font-display text-2xl font-bold text-charcoal">Customer Reviews</h2>
    <div class="mt-6 space-y-4">
      @foreach($reviews as $review)
        <div class="rounded-2xl border border-sage/20 bg-white p-6 shadow-sm">
          <div class="flex items-center gap-3 mb-2">
            <div class="text-amber-500 text-sm">
              @for($i = 1; $i <= 5; $i++)
                @if($i <= $review->rating)★@else☆@endif
              @endfor
            </div>
            <span class="text-sm text-charcoal/50">{{ $review->created_at->diffForHumans() }}</span>
          </div>
          @if($review->title)<h3 class="font-semibold text-charcoal">{{ $review->title }}</h3>@endif
          <p class="mt-2 text-sm text-charcoal/70">{{ $review->body }}</p>
          <div class="mt-3 text-xs text-charcoal/40">— {{ $review->user->name ?? 'Verified Buyer' }}</div>
        </div>
      @endforeach
    </div>
  </div>
@endif
@endsection
