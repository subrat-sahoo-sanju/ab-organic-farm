@php
    $title = $section['title'] ?? '';
    $subtitle = $section['subtitle'] ?? '';
    $image = $section['config']['image'] ?? '';
    $ctaText = $section['config']['cta_text'] ?? 'Shop Now';
    $ctaUrl = $section['config']['cta_url'] ?? '#';
    $bgColor = $section['config']['bg_color'] ?? '#00584b';
    $textColor = $section['config']['text_color'] ?? '#ffffff';
@endphp

<section class="w-full overflow-hidden" style="background:{{ $bgColor }}">
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        @if($image)
            <img src="{{ asset('storage/'.$image) }}" alt="{{ $title }}" class="absolute inset-0 h-full w-full object-cover" loading="lazy">
            <div class="absolute inset-0 bg-gradient-to-r from-black/50 via-black/20 to-transparent"></div>
        @endif

        <div class="relative flex flex-col items-center justify-center py-14 text-center sm:py-20 lg:py-24" style="color:{{ $textColor }}">
            @if($title)
                <h2 class="font-display text-2xl font-extrabold sm:text-3xl lg:text-4xl drop-shadow-lg">{{ $title }}</h2>
            @endif
            @if($subtitle)
                <p class="mt-3 max-w-xl text-sm opacity-90 sm:text-base drop-shadow">{{ $subtitle }}</p>
            @endif
            @if($ctaText && $ctaUrl !== '#')
                <a href="{{ $ctaUrl }}" class="mt-6 inline-flex items-center gap-2 rounded-full bg-white px-7 py-3.5 text-sm font-bold shadow-xl transition hover:shadow-2xl hover:scale-[1.03]" style="color:{{ $bgColor }}">
                    {{ $ctaText }}
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
            @endif
        </div>
    </div>
</section>
