@include('customer.sections.product-rail', [
    'title' => $sec->title,
    'subtitle' => $sec->subtitle,
    'eyebrow' => 'Most Loved',
    'products' => $data ?? collect(),
    'link' => route('shop.categories'),
    'linkLabel' => 'See All Trending',
])