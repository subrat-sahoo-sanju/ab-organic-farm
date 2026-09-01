@php
    $tiles = $data ?? collect();
@endphp

@if($tiles->count())
<section class="py-12">
  <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
    @include('customer.sections._header', ['title' => $sec->title, 'subtitle' => $sec->subtitle, 'align' => 'center'])

    <div class="mt-8 grid grid-cols-2 gap-3 sm:gap-5 lg:grid-cols-4">
      @foreach($tiles as $tile)
        <a href="{{ $tile->url ?: route('shop.categories') }}" class="group relative block overflow-hidden rounded-2xl bg-cream-100">
          @if($tile->image)
            <img src="{{ asset('storage/'.$tile->image) }}" alt="{{ $tile->title ?? 'Hand-picked quality' }}" class="aspect-[4/5] w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy" />
          @else
            <div class="flex aspect-[4/5] w-full items-center justify-center bg-gradient-to-br from-forest-50 to-cream-100 text-5xl">🌿</div>
          @endif
          <div class="absolute inset-0 bg-gradient-to-t from-charcoal-900/60 via-transparent to-transparent opacity-0 transition duration-300 group-hover:opacity-100"></div>
          <div class="absolute inset-x-0 bottom-0 translate-y-4 p-4 opacity-0 transition duration-300 group-hover:translate-y-0 group-hover:opacity-100">
            <p class="font-display text-sm font-bold text-white">{{ $tile->title }}</p>
            @if($tile->subtitle)<p class="mt-0.5 text-[11px] text-white/80">{{ $tile->subtitle }}</p>@endif
          </div>
        </a>
      @endforeach
    </div>
  </div>
</section>
@endif