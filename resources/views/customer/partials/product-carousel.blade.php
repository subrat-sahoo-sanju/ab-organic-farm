@foreach($products as $product)
    <div class="cat-product-card">
        <x-product-card :product="$product" />
    </div>
@endforeach
