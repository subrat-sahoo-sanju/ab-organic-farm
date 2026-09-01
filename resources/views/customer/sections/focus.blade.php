@php
    $cats = $data['categories'] ?? collect();
    $tabProducts = $data['tabProducts'] ?? [];
    $limit = $data['limit'] ?? 8;
    $gridId = 'focus-grid-'.$sec->key;
    $first = $cats->first();
@endphp

@if($cats->count() && $first)
<section class="w-full border-t border-sage-100 py-10 sm:py-14 {{ ($sec->key ?? '') === 'focus_ghee' ? 'bg-[#FBF7EE]' : 'bg-leaf-50' }}">
    <div class="mx-auto w-full max-w-[1300px] px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="font-display text-[26px] font-bold text-anv-800 sm:text-3xl">{{ $sec->title }}</h2>
            <p class="mt-1 text-sm text-charcoal-600/60">{{ $sec->subtitle }}</p>
        </div>

        <div x-data="tabGrid('{{ $gridId }}', '{{ $first->id }}')" class="mt-8">
            <div class="anv-tabs mx-auto flex max-w-full items-center gap-1 overflow-x-auto border-b border-sage-100 pb-0 md:justify-center" style="scrollbar-width:none">
                @foreach($cats as $cat)
                    <button type="button" data-tab-btn
                            @click="pick('{{ route('api.category.products', $cat) }}?limit={{ $limit }}', '{{ $cat->id }}')"
                            :class="active == '{{ $cat->id }}' ? 'active' : ''"
                            class="anv-tab-btn relative flex items-center gap-2 px-4 py-3 text-sm font-semibold transition">
                        @if($cat->image_path)
                            <img src="{{ asset('storage/'.$cat->image_path) }}" alt="" class="h-6 w-6 rounded-full object-cover">
                        @else
                            <span class="grid h-6 w-6 place-items-center rounded-full bg-leaf-50 text-anv-600">
                                @php echo app(\BladeUI\Icons\Factory::class)->svg('lucide-'.($cat->icon ?? 'leaf'), 'h-4 w-4')->toHtml(); @endphp
                            </span>
                        @endif
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>

            <div id="{{ $gridId }}" class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 md:gap-4">
                @foreach(($tabProducts[$first->id] ?? collect()) as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
