@extends('layouts.app', ['title' => 'My Wishlist'])

@section('content')
<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
  <h1 class="font-display text-2xl font-bold text-charcoal mb-8">My Wishlist 💚</h1>

  @if($products->count())
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
      @foreach($products as $product)
        <div class="relative">
          <x-product-card :product="$product" />
          <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="absolute top-3 right-3 z-10">
            @csrf @method('DELETE')
            <button type="submit" class="flex h-8 w-8 items-center justify-center rounded-full bg-white shadow-sm text-red-400 hover:text-red-600 transition" title="Remove from wishlist">♥</button>
          </form>
        </div>
      @endforeach
    </div>
  @else
    <div class="rounded-2xl border border-sage/20 bg-white py-16 text-center">
      <div class="text-5xl mb-4 opacity-40">💚</div>
      <p class="text-charcoal/50">Your wishlist is empty.</p>
      <a href="{{ route('shop.index') }}" class="mt-4 inline-block text-sm font-semibold text-forest hover:underline">Browse Products →</a>
    </div>
  @endif
</div>
@endsection
