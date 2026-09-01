@php
    // Reference-style full-bleed hero with autoplay slideshow.
    $cfg = $sec->config ?? [];
    $imgs = $cfg['images'] ?? [];
    $banners = $data ?? collect();

    // Build slides from admin config first, else seeded hero banners.
    $slides = [];
    if (! empty($imgs['desktop'])) {
        $slides[] = [
            'image'   => $imgs['desktop'],
            'mobile'  => $imgs['mobile'] ?? $imgs['desktop'],
            'alt'     => $imgs['alt'] ?? $sec->title,
            'url'     => $imgs['url'] ?? '',
        ];
    } else {
        foreach ($banners as $b) {
            $slides[] = [
                'image'  => $b->desktop_image,
                'mobile' => $b->mobile_image ?? $b->desktop_image,
                'alt'    => $b->alt_text ?? $sec->title ?? '',
                'url'    => $b->button_url ?? '',
            ];
        }
    }
    if (empty($slides)) {
        $slides[] = ['image' => 'hero-desktop.jpg', 'mobile' => 'hero-mobile.jpg', 'alt' => $sec->title ?? 'Hero', 'url' => route('shop.categories')];
    }

    $urls = array_map(fn ($s) => $s['url'] ?? '', $slides);
@endphp

@if(count($slides))
<section class="relative w-full overflow-hidden" x-data="heroSlider({{ count($slides) }})">
    <div class="relative">
        @foreach($slides as $idx => $slide)
            <a href="{{ $slide['url'] ?: route('shop.categories') }}"
               x-show="active === {{ $idx }}"
               x-transition:enter="transition-opacity duration-700"
               x-transition:enter-start="opacity-0"
               x-transition:enter-end="opacity-100"
               class="block">
                <img src="{{ str_starts_with((string)$slide['image'], 'http') ? $slide['image'] : asset('storage/'.$slide['image']) }}"
                     alt="{{ $slide['alt'] ?? '' }}"
                     loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                     class="h-auto min-h-[280px] w-full object-cover sm:min-h-[420px] lg:min-h-[560px]">
            </a>
        @endforeach
    </div>

    {{-- Arrows (desktop) --}}
    <button @click="prev()" class="absolute left-4 top-1/2 hidden -translate-y-1/2 h-11 w-11 place-items-center rounded-full bg-black/30 text-white shadow-lg backdrop-blur transition hover:bg-black/50 sm:grid" aria-label="Previous">
        <x-lucide-chevron-left class="h-5 w-5"/>
    </button>
    <button @click="next()" class="absolute right-4 top-1/2 hidden -translate-y-1/2 h-11 w-11 place-items-center rounded-full bg-black/30 text-white shadow-lg backdrop-blur transition hover:bg-black/50 sm:grid" aria-label="Next">
        <x-lucide-chevron-right class="h-5 w-5"/>
    </button>

    {{-- Controls: pause + dots --}}
    <div class="absolute bottom-4 inset-x-0 flex items-center justify-center gap-3">
        <button @click="toggle()" class="grid h-8 w-8 place-items-center rounded-full bg-black/30 text-white backdrop-blur transition hover:bg-black/50" aria-label="Play / pause">
            <x-lucide-pause x-show="!paused" class="h-4 w-4" />
            <x-lucide-play x-show="paused" x-cloak class="h-4 w-4" />
        </button>
        <div class="flex items-center gap-1.5">
            @foreach($slides as $s)
                <button @click="go({{ $loop->index }})" :class="active === {{ $loop->index }} ? 'active' : ''" class="anv-hero-dot" aria-label="Slide {{ $loop->iteration }}"></button>
            @endforeach
        </div>
    </div>
</section>
@endif
