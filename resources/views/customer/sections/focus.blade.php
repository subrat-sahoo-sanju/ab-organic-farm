@php
    $products = $data ?? collect();
    $tabList = collect($tabs ?? [])->values();
    $firstKey = $tabList->first()['key'] ?? 'all';
    $gridId = 'focus-grid-'.$sec->key;
    $isGhee = ($sec->key ?? '') === 'focus_ghee';
@endphp

@if($tabList->count())
<section class="focus-section {{ $isGhee ? 'focus-section--ghee' : '' }}" x-data="welcomeTabs(@js($gridId), @js($firstKey), @js($tabList))">
    <div class="welcome-header">
        <h1 class="welcome-title">{{ $sec->title }}</h1>
        @if($sec->subtitle)
            <h2 class="welcome-subtitle">{{ $sec->subtitle }}</h2>
        @endif
    </div>

    <div class="welcome-tabs" x-ref="tabsWrapper" @touchstart.passive="onTouchStart" @touchend="onTouchEnd">
        <div class="welcome-tabs-rail" x-ref="rail" role="tablist" aria-label="{{ $sec->title }} categories">
            <template x-for="tab in tabs" :key="tab.key">
<button type="button"
                        role="tab"
                        :aria-selected="active === tab.key"
                        :aria-label="tab.title"
                        @click="pick(tab, $el)"
                        :class="['welcome-tab', { 'welcome-tab--active': active === tab.key }]">
                    <div class="welcome-tab__icon">
                        <img :src="active === tab.key ? (tab.active_icon || tab.inactive_icon) : tab.inactive_icon" alt="" loading="eager" onerror="this.closest('.welcome-tab__icon')?.remove()" class="welcome-tab__img">
                    </div>
                    <span class="welcome-tab__label" x-text="tab.title"></span>
                </button>
            </template>
            <div x-ref="indicator" class="welcome-tab-indicator" aria-hidden="true"></div>
        </div>
        <!-- Mobile scroll hint -->
        <div class="welcome-tab-scroll-hint" x-show="showScrollHint" x-transition.opacity aria-hidden="true">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </div>
    </div>

    <div :id="gridId" class="welcome-grid" role="tabpanel" data-more="{{ $data->count() > 0 ? 1 : 0 }}">
        @forelse($products as $product)
            <div class="welcome-grid-item">
                <x-product-card :product="$product" />
            </div>
        @empty
            <div class="welcome-empty">Products coming soon.</div>
        @endforelse
    </div>

    <div class="welcome-see-all">
        <a class="welcome-see-all-btn"
           :href="activeTab?.see_all || '#'"
           x-text="activeTab?.title ? 'See All ' + activeTab.title + ' →' : 'See All'"></a>
    </div>

    <div data-tab-sentinel class="welcome-sentinel" aria-hidden="true">
        <span class="welcome-spinner"></span>
    </div>
</section>
@endif