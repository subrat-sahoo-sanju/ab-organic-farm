@php
    $title = $section['title'] ?? 'Welcome to '.setting('store.name');
    $subtitle = $section['subtitle'] ?? 'Farm-fresh certified organic products, delivered to your doorstep.';
    $brandName = $category->brand_name ?: setting('store.name');
    $tabCategories = collect($sectionTabs ?? []);
    $allProducts = $sectionData ?? collect();
@endphp

<section class="w-full bg-white py-10 sm:py-14">
    <div class="mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-8">
        {{-- Section heading --}}
        <div class="mb-8 text-center">
            <div class="mb-3 flex items-center justify-center gap-3">
                <div class="h-px flex-1 max-w-[80px] bg-gradient-to-r from-transparent to-anv-200"></div>
                <span class="text-[11px] font-bold uppercase tracking-[0.2em] text-anv-500">{{ $brandName }}</span>
                <div class="h-px flex-1 max-w-[80px] bg-gradient-to-l from-transparent to-anv-200"></div>
            </div>
            <h2 class="font-display text-2xl font-extrabold text-charcoal-900 sm:text-3xl lg:text-4xl">{{ $title }}</h2>
            @if($subtitle)
                <p class="mx-auto mt-3 max-w-2xl text-sm leading-relaxed text-charcoal-600/60 sm:text-base">{{ $subtitle }}</p>
            @endif
        </div>

        @if($tabCategories->count())
        {{-- Admin-configured tabs; each tab has its own product rail, switched client-side --}}
        <div x-data="welcomeRail('cat-welcome-{{ rand(1000,9999) }}', '{{ $tabCategories->first()['key'] ?? '' }}')">
            <div class="cat-welcome-tabs" x-ref="rail">
                @foreach($tabCategories as $t)
                    <button type="button"
                        @click="pick('{{ $t['key'] }}', $el)"
                        :class="active === '{{ $t['key'] }}' ? 'active' : ''"
                        class="cat-tab-item">
                        <div class="cat-tab-icon">
                            <img src="{{ $t['inactive_icon'] }}" :src="active === '{{ $t['key'] }}' ? '{{ $t['active_icon'] ?? $t['inactive_icon'] }}' : '{{ $t['inactive_icon'] }}'" alt="" loading="eager" onerror="this.closest('.cat-tab-icon')?.remove()">
                        </div>
                        <span class="cat-tab-label">{{ $t['title'] }}</span>
                    </button>
                @endforeach
            </div>
            <div x-ref="indicator" class="h-0.5 bg-anv-600 rounded-full transition-all duration-300" style="opacity:0;position:relative;margin-top:6px;left:0"></div>

            {{-- Product rails (one per tab) --}}            @foreach($tabCategories as $t)
                <div x-show="active === '{{ $t['key'] }}'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     id="cat-rail-{{ $loop->index }}" class="cat-products-scroll mt-6">
                    @php $tabProducts = $t['products'] ?? collect(); @endphp
                    @forelse($tabProducts as $product)
                        <div class="cat-product-card">
                            <x-product-card :product="$product" />
                        </div>
                    @empty
                        <div class="w-full py-10 text-center text-sm text-charcoal-600/50">No products in this tab yet.</div>
                    @endforelse
                </div>
            @endforeach
        </div>
        @else
            {{-- Fallback: no tabs, just products --}}
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 sm:gap-4">
                @forelse($allProducts as $product)
                    <x-product-card :product="$product" />
                @empty
                    <div class="col-span-full py-10 text-center text-sm text-charcoal-600/50">Products coming soon.</div>
                @endforelse
            </div>
        @endif

        {{-- Trust pills --}}
        @php $trustPills = setting_json('display.trust_pills', []); @endphp
        @if(count($trustPills))
        <div class="mt-8 flex flex-wrap items-center justify-center gap-4 text-xs font-semibold text-anv-600">
            @foreach($trustPills as $i => $pill)
                @if($i > 0)<span class="h-1 w-1 rounded-full bg-anv-300"></span>@endif
                <span class="flex items-center gap-1.5">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
                    {{ $pill['text'] }}
                </span>
            @endforeach
        </div>
        @endif
    </div>
</section>
