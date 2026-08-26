@props(['product'])

@php
    $variant = $product->defaultVariant;
    $inventory = $variant?->inventory;
    $inStock = $inventory && $inventory->available() > 0;
    $hasSale = $variant && $variant->sale_price && $variant->sale_price < $variant->price;
    $salePercent = $hasSale ? round((1 - $variant->sale_price / $variant->price) * 100) : 0;
@endphp

<div class="group relative flex w-full flex-col rounded-xl border border-gray-100 bg-white shadow-sm transition hover:shadow-md overflow-hidden">

    {{-- Image area --}}
    <a href="{{ route('shop.product', $product->slug) }}" class="relative block aspect-square w-full bg-white p-3">
        @if($product->primaryImage)
            <img
                src="{{ asset('storage/'.$product->primaryImage->thumb_path) }}"
                alt="{{ $product->primaryImage->alt_text ?: $product->name }}"
                class="h-full w-full object-contain transition group-hover:scale-105"
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
                    <span class="text-[11px] {{ $i <= round($product->rating_avg) ? 'text-amber-400' : 'text-gray-200' }}">★</span>
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

            {{-- Add / Out of Stock --}}
            @if($inStock)
                <form action="{{ route('cart.add') }}" method="POST" class="inline-block">
                    @csrf
                    <input type="hidden" name="variant_id" value="{{ $variant->id }}">
                    <button
                        type="submit"
                        class="rounded-lg border border-emerald-600 bg-white px-4 py-1.5 text-[12px] font-bold uppercase tracking-wide text-emerald-600 transition hover:bg-emerald-600 hover:text-white active:scale-95"
                    >
                        ADD
                    </button>
                </form>
            @else
                <span class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                    Out of Stock
                </span>
            @endif
        </div>

        {{-- Farmer source --}}
        @if($product->farmer_source)
            <div class="mt-1.5 flex items-center gap-1 text-[10px] text-gray-400">
                <span class="text-emerald-500">🌱</span>
                <span class="truncate">{{ $product->farmer_source }}</span>
            </div>
        @endif
    </div>
</div>
