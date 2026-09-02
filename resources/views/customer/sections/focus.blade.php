@php
    $products = $data ?? collect();
    $tabList = collect($tabs ?? [])->values();
    $firstKey = $tabList->first()['key'] ?? 'all';
    $gridId = 'focus-grid-'.$sec->key;
    $isGhee = ($sec->key ?? '') === 'focus_ghee';
@endphp

@if($tabList->count())
<style>
  .menu-scrollbar-row{display:flex;align-items:center;gap:16px;padding:15px 20px;margin-top:10px;width:100%;box-sizing:border-box}
  .menu-scrollbar-track{flex:1;height:8px;background:#f1f1f1;border-radius:4px;position:relative;overflow:hidden;min-width:0}
  .menu-scrollbar-thumb{height:100%;background:#404040!important;border-radius:4px;position:absolute;top:0;left:0;transition:background-color .2s ease,left .05s linear;min-width:20px;cursor:pointer;display:block!important;opacity:1!important;visibility:visible!important}
  .menu-see-all-button{display:flex;align-items:center;gap:8px;padding:8px 16px;background:transparent;border:1.5px solid #404040;border-radius:20px;text-decoration:none;color:#404040;font-size:14px;font-weight:500;white-space:nowrap;transition:all .2s ease;flex-shrink:0}
  .menu-see-all-button:hover{background:#235a49;border-color:#235a49;color:#fff}
  .menu-product-grid .menu-grid-item{flex:0 0 180px;width:180px;min-width:180px;max-width:180px;display:block;position:relative}
  @media (min-width:750px){.menu-product-grid .menu-grid-item{flex-basis:220px;width:220px;min-width:220px;max-width:220px}}
  @media (min-width:1025px){.menu-product-grid .menu-grid-item{flex-basis:260px;width:260px;min-width:260px;max-width:260px}}
  @media (max-width:767px){.menu-scrollbar-row{padding:12px 16px;gap:12px}.menu-see-all-button{padding:6px 12px;font-size:13px}}
</style>
<section class="w-full border-t border-sage-100 py-10 sm:py-14 {{ $isGhee ? 'bg-[#FBF7EE]' : 'bg-white' }}">
    <div class="mx-auto w-full max-w-[1300px] px-4 sm:px-6 lg:px-8">
        <div class="menu-section-headings">
            <h1 class="menu-main-heading">{{ $sec->title }}</h1>
            <h2 class="menu-subheading">{{ $sec->subtitle }}</h2>
        </div>

        <div x-data="welcomeTabs(@js($gridId), @js($firstKey), @js($tabList))">
            <div class="menu-wrapper">
                <div x-ref="rail" class="menu-collection-container">
                    <template x-for="tab in tabs" :key="tab.key">
                        <button type="button"
                                @click="pick(tab, $el)"
                                :class="active === tab.key ? 'active' : ''"
                                class="menu-nav-item">
                            <img :src="active === tab.key ? (tab.active_icon || tab.inactive_icon) : tab.inactive_icon"
                                 :alt="tab.title"
                                 loading="eager"
                                 class="menu-nav-icon">
                            <strong x-text="tab.title"></strong>
                        </button>
                    </template>
                    <div x-ref="indicator" class="sliding-indicator"></div>
                </div>
            </div>

            <div id="{{ $gridId }}" class="menu-product-grid">
                @forelse($products as $product)
                    <div class="menu-grid-item">
                        <x-product-card :product="$product" />
                    </div>
                @empty
                    <div class="w-full py-10 text-center text-sm text-charcoal-600/50">Products coming soon.</div>
                @endforelse
            </div>

            <div class="menu-scrollbar-row">
                <div class="menu-scrollbar-track">
                    <div
                        x-ref="sbThumb"
                        class="menu-scrollbar-thumb"
                        x-init="initScrollbar($refs.sbThumb)"
                        x-effect="syncScrollbar()"></div>
                </div>
                <a class="menu-see-all-button"
                   :href="activeTab?.see_all || '#'"
                   x-text="activeTab?.title ? 'See All ' + activeTab.title + ' →' : 'See All'"></a>
            </div>
        </div>
    </div>
</section>
@endif