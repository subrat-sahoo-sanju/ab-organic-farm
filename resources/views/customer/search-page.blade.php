@extends('layouts.app', ['title' => 'Search — AB Organic Farm'])

@section('content')
<div class="mx-auto max-w-3xl px-4 py-16 sm:px-6 lg:px-8" x-data="{ q: '{{ request('q') }}', recent: JSON.parse(localStorage.getItem('verdura_recent') || '[]') }">
  <h1 class="font-display text-3xl font-bold text-charcoal text-center">What are you looking for?</h1>
  <p class="mt-2 text-center text-charcoal/50">Search our complete range of certified organic products</p>

  <div class="mt-8">
    <form action="{{ route('shop.search') }}" method="GET" class="relative">
      <input type="text" name="q" x-model="q" placeholder="Organic turmeric, cold-pressed oil..." autofocus
             class="w-full rounded-2xl border border-sage/30 bg-white py-4 pl-6 pr-14 text-lg text-charcoal placeholder:text-charcoal/30 focus:border-forest focus:ring-2 focus:ring-forest/20 shadow-sm">
      <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 rounded-xl bg-forest p-2.5 text-white transition hover:bg-forest/90">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      </button>
    </form>
  </div>

  @if($popularCategories->count())
    <div class="mt-12">
      <h2 class="text-sm font-semibold text-charcoal/40 uppercase tracking-wider mb-4">Popular Categories</h2>
      <div class="flex flex-wrap gap-3">
        @foreach($popularCategories as $cat)
          <a href="{{ route('shop.category', $cat->slug) }}" class="rounded-full border border-sage/30 bg-white px-4 py-2 text-sm font-medium text-charcoal transition hover:border-forest hover:text-forest">
            {{ $cat->icon ?? '🌱' }} {{ $cat->name }}
          </a>
        @endforeach
      </div>
    </div>
  @endif

  @if(count($trending) > 0)
    <div class="mt-8">
      <h2 class="text-sm font-semibold text-charcoal/40 uppercase tracking-wider mb-4">Trending Now</h2>
      <div class="flex flex-wrap gap-3">
        @foreach($trending as $term)
          <a href="{{ route('shop.search', ['q' => $term]) }}" class="rounded-full bg-sage/10 px-4 py-2 text-sm text-charcoal/70 hover:bg-sage/20 transition">🔥 {{ $term }}</a>
        @endforeach
      </div>
    </div>
  @endif
</div>
@endsection
