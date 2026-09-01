@php
    // Full-width "Only Perfect Makes The Cut" banner — admin image uploadable.
    $cfg = $sec->config ?? [];
    $imgs = $cfg['images'] ?? [];
    $fallback = $data ?? collect();

    $desktop = $imgs['desktop'] ?? null;
    $mobile  = $imgs['mobile'] ?? null;
    if (! $desktop) {
        $first = $fallback->first();
        $desktop = $first->image ?? $first->desktop_image ?? null;
        $mobile = $first->mobile_image ?? null;
    }
    $desktopSrc = $desktop ? (str_starts_with((string)$desktop, 'http') ? $desktop : asset('storage/'.$desktop)) : asset('images/hero-desktop.jpg');
    $mobileSrc  = $mobile ? (str_starts_with((string)$mobile, 'http') ? $mobile : asset('storage/'.$mobile)) : asset('images/hero-mobile.jpg');
@endphp

<section class="relative w-full overflow-hidden bg-[#0B3B30]">
    <div class="hidden sm:block">
        <img src="{{ $desktopSrc }}" alt="{{ $imgs['alt'] ?? $sec->title }}" class="h-[360px] w-full object-cover opacity-95 lg:h-[460px]" loading="lazy">
    </div>
    <div class="sm:hidden">
        <img src="{{ $mobileSrc }}" alt="{{ $imgs['alt'] ?? $sec->title }}" class="h-[240px] w-full object-cover opacity-95" loading="lazy">
    </div>
    <div class="absolute inset-0 flex items-center justify-center text-center">
        <div class="mx-auto w-full max-w-[1300px] px-4 sm:px-6 lg:px-8">
            <p class="mx-auto mb-2 inline-flex items-center gap-2 rounded-full bg-gold-400/90 px-4 py-1 text-[11px] font-bold uppercase tracking-widest text-[#0B3B30]">
                <svg viewBox="0 0 20 20" fill="currentColor" class="h-3 w-3"><path d="M6.6 10.8l2 2 4.8-4.8-1.1-1.1-3.7 3.7-.9-.9z"/></svg>
                Hand-picked & tested
            </p>
            <h2 class="font-display text-2xl font-bold text-white sm:text-3xl lg:text-4xl">{{ $sec->title }}</h2>
            @if($sec->subtitle)
                <p class="mt-2 text-sm text-white/85 sm:text-base">{{ $sec->subtitle }}</p>
            @endif
        </div>
    </div>
</section>
