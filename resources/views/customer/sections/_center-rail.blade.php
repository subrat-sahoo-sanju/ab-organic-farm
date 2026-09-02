@php
    $products = $data ?? collect();
    $gridId = 'rail-'.$sec->key;
    $isSuper = $sec->key === 'superfoods';
    $bg = $isSuper
        ? 'bg-gradient-to-br from-[#E8F5E9] via-[#F1F8E9] to-[#FFFDE7]'
        : 'bg-gradient-to-br from-[#FFF8E1] via-[#FFF3E0] to-[#FFF8E1]';
@endphp

@if($products->count())
<style>
  .rail-center-head{text-align:center;width:100%;margin:0 auto}
  .rail-center-head .rail-title{font-size:1.9rem;font-weight:700;line-height:1.2;margin:0 0 .25rem;color:#235a49}
  .rail-center-head .rail-sub{font-size:1.05rem;font-weight:400;margin:0;color:#235a49;opacity:.85}
  .rail-scroll{display:flex!important;flex-wrap:nowrap!important;overflow-x:auto!important;overflow-y:hidden!important;scroll-behavior:smooth;-webkit-overflow-scrolling:touch;scrollbar-width:none;gap:12px;margin:0;padding:0 2px;align-items:stretch;scroll-snap-type:x mandatory}
  .rail-scroll::-webkit-scrollbar{display:none;width:0;height:0}
  .rail-scroll .rail-item{flex:0 0 190px;width:190px;min-width:190px;max-width:190px;position:relative;scroll-snap-align:start}
  .rail-scroll .rail-item .anv-card{height:100%;min-height:0}
  .rail-scroll-row{display:flex;align-items:center;gap:12px;padding:12px 2px;margin-top:10px}
  .rail-scroll-track{flex:1;height:8px;background:#f1f1f1;border-radius:4px;position:relative;overflow:hidden;min-width:0}
  .rail-scroll-thumb{height:100%;background:#404040!important;border-radius:4px;position:absolute;top:0;left:0;min-width:20px;width:50px}
  .rail-see-all{display:flex;align-items:center;gap:8px;padding:8px 16px;background:transparent;border:1.5px solid #404040;border-radius:20px;text-decoration:none;color:#404040;font-size:14px;font-weight:500;white-space:nowrap;transition:all .2s ease;flex-shrink:0}
  .rail-see-all:hover{background:#404040;color:#fff}
  .rail-see-all:after{content:"\2192";font-size:16px;font-weight:700;transition:transform .2s ease}
  .rail-see-all:hover:after{transform:translate(3px)}
  @media (min-width:750px){
    .rail-center-head .rail-title{font-size:2.2rem}
    .rail-scroll{gap:16px;padding:0}
    .rail-scroll .rail-item{flex:0 0 240px;width:240px;min-width:240px;max-width:240px}
    .rail-scroll-row{gap:16px;padding:14px 0}
  }
  @media (min-width:1200px){.rail-scroll .rail-item{flex:0 0 250px;width:250px;min-width:250px;max-width:250px}}
</style>
<section class="w-full border-t border-sage-100 py-10 sm:py-14 {{ $bg }}">
    <div class="mx-auto w-full max-w-[1300px] px-4 sm:px-6 lg:px-8">
        <div class="rail-center-head">
            <h2 class="rail-title">{{ $sec->title }}</h2>
            @if($sec->subtitle)
                <p class="rail-sub">{{ $sec->subtitle }}</p>
            @endif
        </div>

        <div x-data="railScroll()" class="mt-6 sm:mt-8">
            <div x-ref="grid" id="{{ $gridId }}" class="rail-scroll">
                @foreach($products as $product)
                    <div class="rail-item"><x-product-card :product="$product" /></div>
                @endforeach
            </div>
            <div class="rail-scroll-row">
                <div class="rail-scroll-track"><div x-ref="thumb" class="rail-scroll-thumb"></div></div>
                <a href="{{ route('shop.categories') }}" class="rail-see-all">See All</a>
            </div>
        </div>
    </div>
</section>
@endif