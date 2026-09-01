@php $products = $data ?? collect(); @endphp
@include("customer.sections.product-rail", ["products" => $products])
