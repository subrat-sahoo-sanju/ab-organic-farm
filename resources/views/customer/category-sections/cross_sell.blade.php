@php
    $title = $section['title'] ?? 'You May Also Like';
    $subtitle = $section['subtitle'] ?? 'Complete your organic collection';
    $products = $sectionData ?? collect();
    $tabCategories = $sectionTabs ?? collect();
    $hasTabs = count($tabCategories) > 0;
@endphp

@if($products->count() || $hasTabs)
<section class="w-full border-t border-sage-100 bg-white py-10 sm:py-14">
    <div class="mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-8">
        <div class="mb-8 text-center">
            <h2 class="font-display text-2xl font-extrabold text-charcoal-900 sm:text-3xl">{{ $title }}</h2>
            @if($subtitle)
                <p class="mt-2 text-sm text-charcoal-600/60">{{ $subtitle }}</p>
            @endif
        </div>

        @if($hasTabs)
            <div x-data="welcomeTabs('crosssell-grid', '{{ $tabCategories->first()['key'] ?? '' }}', @js($tabCategories->toArray()))">
                <div class="cat-welcome-tabs" x-ref="rail">
                    <template x-for="tab in tabs" :key="tab.key">
                        <button type="button" @click="pick(tab, $el)" :class="active === tab.key ? 'active' : ''" class="cat-tab-item">
                            <div class="cat-tab-icon">
                                <img :src="active === tab.key ? (tab.active_icon || tab.inactive_icon) : tab.inactive_icon" :alt="tab.title" loading="eager">
                            </div>
                            <span class="cat-tab-label" x-text="tab.title"></span>
                        </button>
                    </template>
                </div>

                <div id="crosssell-grid" class="cat-products-scroll mt-6">
                    @forelse($products as $product)
                        <div class="cat-product-card">
                            <x-product-card :product="$product" />
                        </div>
                    @empty
                        <div class="w-full py-10 text-center text-sm text-charcoal-600/50">Products coming soon.</div>
                    @endforelse
                </div>
            </div>
        @else
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 sm:gap-4">
                @foreach($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        @endif
    </div>
</section>
@endif
