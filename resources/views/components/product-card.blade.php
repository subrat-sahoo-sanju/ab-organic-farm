@php
    $primary = $product->primaryImage;
    $img     = $primary?->path;
    $hover   = $product->hoverImage();
    $hoverPath = $hover?->path ?? null;
    $variants = $product->relationLoaded('activeVariants')
        ? $product->activeVariants
        : $product->activeVariants()->limit(5)->get();
    $default   = $product->defaultVariant;
    $variant   = $default ?? $variants->first();
    $inStock   = $variants->contains(fn ($v) => $v->inventory && $v->inventory->available() > 0);
    $available = $variant && $variant->inventory && $variant->inventory->available() > 0;
    $discount  = $product->discountPercent();
    $rating    = $product->reviews_avg_rating ?? (float) ($product->rating_avg ?? 0);
    $reviews   = $product->reviews_count ?? 0;
    $sold      = (int) ($product->sold_count ?? 0);
    $badge     = $product->displayBadge();
    $unit      = $variant?->unit_label ?: ($variant && $variant->weight_grams ? $variant->weight_grams.' gm' : null);
    $multi     = $variants->count() > 1;

    $variantsJson = $variants->values()->map(fn ($v) => [
        'id'        => $v->id,
        'label'     => $v->name ?: ($v->weight_grams ? $v->weight_grams.' gm' : ''),
        'image'     => $img ? asset('storage/'.$img) : null,
        'price'     => (float) $v->price,
        'sale'      => (float) $v->effectivePrice(),
        'available' => $v->inventory && $v->inventory->available() > 0,
    ])->toJson();

    $wishlistUrl = route('wishlist.toggle', $product);
    $wishTok     = csrf_token();

    // User's wishlist product ids, fetched once per request (avoids N+1 on loops).
    $inWishlist = false;
    if (auth()->check()) {
        $wishIds = &$GLOBALS['__userWishIds'];
        if (!isset($wishIds)) {
            $wishIds = auth()->user()->wishlistItems()->pluck('product_id')->all();
        }
        $inWishlist = in_array($product->id, $wishIds, true);
    }
@endphp

@endphp

<article class="anv-card group relative flex h-full w-full flex-col overflow-hidden rounded-2xl bg-white shadow-[0_2px_14px_-8px_rgba(36,36,36,.18)] ring-1 ring-sage-100/80 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_20px_44px_-18px_rgba(35,90,73,.4)]">
    {{-- ═══ IMAGE · 1:1 · badges · hover swap · wishlist · sold-out ═══ --}}
    <a href="{{ route('shop.product', $product) }}" class="relative block aspect-square w-full overflow-hidden rounded-t-[14px] bg-[#f3f3f3]">
        <img src="{{ $img ? asset('storage/'.$img) : asset('images/placeholder.png') }}" loading="lazy"
             alt="{{ $product->name }}"
             class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-[1.06] group-hover:opacity-0">
        @if($hoverPath && $hoverPath !== $img)
            <img src="{{ asset('storage/'.$hoverPath) }}" alt="" loading="lazy"
                 class="absolute inset-0 h-full w-full object-cover opacity-0 transition duration-500 group-hover:opacity-100">
        @endif

        @if($discount > 0 || $badge)
            <span class="anv-badge-tag absolute left-2 top-2 rounded-md bg-gold-300 px-1.5 py-1 text-[10px] font-extrabold uppercase text-anv-600 shadow-sm">{{ $discount > 0 ? $discount.'% OFF' : $badge }}</span>
        @endif

        @if($badge && $discount > 0)
            <span class="anv-badge-tag absolute right-2 top-11 rounded-md bg-anv-600 px-1.5 py-1 text-[9px] font-bold uppercase text-white shadow-sm">{{ $badge }}</span>
        @endif

        {{-- Wishlist heart (guests -> login) --}}
        @auth
            <button type="button"
                    data-wishlist
                    data-url="{{ $wishlistUrl }}"
                    class="absolute right-2 top-2 z-10 grid h-9 w-9 place-items-center rounded-full bg-white/90 text-[#235A49] shadow-sm ring-1 ring-sage-200/70 backdrop-blur transition hover:scale-110 hover:bg-white {{ $inWishlist ? 'wish-on' : '' }}"
                    aria-label="Add to wishlist"
                    @click="
                        let btn = $el;
                        fetch(btn.dataset.url, { headers: { 'X-Requested-With':'XMLHttpRequest','Accept':'application/json' } })
                          .then(r => r.json())
                          .then(d => {
                            btn.classList.toggle('wish-on', d.inWishlist);
                            if (d.inWishlist) btn.innerHTML = '<svg viewBox=\'0 0 24 24\' fill=\'#e02441\' class=\'h-5 w-5\'><path d=\'M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8L12 21l8.8-8.6a5.5 5.5 0 0 0 0-7.8z\'/></svg>';
                            else btn.innerHTML = '<svg viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\' class=\'h-5 w-5\'><path d=\'M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8L12 21l8.8-8.6a5.5 5.5 0 0 0 0-7.8z\'/></svg>';
                          });
                    ">
                @if($inWishlist)
                    <svg viewBox="0 0 24 24" fill="#e02441" class="h-5 w-5"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8L12 21l8.8-8.6a5.5 5.5 0 0 0 0-7.8z"/></svg>
                @else
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8L12 21l8.8-8.6a5.5 5.5 0 0 0 0-7.8z"/></svg>
                @endif
            </button>
        @else
            <a href="{{ route('login') }}" title="Login to save this item"
               class="absolute right-2 top-2 z-10 grid h-9 w-9 place-items-center rounded-full bg-white/90 text-[#235A49] shadow-sm ring-1 ring-sage-200/70 backdrop-blur transition hover:scale-110 hover:bg-white" aria-label="Login to add to wishlist">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8L12 21l8.8-8.6a5.5 5.5 0 0 0 0-7.8z"/></svg>
            </a>
        @endauth

        @if(!$inStock)
            <span class="absolute inset-0 grid place-items-center bg-white/60 backdrop-blur-[1px]">
                <span class="rounded-md bg-anv-600 px-3 py-1.5 text-xs font-bold uppercase tracking-wide text-white">Sold Out</span>
            </span>
        @endif
    </a>

    {{-- ═══ BODY ═══ --}}
    <div class="flex flex-1 flex-col p-3">

        {{-- ═══ QUICK-ADD / STEPPER ═══ --}}
        @if(!$inStock)
            <button type="button" @click.prevent="$dispatch('open-notify', {name: {{ json_encode($product->name) }}, slug: {{ json_encode($product->slug) }}})"
                    class="anv-badge-tag mb-2 w-full rounded-full border-2 border-[#CF9726] py-1.5 text-xs font-extrabold uppercase tracking-wider text-[#CF9726] transition hover:bg-[#CF9726] hover:text-white">
                Notify Me
            </button>
        @elseif($multi)
            <button type="button" @click.prevent="$dispatch('open-variant', { variants: {{ $variantsJson }}, name: {{ json_encode($product->name) }} })"
                    class="mb-2 flex w-full items-center justify-center gap-1.5 rounded-full border-2 border-anv-600 py-1.5 text-xs font-extrabold uppercase tracking-wide text-anv-600 transition hover:bg-anv-600 hover:text-white">
                ADD
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="h-4 w-4"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            </button>
        @else
            @php $vid = $variant->id; @endphp
            <div class="mb-2 flex items-center rounded-full border-2 border-anv-600 {{ $available ? '' : 'pointer-events-none opacity-40' }}"
                 x-data="anvStepper('{{ $vid }}', {{ $available ? '1' : '0' }})"
                 :style="qty>0 && 'background:#235A49;border-color:#235A49'">
                <button type="button" @click.prevent="window.AnvCart.plus('{{ $vid }}')"
                        class="flex-1 py-1.5 text-center text-xs font-extrabold uppercase tracking-wide text-anv-600" :class="qty>0?'text-white':'text-anv-600'">
                    ADD<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="mx-auto mt-0.5 h-3.5 w-3.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                </button>
                <template x-if="qty > 0">
                    <button type="button" @click.prevent="window.AnvCart.minus('{{ $vid }}')" class="px-3 py-1.5 text-lg font-bold leading-none text-white">−</button>
                </template>
                <span x-show="qty>0" x-text="qty" class="text-sm font-bold text-white" x-cloak></span>
                <template x-if="qty > 0">
                    <button type="button" @click.prevent="window.AnvCart.plus('{{ $vid }}')" class="px-3 py-1.5 text-lg font-bold leading-none text-white">+</button>
                </template>
            </div>
        @endif

        <a href="{{ route('shop.product', $product) }}" class="mt-1 block">
            <h3 class="line-clamp-2 text-[15px] font-semibold leading-snug text-[#1f2a24] group-hover:text-anv-700">{{ $product->name }}</h3>
        </a>

        @if($rating > 0)
            <div class="mt-1.5 flex items-center gap-1 text-xs">
                <span class="inline-flex items-center gap-0.5 font-bold text-[#b8860b]">
                    <svg viewBox="0 0 20 20" fill="#FFC107" class="h-3.5 w-3.5"><path d="M10 1.5l2.6 5.3 5.9.9-4.2 4.1 1 5.8L10 15.3 4.7 17.6l1-5.8L1.5 7.7l5.9-.9z"/></svg>
                    {{ number_format($rating, 1) }}
                </span>
                <span class="text-[#9AA79F]">({{ $reviews }})</span>
            </div>
        @endif

        <div class="mt-2 flex flex-wrap items-baseline justify-between gap-x-2 gap-y-0.5">
            <div class="flex flex-wrap items-baseline gap-x-1.5">
                <span class="text-[17px] font-extrabold tracking-tight text-[#235A49]">₹{{ number_format($variant?->effectivePrice() ?? $product->basePrice(), 0) }}</span>
                @if($variant && $variant->price > $variant->effectivePrice())
                    <s class="text-xs text-[#9AA79F]">₹{{ number_format($variant->price, 0) }}</s>
                @endif
                @if($unit)
                    <span class="text-[11px] font-medium text-[#6B7E73]">/{{ $unit }}</span>
                @endif
            </div>
        </div>

        @if($sold > 0)
            <div class="anv-qty-sold mt-1.5 inline-flex w-fit items-center gap-1 rounded-full bg-[#FFF6DB] px-2 py-0.5 text-[10px] font-bold text-gold-600">🔥 {{ $sold }} sold in last 7 days</div>
        @endif

        @if($product->promo_note)
            <p class="mt-1 text-[11px] font-semibold text-anv-500">{{ $product->promo_note }}</p>
        @endif
    </div>
</article>