@php
    $key = $sec->key ?? '';
    $messages = [
        'combos'        => 'Delicious combo packs are being put together. Check back soon.',
        'superfoods'    => 'Our superfood range is being restocked with fresh farm produce.',
        'best_sellers'  => 'Best sellers are being refreshed — new favourites landing soon.',
        'trusted_by'    => 'We are proudly trusted by families across our community.',
        'logo_slider'   => 'Brand partnerships are being added.',
        'testimonials'  => 'Be the first to share your experience with our farm.',
        'trending'      => 'Trending products are coming your way shortly.',
        'new_arrivals'  => 'Fresh new arrivals are on their way from the farm.',
        'recommended'   => 'Hand-picked recommendations are being prepared.',
        'organic_picks' => 'Organic picks are being gathered for you.',
        'deals'         => 'Exciting deals are cooking up — stay tuned.',
        'recently_viewed' => 'Products you browse around the store will appear here.',
        'native_ingredients' => 'Authentic native produce is being sourced.',
        'quality'       => 'Only the best quality products make the cut.',
        'app_download'  => 'Get the AB Organic app for the freshest experience.',
        'focus_oils'    => 'Explore our range of cold-pressed oils.',
        'focus_ghee'    => 'Explore our range of A2 desi ghee.',
    ];
    $iconMap = [
        'combos' => 'M4 6h16M4 12h16M4 18h16', 'superfoods' => 'M12 3l2.7 5.6 6.1.8-4.5 4.3 1.1 6-5.4-2.9-5.4 2.9 1.1-6-4.5-4.3 6.1-.8z',
        'best_sellers' => 'M8 4h8v7a4 4 0 01-4 4 4 4 0 01-4-4V4zM6 4H4v2a4 4 0 002 3.8M18 4h2v2a4 4 0 01-2 3.8M12 15v3M9 21h6',
        'logo_slider' => 'M3 21h18M6 21V7m0 0L3 10m3-3l3 3m3 11V7m0 0l-3 3m3-3l3 3m3 11V7m0 0l-3 3m3-3l3 3',
        'testimonials' => 'M20.8 8.5a5.5 5.5 0 00-9.3-4A5.5 5.5 0 002.2 8.5c0 4 5.3 8.5 9.3 10.5 4-2 9.3-6.5 9.3-10.5z',
        'app_download' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z',
    ];
    $msg = $messages[$key] ?? 'Fresh farm produce is coming your way soon.';
    $icon = $iconMap[$key] ?? 'M12 21C7 17 5 12 5 8c0-3 2-5 5-5 4 0 8 3 9 7 1 4-1 8-4 10-.4.2-.8.3-1 .4-.6.3-1.5.5-2 .6z';
@endphp
<section class="w-full border-t border-sage-100 py-10 sm:py-14 bg-[#FAFCFA]">
    <div class="mx-auto w-full max-w-[1300px] px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            @if(($sec->title ?? ''))
                <h2 class="font-display text-[24px] font-bold text-anv-800 sm:text-2xl">{{ $sec->title }}</h2>
            @endif
            @if(($sec->subtitle ?? ''))
                <p class="mt-1 text-sm text-charcoal-600/60">{{ $sec->subtitle }}</p>
            @endif
        </div>
        <div class="mx-auto mt-8 flex max-w-md flex-col items-center rounded-2xl border border-dashed border-sage-200 bg-white/70 px-6 py-10 text-center shadow-sm">
            <span class="grid h-16 w-16 place-items-center rounded-full bg-leaf-50 text-anv-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/>
                </svg>
            </span>
            <p class="mt-4 text-sm leading-relaxed text-charcoal-600/80">{{ $msg }}</p>
            <a href="{{ route('shop.categories') }}" class="mt-5 inline-flex items-center gap-2 rounded-full bg-anv-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-anv-700">
                Shop the collection
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12"/></svg>
            </a>
        </div>
    </div>
</section>