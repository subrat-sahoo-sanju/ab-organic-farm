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
                        @click="pick(tab, $el)"
                        :class="['welcome-tab', { 'welcome-tab--active': active === tab.key }]">
                    <div class="welcome-tab__icon">
                        <img :src="active === tab.key ? (tab.active_icon || tab.inactive_icon) : tab.inactive_icon"
                             :alt="tab.title + (active === tab.key ? ' (active)' : '')"
                             loading="eager"
                             class="welcome-tab__img">
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

    <div :id="gridId" class="welcome-grid" role="tabpanel">
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
</section>
@endif

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('welcomeTabs', (gridId, initialKey, tabs) => ({
        active: initialKey,
        loading: false,
        tabs,
        showScrollHint: true,
        touchStartX: 0,
        init() {
            this.$nextTick(() => {
                this.moveToActive();
                this.checkScrollHint();
                this.$refs.rail?.addEventListener('scroll', () => this.checkScrollHint(), { passive: true });
                window.addEventListener('resize', () => this.checkScrollHint());
            });
        },
        checkScrollHint() {
            const rail = this.$refs.rail;
            if (rail) {
                this.showScrollHint = rail.scrollWidth > rail.clientWidth + 10;
            }
        },
        get activeTab() {
            return this.tabs.find(t => t.key === this.active) || this.tabs[0] || null;
        },
        moveToActive() {
            const btn = this.$refs.rail?.querySelector('.welcome-tab--active');
            if (btn) this.placeIndicator(btn);
        },
        placeIndicator(el) {
            const ind = this.$refs.indicator;
            if (!ind || !el) return;
            ind.style.opacity = '1';
            ind.style.width = el.offsetWidth + 'px';
            ind.style.transform = 'translateX(' + el.offsetLeft + 'px)';
        },
        onTouchStart(e) { this.touchStartX = e.changedTouches[0].screenX; },
        onTouchEnd(e) {
            this.touchEndX = e.changedTouches[0].screenX;
            const diff = this.touchStartX - this.touchEndX;
            if (Math.abs(diff) > 40 && this.tabs.length > 1) {
                const currentIdx = this.tabs.findIndex(t => t.key === this.active);
                if (diff > 0 && currentIdx < this.tabs.length - 1) {
                    this.pick(this.tabs[currentIdx + 1], this.$refs.rail?.children[currentIdx + 1]);
                } else if (diff < 0 && currentIdx > 0) {
                    this.pick(this.tabs[currentIdx - 1], this.$refs.rail?.children[currentIdx - 1]);
                }
            }
        },
        async pick(tab, el) {
            if (this.loading || !el || this.active === tab.key) return;
            this.active = tab.key;
            this.placeIndicator(el);
            this.loading = true;
            const grid = document.getElementById(gridId);
            grid?.classList.add('opacity-40', 'pointer-events-none');
            try {
                const r = await fetch(tab.url, { headers: { 'Accept': 'application/json' } });
                const d = await r.json();
                if (grid) grid.innerHTML = d.html;
            } catch (e) {
                if (grid) grid.innerHTML = '<div class="welcome-empty">Couldn\'t load products. Please try again.</div>';
            }
            grid?.classList.remove('opacity-40', 'pointer-events-none');
            this.loading = false;
        },
    }));
});
</script>
@endpush