@php
    // Full-width "Native Ingredients. No Substitutes." banner — admin image uploadable.
    $cfg = $sec->config ?? [];
    $imgs = $cfg['images'] ?? [];
    $fallback = $data ?? collect();

    $desktop = $imgs['desktop'] ?? null;
    $mobile  = $imgs['mobile'] ?? null;
    if (! $desktop) {
        $first = $fallback->first();
        $desktop = $first->image ?? $first->primaryImage?->path ?? null;
        $mobile = null;
    }
    $desktopSrc = $desktop ? (str_starts_with((string)$desktop, 'http') ? $desktop : asset('storage/'.$desktop)) : asset('images/hero-desktop.jpg');
    $mobileSrc  = $mobile ? (str_starts_with((string)$mobile, 'http') ? $mobile : asset('storage/'.$mobile)) : asset('images/hero-mobile.jpg');
@endphp

<section class="relative w-full overflow-hidden">
    {{-- Desktop image --}}
    <div class="hidden sm:block">
        <img src="{{ $desktopSrc }}" alt="{{ $imgs['alt'] ?? $sec->title }}" class="h-[420px] w-full object-cover lg:h-[520px]" loading="lazy">
    </div>
    {{-- Mobile image --}}
    <div class="sm:hidden">
        <img src="{{ $mobileSrc }}" alt="{{ $imgs['alt'] ?? $sec->title }}" class="h-[260px] w-full object-cover" loading="lazy">
    </div>
    {{-- Overlay caption --}}
    <div class="absolute inset-0 flex items-center bg-gradient-to-r from-black/55 via-black/25 to-transparent">
        <div class="mx-auto w-full max-w-[1300px] px-4 sm:px-6 lg:px-8">
            <h2 class="font-display max-w-xl text-2xl font-bold text-white sm:text-3xl lg:text-4xl">{{ $sec->title }}</h2>
            @if($sec->subtitle)
                <p class="mt-2 max-w-md text-sm text-white/85 sm:text-base">{{ $sec->subtitle }}</p>
            @endif
        </div>
    </div>
</section>
