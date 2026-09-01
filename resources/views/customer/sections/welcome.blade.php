@php
    $products = $data ?? collect();
    $cats = $tabs ?? collect();
    $gridId = 'welcome-grid';
    $limit = (int) (($sec->config ?? [])['product_count'] ?? 8);
    $tabIcons = [
        'all' => asset('images/tab-all-active.svg'),
    ];
@endphp

@if($products->count())
<section class="w-full border-t border-sage-100 bg-white py-10 sm:py-14">
    <div class="mx-auto w-full max-w-[1300px] px-4 sm:px-6 lg:px-8">
        {{-- Welcome heading (reference: 'Welcome To Anveshan!' / 'You're One Step Closer to Purity') --}}
        <div class="text-center">
            <h2 class="font-display text-[26px] font-bold text-anv-800 sm:text-3xl">{{ $sec->title }}</h2>
            <p class="mt-1 text-sm text-charcoal-600/60">{{ $sec->subtitle }}</p>
        </div>

        {{-- Tab menu with sliding indicator + icons --}}
        <div x-data="tabGrid('{{ $gridId }}', 'all')" class="mt-8">
            <div class="anv-tabs mx-auto flex max-w-full items-center gap-1 overflow-x-auto pb-0 md:justify-center" style="scrollbar-width:none">
                <button type="button" data-tab-btn-first
                        @click="pick(null, 'all')"
                        :class="active === 'all' ? 'active' : ''"
                        class="anv-tab-btn relative flex items-center gap-2 px-4 py-3 text-sm font-semibold transition">
                    <img src="{{ asset('images/tab-all-active.svg') }}" alt="" class="h-6 w-6 object-contain">
                    All
                </button>
                @foreach($cats as $cat)
                    <button type="button" data-tab-btn
                            @click="pick('{{ route('api.category.products', $cat) }}?limit={{ $limit }}', '{{ $cat->id }}')"
                            :class="active == '{{ $cat->id }}' ? 'active' : ''"
                            class="anv-tab-btn relative flex items-center gap-2 px-4 py-3 text-sm font-semibold transition">
                        <span class="grid h-6 w-6 place-items-center rounded-full bg-leaf-50 text-anv-600">
                            @php echo app(\BladeUI\Icons\Factory::class)->svg('lucide-'.($cat->icon ?? 'leaf'), 'h-4 w-4')->toHtml(); @endphp
                        </span>
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>

            {{-- Product grid --}}
            <div id="{{ $gridId }}" class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 md:gap-4">
                @foreach($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
