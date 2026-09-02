<style>
  /* Reference homepage menu ("Welcome To …") — replicated styles */
  .menu-section-headings{text-align:center;width:100%;margin:0 auto;padding-top:2.75rem}
  .menu-main-heading{font-size:1.9rem;font-weight:700;line-height:1.2;margin:0 0 .5rem;color:#235a49;text-align:center}
  .menu-subheading{font-size:1.9rem;font-weight:400;line-height:1.2;margin:0 0 2rem;color:#235a49;text-align:center}
  .menu-wrapper{position:relative;width:100%;overflow:hidden;padding:0;box-sizing:border-box}
  .menu-collection-container{display:flex!important;overflow-x:auto;overflow-y:hidden;scroll-behavior:smooth;-webkit-overflow-scrolling:touch;scrollbar-width:none;gap:8px;margin:0 auto;justify-content:flex-start;align-items:center;flex-wrap:nowrap;position:relative;padding-bottom:12px;padding-left:20px;padding-right:20px;min-height:80px;max-width:max-content;box-sizing:border-box}
  .menu-nav-item{flex:0 0 auto;display:flex;flex-direction:column;align-items:center;justify-content:center;text-decoration:none;color:inherit;position:relative;min-width:clamp(50px,6vw,70px);max-width:clamp(60px,8vw,90px);white-space:nowrap;transition:transform .2s ease;background:none;border:none;cursor:pointer}
  .menu-nav-item.active{transform:translateY(-1px)}
  .menu-nav-icon{object-fit:contain!important;object-position:center;display:flex;align-items:center;justify-content:center;box-sizing:border-box;aspect-ratio:1/1;width:clamp(35px,4vw,50px);height:clamp(35px,4vw,50px);padding:clamp(4px,.5vw,6px);margin-bottom:clamp(1px,.1vw,2px);transition:all .3s cubic-bezier(.4,0,.2,1)}
  .menu-nav-item.active .menu-nav-icon{transform:scale(1.05)}
  .menu-nav-item strong{text-align:center;color:#495057;overflow:hidden;text-overflow:ellipsis;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;font-size:clamp(11px,1.3vw,13px);font-weight:400;letter-spacing:clamp(.3px,.05vw,.6px);margin:clamp(1px,.1vw,2px) 0;transition:color .2s ease}
  .sliding-indicator{position:absolute;bottom:0;left:0;height:4px;background:#235a49!important;border-radius:2px 2px 0 0;transition:transform .3s cubic-bezier(.4,0,.2,1),width .3s cubic-bezier(.4,0,.2,1),opacity .2s ease;z-index:1;opacity:0;box-shadow:0 -1px 3px #235a4933;min-width:20px!important;visibility:visible!important;pointer-events:none}
  .menu-product-grid{display:flex!important;flex-direction:row!important;overflow-x:auto!important;overflow-y:hidden!important;scroll-behavior:smooth;-webkit-overflow-scrolling:touch;scrollbar-width:none;gap:16px;margin:0;padding:0 20px;justify-content:flex-start!important;align-items:stretch;flex-wrap:nowrap!important;width:100%;box-sizing:border-box;transition:opacity .3s ease}
  .menu-product-grid .menu-grid-item{flex:0 0 180px;width:180px;min-width:180px;max-width:180px;display:block!important;position:relative!important}
</style>

@php
    $products = $data ?? collect();
    $tabList = collect($tabs ?? [])->values();
    $firstKey = $tabList->first()['key'] ?? 'all';
    $gridId = 'welcome-grid';
@endphp

@if($tabList->count())
<section class="w-full border-t border-sage-100 bg-white pb-10 sm:pb-14">
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
        </div>
    </div>
</section>
@endif