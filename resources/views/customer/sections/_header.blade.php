@props(['title', 'subtitle' => null, 'eyebrow' => null, 'link' => null, 'linkLabel' => 'See All', 'align' => 'center'])

<div class="{{ $align === 'center' ? 'text-center' : 'flex items-end justify-between gap-4' }}">
    <div class="{{ $align === 'center' ? '' : '' }}">
        @if($eyebrow)
            <span class="inline-flex items-center gap-1.5 text-[11px] font-extrabold uppercase tracking-[0.18em] text-forest-600/70">
                <span class="h-px w-6 bg-forest-600/40"></span>{{ $eyebrow }}<span class="h-px w-6 bg-forest-600/40"></span>
            </span>
        @endif
        <h2 class="mt-2 font-display text-2xl font-bold tracking-tight text-charcoal-900 sm:text-3xl">{{ $title }}</h2>
        @if($subtitle)
            <p class="mx-auto mt-1.5 max-w-xl text-sm text-charcoal-600/60">{{ $subtitle }}</p>
        @endif
    </div>
    @if($link)
        <a href="{{ $link }}" class="hidden shrink-0 items-center gap-1 rounded-full border border-forest-600/30 bg-white px-4 py-2 text-xs font-bold text-forest-700 transition hover:bg-forest-600 hover:text-white sm:inline-flex">
            {{ $linkLabel }}<x-lucide-arrow-right class="h-3.5 w-3.5" />
        </a>
    @endif
</div>
@if($link)
    <div class="mt-3 text-center sm:hidden">
        <a href="{{ $link }}" class="inline-flex items-center gap-1 rounded-full border border-forest-600/30 bg-white px-4 py-2 text-xs font-bold text-forest-700">{{ $linkLabel }}<x-lucide-arrow-right class="h-3.5 w-3.5" /></a>
    </div>
@endif