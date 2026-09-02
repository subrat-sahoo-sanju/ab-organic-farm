@foreach($products as $product)
    @if(! empty($itemClass ?? null))
        <div class="{{ $itemClass }}"><x-product-card :product="$product" /></div>
    @else
        <x-product-card :product="$product" />
    @endif
@endforeach