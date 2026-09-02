@php
    $title = $section['title'] ?? 'Featured Products';
    $subtitle = $section['subtitle'] ?? '';
    $products = $sectionData ?? collect();
@endphp

@if($products->count())
<section class="w-full bg-[#fafcfa] py-10 sm:py-14">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-8 text-center">
            <h2 class="font-display text-2xl font-extrabold text-charcoal-900 sm:text-3xl">{{ $title }}</h2>
            @if($subtitle)
                <p class="mt-2 text-sm text-charcoal-600/60">{{ $subtitle }}</p>
            @endif
        </div>

        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 sm:gap-4">
            @foreach($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>
@endif
