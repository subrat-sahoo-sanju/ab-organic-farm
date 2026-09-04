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
<section class="relative w-full overflow-hidden bg-[#f3f3f3] hero-section" x-data="heroSlider({{ count($slides) }})" @touchstart.passive="onTouchStart" @touchend="onTouchEnd">
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
               class="block relative hero-slide">
                {{-- Desktop — full-bleed, fixed hero ratio so ANY upload looks clean --}}
                <div class="hidden sm:block" style="height:min(520px, 32vw)">
                    <img src="{{ $isHttp ? $slide['image'] : asset('storage/'.$slide['image']) }}"
                         alt="{{ $slide['alt'] ?? '' }}"
                         loading="{{ $idx === 0 ? 'eager' : 'lazy' }}"
                         class="h-full w-full object-cover">
                </div>
                {{-- Mobile --}}
                <div class="sm:hidden" style="aspect-ratio: 16 / 9">
                    <img @if($isHttpM) src="{{ $slide['mobile'] }}" @else src="{{ asset('storage/'.$slide['mobile']) }}" @endif
                         alt="{{ $slide['alt'] ?? '' }}"
                         loading="{{ $idx === 0 ? 'eager' : 'lazy' }}"
                         class="h-full w-full object-cover">
                </div>

                {{-- Text overlay (only when show_text is on) --}}
                @if($slide['show_text'])
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                    <div class="absolute inset-x-0 bottom-0 p-4 sm:p-6 md:p-8 lg:p-10">
                        @if($slide['subtitle'])
                            <span class="mb-1.5 inline-block rounded-full bg-[#A9CB92]/90 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-white sm:text-xs">{{ $slide['subtitle'] }}</span>
                        @endif
                        @if($slide['title'])
                            <h2 class="font-display text-lg font-extrabold text-white sm:text-xl md:text-2xl lg:text-3xl">{{ $slide['title'] }}</h2>
                        @endif
                        @if($slide['button_text'])
                            <span class="mt-2 inline-flex items-center gap-1 text-sm font-bold text-[#A9CB92] transition group-hover:text-white sm:text-base">{{ $slide['button_text'] }}<x-lucide-arrow-right class="h-4 w-4"/></span>
                        @endif
                    </div>
                @endif
            </a>
        @endforeach
    </div>

    {{-- Arrows --}}
    <button @click="prev()" class="absolute left-2 top-1/2 -translate-y-1/2 h-10 w-10 place-items-center rounded-full bg-black/30 text-white shadow-lg backdrop-blur transition hover:bg-black/50 sm:left-4 sm:h-11 sm:w-11" aria-label="Previous">
        <x-lucide-chevron-left class="h-5 w-5 sm:h-6 sm:w-6"/>
    </button>
    <button @click="next()" class="absolute right-2 top-1/2 -translate-y-1/2 h-10 w-10 place-items-center rounded-full bg-black/30 text-white shadow-lg backdrop-blur transition hover:bg-black/50 sm:right-4 sm:h-11 sm:w-11" aria-label="Next">
        <x-lucide-chevron-right class="h-5 w-5 sm:h-6 sm:w-6"/>
    </button>

    {{-- Controls: pause + dots --}}
    <div class="absolute bottom-3 inset-x-0 flex flex-col items-center justify-center gap-2 sm:bottom-4 sm:flex-row sm:gap-3">
        <button @click="toggle()" class="grid h-8 w-8 place-items-center rounded-full bg-black/30 text-white backdrop-blur transition hover:bg-black/50" aria-label="Play / pause">
            <x-lucide-pause x-show="!paused" class="h-4 w-4" />
            <x-lucide-play x-show="paused" x-cloak class="h-4 w-4" />
        </button>
        <div class="flex items-center gap-1.5 sm:gap-2">
            @foreach($slides as $s)
                <button @click="go({{ $loop->index }})" :class="active === {{ $loop->index }} ? 'active' : ''" class="anv-hero-dot" aria-label="Slide {{ $loop->iteration }}"></button>
            @endforeach
        </div>
    </div>
</section>
@endif

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('heroSlider', (count) => ({
        active: 0,
        paused: false,
        timer: null,
        touchStartX: 0,
        init() {
            this.play();
        },
        play() {
            clearInterval(this.timer);
            if (this.paused) return;
            this.timer = setInterval(() => {
                this.active = (this.active + 1) % count;
            }, 5000);
        },
        toggle() {
            this.paused = !this.paused;
            if (this.paused) clearInterval(this.timer);
            else this.play();
        },
        next() {
            this.active = (this.active + 1) % count;
            this.play();
        },
        prev() {
            this.active = (this.active - 1 + count) % count;
            this.play();
        },
        go(i) {
            this.active = i;
            this.play();
        },
        onTouchStart(e) {
            this.touchStartX = e.changedTouches[0].screenX;
        },
        onTouchEnd(e) {
            this.touchEndX = e.changedTouches[0].screenX;
            const diff = this.touchStartX - this.touchEndX;
            if (Math.abs(diff) > 50) {
                if (diff > 0) this.next();
                else this.prev();
            }
        },
    }));
});
</script>
@endpush
