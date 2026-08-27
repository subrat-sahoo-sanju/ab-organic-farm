@extends('layouts.app', ['title' => 'Search Results — AB Organic Farm'])

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
  <h1 class="font-display text-2xl font-bold text-charcoal">
    @if($query)
      Results for "{{ $query }}"
    @else
      Search Products
    @endif
  </h1>
  <p class="mt-1 text-sm text-charcoal/50">{{ $products->total() }} product(s) found</p>

  @if($products->count())
    <div class="mt-8 grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 sm:gap-4">
      @foreach($products as $product)
        <x-product-card :product="$product" />
      @endforeach
    </div>
    <div class="mt-10">{{ $products->withQueryString()->links('pagination::tailwind') }}</div>
  @else
    <div class="mt-12 rounded-2xl border border-sage/20 bg-white py-16 text-center">
      <div class="text-5xl mb-4 opacity-40">🔍</div>
      <p class="text-charcoal/50">No products match your search.</p>
      <a href="{{ route('shop.index') }}" class="mt-4 inline-block text-sm font-semibold text-forest hover:underline">Browse All Products →</a>
    </div>
  @endif
</div>
@endsection
