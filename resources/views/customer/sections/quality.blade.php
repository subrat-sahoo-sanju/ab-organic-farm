@php
    // Reference "Only Perfect Makes The Cut" — blue title over full-width
    // background image + portrait image carousel with arrows and dots.
    $cfg = $sec->config ?? [];
    $items = collect($cfg['carousel'] ?? [])->filter(fn ($c) => ! empty($c['image']));
    if ($items->isEmpty()) {
        $items = collect($data ?? [])->filter(fn ($d) => ! empty($d->image));
    }
    $titleColor = $cfg['title_color'] ?? '#4199A8';
    $bgDesktop  = $cfg['bg_desktop'] ?? null;
    $bgMobile   = $cfg['bg_mobile'] ?? $bgDesktop;
    $heading    = $sec->title ?: 'Only Perfect Makes The Cut';
@endphp

@if($items->count())
<section class="relative w-full overflow-hidden bg-white">
    @if($bgDesktop)
        <div class="absolute inset-0 hidden sm:block">
            <img src="{{ str_starts_with((string) $bgDesktop, 'http') ? $bgDesktop : asset('storage/'.$bgDesktop) }}"
                 alt="" aria-hidden="true" loading="lazy" class="h-full w-full object-cover">
        </div>
    @endif
    @if($bgMobile)
        <div class="absolute inset-0 sm:hidden">
            <img src="{{ str_starts_with((string) $bgMobile, 'http') ? $bgMobile : asset('storage/'.$bgMobile) }}"
                 alt="" aria-hidden="true" loading="lazy" class="h-full w-full object-cover">
        </div>
    @endif
    <div class="absolute inset-0 bg-gradient-to-b from-black/15 via-black/5 to-black/25"></div>

    <div class="relative z-10 mx-auto max-w-[1200px] px-5 py-12 text-center sm:py-16" x-data="slideCarousel()">
        <h2 class="mx-auto font-display text-[22px] font-bold leading-tight sm:text-[26px]" style="color: {{ $titleColor }}">{{ $heading }}</h2>
        @if($sec->subtitle)
            <p class="mt-1 text-sm text-white/85">{{ $sec->subtitle }}</p>
        @endif

        <div class="relative mt-8">
            <div class="overflow-hidden rounded-[8px]">
                <div class="flex items-center gap-3 transition-transform duration-500 ease-out"
                     :style="`transform: translateX(-${index * (100 / per)}%)`"
                     x-ref="track"
                     @touchstart.passive="handleTouchStart"
                     @touchend="handleTouchEnd">
                    @foreach($items as $item)
                        @php $src = $item['image'] ?? $item->image; @endphp
                        <a href="{{ ($item['url'] ?? '') ?: '#' }}" class="shrink-0">
                            <img src="{{ str_starts_with((string) $src, 'http') ? $src : asset('storage/'.$src) }}"
                                 alt="{{ $item['alt'] ?? $heading }}" loading="lazy"
                                 width="280" height="350"
                                 class="h-auto w-[220px] rounded-[8px] object-cover shadow-[0_4px_20px_rgba(0,0,0,0.25)] sm:w-[280px]">
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Arrows --}}
            <button @click="prev()" :disabled="index === 0" class="absolute -left-3 top-1/2 h-10 w-10 -translate-y-1/2 place-items-center rounded-full bg-white/90 text-black shadow-lg transition hover:bg-white disabled:opacity-40 anv-carousel-arrow sm:hidden sm:-left-3" aria-label="Previous">
                <x-lucide-chevron-left class="h-5 w-5"/>
            </button>
            <button @click="next()" :disabled="index >= maxIndex()" class="absolute -right-3 top-1/2 h-10 w-10 -translate-y-1/2 place-items-center rounded-full bg-white/90 text-black shadow-lg transition hover:bg-white disabled:opacity-40 anv-carousel-arrow sm:hidden sm:-right-3" aria-label="Next">
                <x-lucide-chevron-right class="h-5 w-5"/>
            </button>

            {{-- Dots --}}
            <div class="mt-5 flex items-center justify-center gap-1.5" x-show="maxIndex() > 0" x-cloak>
                <template x-for="(d, i) in Array.from({ length: maxIndex() + 1 })" :key="i">
                    <button @click="index = i"
                            :class="index === i ? 'anv-rev-dot active' : 'anv-rev-dot'"
                            aria-label="Go to slide"></button>
                </template>
            </div>
        </div>
    </div>
</section>
@else
@include('customer.sections._empty-state', ['sec' => $sec])
@endif