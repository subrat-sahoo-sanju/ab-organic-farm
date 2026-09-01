@props(['title', 'subtitle' => null, 'eyebrow' => null, 'link' => null, 'linkLabel' => 'See All', 'products', 'columns' => 6])

<section class="py-10">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
        @include('customer.sections._header', [
            'title' => $title,
            'subtitle' => $subtitle,
            'eyebrow' => $eyebrow,
            'link' => $link,
            'linkLabel' => $linkLabel,
        ])

        <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-{{ $columns }} sm:gap-4">
            @foreach($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>