@php
    $cats = $rootCategories ?? collect();
    $limit = (int) ($sec->config['product_count'] ?? 8);
    $cats = $cats->take($limit);
@endphp

@if($cats->count())
<section class="py-8">
  <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
    @include('customer.sections._header', ['title' => $sec->title, 'subtitle' => $sec->subtitle, 'align' => 'center', 'link' => route('shop.categories'), 'linkLabel' => 'All Categories'])

    <div class="scrollbar-none mt-6 flex items-start gap-5 overflow-x-auto pb-2" style="-webkit-overflow-scrolling: touch;">
      @foreach($cats as $cat)
        <a href="{{ route('shop.category', $cat->slug) }}" class="group flex w-[19%] min-w-[76px] flex-col items-center gap-2">
          <span class="flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-[#EEF5EC] to-[#FDF6E3] text-2xl ring-1 ring-cream-200 transition-all duration-300 group-hover:scale-105 group-hover:ring-forest-300 group-hover:shadow-[0_8px_20px_-6px_rgba(12,131,31,0.35)]">
            @if($cat->image_path)
              <img src="{{ asset('storage/'.$cat->image_path) }}" alt="{{ $cat->name }}" class="h-full w-full rounded-full object-cover" loading="lazy" />
            @elseif($cat->icon)
              @php echo app(\BladeUI\Icons\Factory::class)->svg('lucide-'.$cat->icon, 'h-7 w-7 text-forest-700')->toHtml(); @endphp
            @else
              <span class="text-forest-700">🌱</span>
            @endif
          </span>
          <span class="text-center text-[11px] font-bold leading-tight text-charcoal-800 transition group-hover:text-forest-700">{{ $cat->name }}</span>
          @if($cat->products_count)
            <span class="text-[10px] font-semibold text-charcoal-600/40">{{ $cat->products_count }} items</span>
          @endif
        </a>
      @endforeach
    </div>
  </div>
</section>
@endif