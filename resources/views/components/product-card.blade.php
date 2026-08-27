@props(['product'])

@php
    $variant = $product->defaultVariant;
    $inventory = $variant?->inventory;
    $inStock = $inventory && $inventory->available() > 0;
    $hasSale = $variant && $variant->sale_price && $variant->sale_price < $variant->price;
    $salePercent = $hasSale ? round((1 - $variant->sale_price / $variant->price) * 100) : 0;
    $roundedRating = round($product->rating_avg ?? 0);
@endphp

<div class="group relative flex h-full w-full flex-col rounded-2xl border border-sage/20 bg-white shadow-sm transition duration-300 hover:-translate-y-0.5 hover:border-[#0C831F]/30 hover:shadow-lg overflow-hidden">

    {{-- Image area --}}
    <a href="{{ route('shop.product', $product->slug) }}" class="relative block aspect-square w-full bg-gradient-to-br from-[#FDFBF7] to-[#f2f7ef] p-3">
        @if($product->primaryImage)
            <img
                src="{{ asset('storage/'.$product->primaryImage->thumb_path) }}"
                alt="{{ $product->primaryImage->alt_text ?: $product->name }}"
                class="h-full w-full object-contain transition duration-500 group-hover:scale-105"
                loading="lazy"
            >
        @else
            <div class="flex h-full items-center justify-center text-4xl text-gray-200">🌿</div>
        @endif

        {{-- Organic badge --}}
        @if($product->is_organic)
            <span class="absolute top-2 left-2 inline-flex items-center gap-1 rounded-full bg-emerald-600 px-2 py-0.5 text-[10px] font-bold uppercase leading-tight text-white shadow-sm">
                Organic
            </span>
        @endif

        {{-- Discount badge --}}
        @if($hasSale)
            <span class="absolute top-2 right-2 rounded-full bg-orange-500 px-2 py-0.5 text-[10px] font-bold text-white shadow-sm">
                {{ $salePercent }}% OFF
            </span>
        @endif

        {{-- Quick view hover overlay --}}
        <div class="absolute inset-0 flex items-end justify-center bg-gradient-to-t from-black/25 to-transparent p-2 opacity-0 transition duration-300 group-hover:opacity-100">
            <span class="inline-flex items-center gap-1 rounded-full bg-white/95 px-4 py-1.5 text-[11px] font-bold text-[#0C831F] shadow">
                <x-lucide-eye class="h-3.5 w-3.5" />Quick View
            </span>
        </div>
    </a>

    {{-- Content area --}}
    <div class="flex flex-1 flex-col px-3 pb-3 pt-2">

        {{-- Category --}}
        @if($product->category)
            <a href="{{ route('shop.category', $product->category->slug) }}" class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 hover:text-emerald-600 transition">
                {{ $product->category->name }}
            </a>
        @endif

        {{-- Product name --}}
        <a href="{{ route('shop.product', $product->slug) }}" class="mt-0.5 text-[13px] font-bold leading-tight text-gray-900 line-clamp-2 transition hover:text-emerald-700">
            {{ $product->name }}
        </a>

        {{-- Weight / unit --}}
        @if($variant)
            <span class="mt-0.5 text-[11px] font-medium text-gray-400">
                {{ $variant->unit_value ?? '' }}{{ $variant->unit_label ?? '' }}
            </span>
        @endif

        {{-- Rating --}}
        @if($product->rating_avg > 0)
            <div class="mt-1 inline-flex items-center gap-0.5">
                @for($i = 1; $i <= 5; $i++)
                    <span class="text-[11px] {{ $i <= $roundedRating ? 'text-amber-400' : 'text-gray-200' }}">★</span>
                @endfor
                <span class="ml-0.5 text-[10px] font-medium text-gray-400">({{ $product->review_count }})</span>
            </div>
        @endif

        {{-- Price + ADD row --}}
        <div class="mt-auto flex items-end justify-between pt-2">
            <div class="flex flex-col">
                <span class="text-base font-extrabold leading-none text-gray-900">
                    ₹{{ number_format($hasSale ? $variant->sale_price : ($variant?->price ?? 0)) }}
                </span>
                @if($hasSale)
                    <div class="mt-0.5 flex items-center gap-1">
                        <span class="text-[11px] font-medium text-gray-400 line-through">₹{{ number_format($variant->price) }}</span>
                    </div>
                @endif
            </div>

            {{-- Add / Quantity / Out of Stock --}}
            @if($inStock)
                <div
                    x-data
                    class="inline-flex items-center"
                >
                    {{-- ADD button (when not in cart) --}}
                    <button
                        type="button"
                        x-show="$store.cart.qtyOf({{ $variant->id }}) === 0"
                        @click="$store.cart.add({{ $variant->id }})"
                        class="inline-flex items-center gap-1 rounded-xl border border-emerald-600 bg-emerald-600 px-4 py-2 text-[12px] font-bold uppercase tracking-wide text-white shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 active:scale-90"
                    >
                        <x-lucide-plus class="h-3.5 w-3.5" />ADD
                    </button>

                    {{-- Quantity stepper (when in cart) --}}
                    <div
                        x-show="$store.cart.qtyOf({{ $variant->id }}) > 0"
                        x-cloak
                        class="flex items-center overflow-hidden rounded-xl border-2 border-emerald-600 bg-white shadow-sm"
                    >
                        <button
                            type="button"
                            @click="$store.cart.setQty({{ $variant->id }}, $store.cart.items[{{ $variant->id }}], $store.cart.qtyOf({{ $variant->id }}) - 1)"
                            class="flex h-9 w-9 items-center justify-center text-emerald-700 transition hover:bg-emerald-50"
                        >
                            <x-lucide-minus class="h-3.5 w-3.5" />
                        </button>
                        <span class="flex h-9 w-9 items-center justify-center text-sm font-extrabold text-emerald-700"
                              x-text="$store.cart.qtyOf({{ $variant->id }})"></span>
                        <button
                            type="button"
                            @click="$store.cart.setQty({{ $variant->id }}, $store.cart.items[{{ $variant->id }}], $store.cart.qtyOf({{ $variant->id }}) + 1)"
                            class="flex h-9 w-9 items-center justify-center text-emerald-700 transition hover:bg-emerald-50"
                        >
                            <x-lucide-plus class="h-3.5 w-3.5" />
                        </button>
                    </div>
                </div>
            @else
                <span class="rounded-xl border border-gray-200 bg-gray-50 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                    Out of Stock
                </span>
            @endif
        </div>
    </div>
</div>
