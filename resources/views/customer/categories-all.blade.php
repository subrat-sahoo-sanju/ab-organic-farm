@extends('layouts.app', ['title' => 'All Categories'])

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
  <h1 class="font-display text-3xl font-bold text-charcoal">Shop by Category</h1>
  <p class="mt-2 text-charcoal/60">Find exactly what you need — all certified organic, all farm-fresh.</p>

  <div class="mt-10 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 sm:gap-4">
    @foreach($categories as $category)
      <a href="{{ route('shop.category', $category->slug) }}" class="group relative overflow-hidden rounded-2xl border border-sage/20 bg-white shadow-sm transition hover:shadow-md hover:border-forest">
        <div class="aspect-[4/3] bg-forest/5 p-8 flex items-center justify-center">
          @if($category->image_path)
            <img src="{{ asset('storage/'.$category->image_path) }}" alt="{{ $category->name }}" class="h-12 w-12 rounded-xl object-cover">
          @else
            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#0C831F]/10 text-2xl leading-none">{{ $category->icon ?? '🌱' }}</span>
          @endif
        </div>
        <div class="p-5">
          <h2 class="text-lg font-bold text-charcoal transition group-hover:text-forest">{{ $category->name }}</h2>
          @if($category->description)
            <p class="mt-1 text-sm text-charcoal/60 line-clamp-2">{{ $category->description }}</p>
          @endif
          <div class="mt-3 flex items-center justify-between">
            <span class="text-xs font-semibold text-charcoal/40">{{ $category->products_count ?? $category->products()->count() }} products</span>
            <span class="text-sm font-semibold text-forest opacity-0 transition group-hover:opacity-100">Browse →</span>
          </div>
        </div>
      </a>
    @endforeach
  </div>
</div>
@endsection
