@extends('layouts.app', ['title' => $category->name . ' — AB Organic Farm'])

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
  <nav class="mb-6 text-sm text-charcoal/50">
    <a href="{{ route('shop.index') }}" class="hover:text-forest">Home</a>
    <span class="mx-1">/</span>
    <a href="{{ route('shop.categories') }}" class="hover:text-forest">Categories</a>
    <span class="mx-1">/</span>
    <span class="text-charcoal">{{ $category->name }}</span>
  </nav>

  <div class="mb-8">
    <h1 class="font-display text-3xl font-bold text-charcoal">{{ $category->name }}</h1>
    @if($category->description)
      <p class="mt-2 max-w-2xl text-charcoal/60">{{ $category->description }}</p>
    @endif
  </div>

  @if($category->image_path)
  <div class="relative h-40 w-full overflow-hidden rounded-2xl mb-6">
    <img src="{{ asset('storage/'.$category->image_path) }}" alt="{{ $category->name }}" class="h-full w-full object-cover" loading="lazy">
    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
    <div class="absolute bottom-4 left-4">
      <h2 class="text-xl font-bold text-white">{{ $category->name }}</h2>
    </div>
  </div>
  @endif

  @if($subcategories->count())
    <div class="mb-8 flex flex-wrap gap-3">
      <a href="{{ route('shop.category', $category->slug) }}" class="rounded-full border px-4 py-2 text-sm font-semibold transition {{ !request('sub') ? 'border-forest bg-forest text-white' : 'border-sage/30 bg-white text-charcoal hover:border-forest' }}">All</a>
      @foreach($subcategories as $sub)
        <a href="{{ route('shop.category', $category->slug) }}?sub={{ $sub->slug }}" class="rounded-full border px-4 py-2 text-sm font-semibold transition {{ request('sub') === $sub->slug ? 'border-forest bg-forest text-white' : 'border-sage/30 bg-white text-charcoal hover:border-forest' }}">{{ $sub->name }}</a>
      @endforeach
    </div>
  @endif

  <div class="flex items-center justify-between mb-6">
    <p class="text-sm text-charcoal/50">{{ $products->total() }} product(s)</p>
    <form method="GET" class="flex gap-2">
      <select name="sort" onchange="this.form.submit()" class="rounded-lg border border-sage/30 bg-white px-3 py-1.5 text-sm text-charcoal focus:border-forest focus:ring-1 focus:ring-forest/30">
        <option value="">Sort: Featured</option>
        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name: A–Z</option>
        <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Top Rated</option>
        <option value="best_selling" {{ request('sort') === 'best_selling' ? 'selected' : '' }}>Best Selling</option>
      </select>
    </form>
  </div>

  @if($products->count())
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
      @foreach($products as $product)
        <x-product-card :product="$product" />
      @endforeach
    </div>
    <div class="mt-10">{{ $products->withQueryString()->links('pagination::tailwind') }}</div>
  @else
    <div class="rounded-2xl border border-sage/20 bg-white py-16 text-center">
      <div class="text-5xl mb-4 opacity-40">🌿</div>
      <p class="text-charcoal/50">No products found in this category yet.</p>
      <a href="{{ route('shop.index') }}" class="mt-4 inline-block text-sm font-semibold text-forest hover:underline">Browse All Products →</a>
    </div>
  @endif
</div>
@endsection
