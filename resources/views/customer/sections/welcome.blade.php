<style>
  .no-active-underline .anv-tab-btn.active:after { display: none; }
  .anv-tab-indicator { transition: transform .3s cubic-bezier(.4, 0, .2, 1), width .3s cubic-bezier(.4, 0, .2, 1), opacity .2s ease; }
</style>

@php
    $products = $data ?? collect();
    $tabList = collect($tabs ?? [])->values();
    $firstKey = $tabList->first()['key'] ?? 'all';
    $gridId = 'welcome-grid';
@endphp

@if($tabList->count())
<section class="w-full border-t border-sage-100 bg-white py-10 sm:py-14">
    <div class="mx-auto w-full max-w-[1300px] px-4 sm:px-6 lg:px-8">
        {{-- Welcome heading (reference: "Welcome To Anveshan!" / "You're One Step Closer to Purity") --}}
        <div class="text-center">
            <h1 class="font-display text-[26px] font-bold text-anv-800 sm:text-3xl">{{ $sec->title }}</h1>
            <h2 class="mt-1 text-sm text-charcoal-600/60">{{ $sec->subtitle }}</h2>
        </div>

        {{-- Icon tab rail + sliding indicator + lazy product grid --}}
        <div class="mt-8"
             x-data="welcomeTabs(@js($gridId), @js($firstKey), @js($tabList))">
            <div class="relative mx-auto max-w-5xl">
                <div x-ref="rail"
                     class="anv-tabs no-active-underline mx-auto flex w-full items-center gap-1 overflow-x-auto px-1 pb-2 md:justify-center"
                     style="scrollbar-width:none">
                    <template x-for="tab in tabs" :key="tab.key">
                        <button type="button"
                                @click="pick(tab, $el)"
                                :class="active === tab.key ? 'active' : ''"
                                class="anv-tab-btn relative flex shrink-0 items-center gap-2 rounded-full px-4 py-2.5 text-sm font-semibold transition hover:text-anv-700">
                            <img :src="active === tab.key ? (tab.active_icon || tab.inactive_icon) : tab.inactive_icon"
                                 :alt="tab.title"
                                 loading="eager"
                                 class="h-6 w-6 object-contain">
                            <strong x-text="tab.title"></strong>
                        </button>
                    </template>
                </div>
                <div x-ref="indicator"
                     class="anv-tab-indicator pointer-events-none absolute bottom-0 h-[3px] rounded-full bg-anv-700"
                     style="left:0;width:0;opacity:0"></div>
            </div>

            {{-- Product grid --}}
            <div id="{{ $gridId }}" class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 md:gap-4">
                @forelse($products as $product)
                    <x-product-card :product="$product" />
                @empty
                    <div class="col-span-full py-10 text-center text-sm text-charcoal-600/50">Products coming soon.</div>
                @endforelse
            </div>
        </div>
    </div>
</section>
@endif