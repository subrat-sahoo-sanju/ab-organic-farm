@php
    $title = $section['title'] ?? 'Welcome to AB Organic';
    $subtitle = $section['subtitle'] ?? 'Farm-fresh certified organic products, delivered to your doorstep.';
    $brandName = $category->brand_name ?: 'AB Organic';
@endphp

<section class="w-full bg-white py-10 sm:py-14">
    <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
        <div class="mx-auto mb-6 flex items-center justify-center gap-3">
            <div class="h-px flex-1 bg-gradient-to-r from-transparent to-anv-200"></div>
            <span class="text-xs font-bold uppercase tracking-[0.2em] text-anv-500">{{ $brandName }}</span>
            <div class="h-px flex-1 bg-gradient-to-l from-transparent to-anv-200"></div>
        </div>

        <h2 class="font-display text-2xl font-extrabold text-charcoal-900 sm:text-3xl lg:text-4xl">{{ $title }}</h2>

        <p class="mx-auto mt-4 max-w-2xl text-base leading-relaxed text-charcoal-600/70 sm:text-lg">{{ $subtitle }}</p>

        <div class="mt-6 flex items-center justify-center gap-4 text-xs font-semibold text-anv-600">
            <span class="flex items-center gap-1.5">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
                100% Certified Organic
            </span>
            <span class="h-1 w-1 rounded-full bg-anv-300"></span>
            <span class="flex items-center gap-1.5">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
                Lab Tested
            </span>
            <span class="h-1 w-1 rounded-full bg-anv-300"></span>
            <span class="flex items-center gap-1.5">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
                Farm to Table
            </span>
        </div>
    </div>
</section>
