@include('customer.sections.product-rail', [
    'title' => $sec->title,
    'subtitle' => $sec->subtitle,
    'eyebrow' => 'Hot Prices',
    'products' => $data ?? collect(),
    'link' => route('shop.categories'),
    'linkLabel' => 'See All Deals',
])