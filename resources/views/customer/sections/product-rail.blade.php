@php
    $products = $data ?? collect();
    $horizontal = in_array($sec->key, ['combos', 'superfoods', 'trending', 'best_sellers', 'new_arrivals', 'recommended', 'organic_picks', 'deals']);
    $limit = (int) (($sec->config ?? [])['product_count'] ?? 10);

    // Colourful organic section background per block for visual variety.
    $bgMap = [
        'superfoods'    => 'bg-gradient-to-br from-[#E8F5E9] via-[#F1F8E9] to-[#FFF8E1]',
        'combos'        => 'bg-gradient-to-br from-[#FFF3E0] via-[#FFF8E1] to-[#E8F5E9]',
        'trending'      => 'bg-gradient-to-br from-[#F3E5F5] via-[#FCE4EC] to-[#E8F5E9]',
        'best_sellers'  => 'bg-[#FBF7EE]',
        'new_arrivals'  => 'bg-gradient-to-br from-[#E3F2FD] via-[#E8F5E9] to-[#FFF3E0]',
        'recommended'   => 'bg-[#FAFCFA]',
        'organic_picks' => 'bg-gradient-to-br from-[#E8F5E9] to-[#F1F8E9]',
        'deals'         => 'bg-gradient-to-br from-[#FBE9E7] via-[#FFF3E0] to-[#FFFDE7]',
    ];
    $bg = $bgMap[$sec->key] ?? 'bg-white';
@endphp

@if($products->count())
<section class="w-full border-t border-sage-100 py-8 sm:py-10 {{ $bg }}">
    <div class="mx-auto w-full max-w-[1300px] px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="font-display text-[24px] font-bold text-anv-800 sm:text-2xl">{{ $sec->title }}</h2>
                @if($sec->subtitle)
                    <p class="mt-1 text-sm text-charcoal-600/60">{{ $sec->subtitle }}</p>
                @endif
            </div>
            @if($sec->key === 'superfoods' || $sec->key === 'combos')
                <a href="{{ route('shop.categories') }}"
                   class="hidden h-10 w-10 shrink-0 items-center justify-center rounded-full border border-anv-600 text-anv-600 transition hover:bg-anv-600 hover:text-white sm:flex" aria-label="See all">
                    <x-lucide-arrow-right class="h-4 w-4"/>
                </a>
            @endif
        </div>

        <div class="anv-rail mt-6 pb-2">
            @foreach($products as $product)
                <div class="anv-rail-item w-40 sm:w-52 lg:w-56">
                    <x-product-card :product="$product" />
                </div>
            @endforeach
        </div>
    </div>
</section>
@else
@include('customer.sections._empty-state', ['sec' => $sec])
@endif
