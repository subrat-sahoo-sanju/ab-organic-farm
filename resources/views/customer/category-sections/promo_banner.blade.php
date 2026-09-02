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
        @endif

        <div class="relative flex flex-col items-center justify-center py-12 text-center sm:py-16 lg:py-20" style="color:{{ $textColor }}">
            @if($title)
                <h2 class="font-display text-2xl font-extrabold sm:text-3xl lg:text-4xl">{{ $title }}</h2>
            @endif
            @if($subtitle)
                <p class="mt-3 max-w-xl text-sm opacity-85 sm:text-base">{{ $subtitle }}</p>
            @endif
            @if($ctaText && $ctaUrl !== '#')
                <a href="{{ $ctaUrl }}" class="mt-6 inline-flex items-center gap-2 rounded-full bg-white px-6 py-3 text-sm font-bold shadow-lg transition hover:shadow-xl hover:scale-[1.02]" style="color:{{ $bgColor }}">
                    {{ $ctaText }}
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
            @endif
        </div>
    </div>
</section>
