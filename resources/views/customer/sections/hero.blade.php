@php
    // Hero slider — slides come ONLY from active hero-placement banners
    // managed in Admin → Marketing → Banners. No fallback to old config.
    $banners = $data ?? collect();

    $slides = collect($banners)->filter(fn ($b) => $b->desktop_image)->map(fn ($b) => [
        'image'      => $b->desktop_image,
        'mobile'     => $b->mobile_image ?? $b->desktop_image,
        'alt'        => $b->title ?? ($sec->title ?? ''),
        'url'        => $b->button_url ?? '',
        'title'      => $b->title ?? '',
        'subtitle'   => $b->subtitle ?? '',
        'button_text'=> $b->button_text ?? '',
        'show_text'  => (bool) ($b->show_text ?? true),
    ])->values()->all();
@endphp

@if(count($slides))
<section class="relative w-full overflow-hidden bg-[#f3f3f3]" x-data="heroSlider({{ count($slides) }})">
    <div class="relative">
        @foreach($slides as $idx => $slide)
            @php
                $isHttp = str_starts_with((string) $slide['image'], 'http') || str_starts_with((string) $slide['image'], '/');
                $isHttpM = str_starts_with((string) $slide['mobile'], 'http') || str_starts_with((string) $slide['mobile'], '/');
            @endphp
            <a href="{{ $slide['url'] ?: route('shop.categories') }}"
               x-show="active === {{ $idx }}"
               x-transition:enter="transition-opacity duration-700"
               x-transition:enter-start="opacity-0"
               x-transition:enter-end="opacity-100"
               class="block relative">
                {{-- Desktop — full-bleed --}}
                <img src="{{ $isHttp ? $slide['image'] : asset('storage/'.$slide['image']) }}"
                     alt="{{ $slide['alt'] ?? '' }}"
                     loading="{{ $idx === 0 ? 'eager' : 'lazy' }}"
                     class="hidden w-full object-cover sm:block">
                {{-- Mobile --}}
                <img @if($isHttpM) src="{{ $slide['mobile'] }}" @else src="{{ asset('storage/'.$slide['mobile']) }}" @endif
                     alt="{{ $slide['alt'] ?? '' }}"
                     loading="{{ $idx === 0 ? 'eager' : 'lazy' }}"
                     class="w-full object-cover sm:hidden">

                {{-- Text overlay (only when show_text is on) --}}
                @if($slide['show_text'])
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                    <div class="absolute inset-x-0 bottom-0 p-5 sm:p-8 md:p-10">
                        @if($slide['subtitle'])
                            <span class="mb-1.5 inline-block rounded-full bg-[#74C9A1]/90 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-white sm:text-xs">{{ $slide['subtitle'] }}</span>
                        @endif
                        @if($slide['title'])
                            <h2 class="font-display text-xl font-extrabold text-white sm:text-2xl md:text-3xl">{{ $slide['title'] }}</h2>
                        @endif
                        @if($slide['button_text'])
                            <span class="mt-2 inline-flex items-center gap-1 text-sm font-bold text-[#74C9A1] transition group-hover:text-white sm:text-base">{{ $slide['button_text'] }}<x-lucide-arrow-right class="h-4 w-4"/></span>
                        @endif
                    </div>
                @endif
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
