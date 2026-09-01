@php
    $products = $data ?? collect();
@endphp

@if($products->count())
<section class="w-full bg-white">
    <div class="mx-auto w-full max-w-[1300px] px-4 py-10 sm:px-6 sm:py-12">
        <h2 class="text-[22px] font-bold leading-tight text-[#242424] sm:text-[26px] font-display">
            <strong>{{ $sec->title ?: 'Explore our Superfoods' }}</strong>
        </h2>
        @if($sec->subtitle)
            <p class="mt-1 text-sm text-[#666666]">{{ $sec->subtitle }}</p>
        @endif

        <div class="anv-rail mt-6 pb-1" style="gap: 12px; scroll-snap-type: x mandatory;">
            @foreach($products as $product)
                <div class="w-[190px] shrink-0 scroll-snap-align-start md:w-[240px]">
                    <x-product-card :product="$product" />
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif