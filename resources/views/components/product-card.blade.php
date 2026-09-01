@props(['product'])

@php
    $variant = $product->defaultVariant;
    $inventory = $variant?->inventory;
    $inStock = $inventory && $inventory->available() > 0;
    $hasSale = $variant && $variant->sale_price && (float) $variant->sale_price < (float) $variant->price;
    $salePercent = $hasSale ? round((1 - $variant->sale_price / $variant->price) * 100) : 0;
    $roundedRating = round((float) ($product->rating_avg ?? 0));
    $reviewCount = (int) ($product->review_count ?? $product->approvedReviews_count ?? 0);
    $badge = $product->displayBadge();
    $hover = ($product->relationLoaded('images') && $product->images->count() > 1)
        ? $product->images->get(1)
        : null;
    $unit = ($variant?->unit_value ?? '') . ($variant?->unit_label ?? '');
    $per = trim((string) $variant?->unit_label);
@endphp

<div class="group relative flex h-full w-full flex-col overflow-hidden rounded-2xl border border-cream-200 bg-white shadow-[0_2px_12px_-2px_rgba(12,131,31,0.06)] transition-all duration-300 hover:-translate-y-1 hover:border-forest-300 hover:shadow-[0_12px_32px_-8px_rgba(12,131,31,0.22)]">

    {{-- Image area --}}
    <a href="{{ route('shop.product', $product->slug) }}" class="relative block aspect-square w-full overflow-hidden bg-gradient-to-br from-[#FBF7EE] to-[#EEF5EC]">
        @if($product->primaryImage)
            <img
                src="{{ asset('storage/'.$product->primaryImage->thumb_path) }}"
                alt="{{ $product->primaryImage->alt_text ?: $product->name }}"
                class="absolute inset-0 h-full w-full object-cover p-4 transition-all duration-500 group-hover:scale-[1.08] {{ $hover ? 'opacity-100' : '' }}"
                loading="lazy"
            >
            @if($hover)
                <img
                    src="{{ asset('storage/'.$hover->thumb_path) }}"
                    alt="{{ $product->name }}"
                    class="absolute inset-0 z-[1] h-full w-full object-cover p-4 opacity-0 transition-all duration-500 group-hover:scale-[1.08] group-hover:opacity-100"
                    loading="lazy"
                >
            @endif
        @else
            <div class="flex h-full items-center justify-center text-4xl text-gray-200">🌿</div>
        @endif

        {{-- Top-left: discount / organic --}}
        <div class="absolute left-2 top-2 z-[2] flex flex-col items-start gap-1">
            @if($hasSale)
                <span class="rounded-lg bg-clay-500 px-1.5 py-0.5 text-[10px] font-extrabold leading-none text-white shadow-sm">{{ $salePercent }}% OFF</span>
            @elseif($product->is_organic)
                <span class="rounded-lg bg-emerald-600 px-1.5 py-0.5 text-[10px] font-extrabold leading-none text-white shadow-sm">ORGANIC</span>
            @endif
        </div>

        {{-- Top-right: status badge --}}
        @if($badge)
            <div class="absolute right-2 top-2 z-[2]">
                <span class="rounded-full bg-white/95 px-2 py-0.5 text-[10px] font-extrabold leading-none tracking-wide text-[#0C831F] shadow-sm ring-1 ring-forest-200">{{ $badge }}</span>
            </div>
        @endif

        {{-- Quick view hover overlay --}}
        <div class="absolute inset-x-0 bottom-0 z-[2] flex translate-y-3 items-center justify-center p-2 opacity-0 transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-white/95 px-4 py-1.5 text-[11px] font-bold text-[#0C831F] shadow-lg ring-1 ring-black/5">
                <x-lucide-eye class="h-3.5 w-3.5" />Quick View
            </span>
        </div>
    </a>

    {{-- Content area --}}
    <div class="flex flex-1 flex-col px-3 pb-3 pt-2.5">

        {{-- Category --}}
        @if($product->category)
            <a href="{{ route('shop.category', $product->category->slug) }}" class="text-[10px] font-bold uppercase tracking-wider text-charcoal-600/40 transition hover:text-forest-600">{{ $product->category->name }}</a>
        @endif

        {{-- Product name --}}
        <a href="{{ route('shop.product', $product->slug) }}" class="mt-0.5 line-clamp-2 text-[13px] font-bold leading-tight text-charcoal-900 transition hover:text-forest-700">{{ $product->name }}</a>

        {{-- Weight / unit --}}
        @if($unit)
            <span class="mt-1 text-[11px] font-medium text-charcoal-600/50">{{ $unit }}</span>
        @endif

        {{-- Rating --}}
        <div class="mt-1.5 flex items-center gap-1">
            <span class="inline-flex items-center gap-0.5 rounded-md bg-emerald-600 px-1.5 py-0.5 text-[10px] font-extrabold text-white">
                {{ number_format((float) ($product->rating_avg ?? 0), 1, '.', '') }}<x-lucide-star class="h-2.5 w-2.5 fill-current" />
            </span>
            @if($reviewCount > 0)
                <span class="text-[10px] font-medium text-charcoal-600/40">({{ $reviewCount }} reviews)</span>
            @endif
        </div>

        {{-- Price + per unit --}}
        <div class="mt-auto flex items-start justify-between gap-2 pt-2.5">
            <div class="flex flex-col leading-none">
                <div class="flex items-baseline gap-1.5">
                    <span class="text-[15px] font-extrabold text-charcoal-900">₹{{ number_format($hasSale ? $variant->sale_price : ($variant?->price ?? 0)) }}</span>
                    @if($hasSale)
                        <span class="text-[11px] font-semibold text-charcoal-600/40 line-through">₹{{ number_format($variant->price) }}</span>
                    @endif
                </div>
                @if($per)
                    <span class="mt-0.5 text-[10px] font-medium text-charcoal-600/40">/ {{ $per }}</span>
                @endif
            </div>

            {{-- Add / Quantity / Out of Stock --}}
            @if($inStock)
                <div x-data class="shrink-0">
                    {{-- ADD button (when not in cart) --}}
                    <button
                        type="button"
                        x-show="$store.cart.qtyOf({{ $variant->id }}) === 0"
                        @click="$store.cart.add({{ $variant->id }})"
                        class="inline-flex h-9 items-center gap-1 rounded-xl border-2 border-forest-600 bg-forest-600 px-3.5 text-[12px] font-extrabold uppercase tracking-wide text-white shadow-sm transition active:scale-90 hover:bg-forest-700"
                    >
                        <x-lucide-plus class="h-3.5 w-3.5" />Add
                    </button>

                    {{-- Quantity stepper (when in cart) --}}
                    <div
                        x-show="$store.cart.qtyOf({{ $variant->id }}) > 0"
                        x-cloak
                        class="flex items-center overflow-hidden rounded-xl border-2 border-forest-600 bg-white shadow-sm"
                    >
                        <button
                            type="button"
                            @click="$store.cart.setQty({{ $variant->id }}, $store.cart.items[{{ $variant->id }}], $store.cart.qtyOf({{ $variant->id }}) - 1)"
                            class="flex h-9 w-8 items-center justify-center text-forest-700 transition hover:bg-forest-50"
                        >
                            <x-lucide-minus class="h-3.5 w-3.5" />
                        </button>
                        <span class="flex h-9 w-7 items-center justify-center text-[13px] font-extrabold text-forest-700" x-text="$store.cart.qtyOf({{ $variant->id }})"></span>
                        <button
                            type="button"
                            @click="$store.cart.setQty({{ $variant->id }}, $store.cart.items[{{ $variant->id }}], $store.cart.qtyOf({{ $variant->id }}) + 1)"
                            class="flex h-9 w-8 items-center justify-center text-forest-700 transition hover:bg-forest-50"
                        >
                            <x-lucide-plus class="h-3.5 w-3.5" />
                        </button>
                    </div>
                </div>
            @else
                <span class="shrink-0 rounded-lg border border-gray-200 bg-gray-50 px-2.5 py-1.5 text-[10px] font-bold uppercase tracking-wide text-gray-400">Sold out</span>
            @endif
        </div>

        {{-- Promo note (admin-manageable) --}}
        @if($product->promo_note)
            <p class="mt-2 rounded-lg bg-forest-50 px-2 py-1 text-[10px] font-semibold leading-snug text-forest-700">{{ $product->promo_note }}</p>
        @endif
    </div>
</div>