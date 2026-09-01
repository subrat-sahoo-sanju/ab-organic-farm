@php
    $banners = $data ?? collect();
    $deliveryText = setting('home.delivery_charge_text', 'Free delivery ₹499+');
@endphp

@if($banners->count())
<style>
.hero-viewport{position:relative;width:100%;height:300px;overflow:hidden;background-color:#14532d}
@media (min-width:640px){.hero-viewport{height:400px}}
@media (min-width:1024px){.hero-viewport{height:460px}}
</style>
<section x-data="{ active: 0, total: {{ $banners->count() }} }" x-init="setInterval(() => { active = (active + 1) % total }, 5000)" class="relative w-full overflow-hidden bg-[#14532d]">
  <div class="hero-viewport">
      @foreach($banners as $index => $banner)
      <div
        x-show="active === {{ $index }}"
        x-transition:enter="transition duration-700 ease-in-out"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition duration-700 ease-in-out"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0"
      >
          @if(!empty($banner->show_text) && $banner->show_text)
            <a href="{{ $banner->button_url ?: '#' }}" class="absolute inset-0 block">
              @if(!empty($banner->mobile_image))
                <img src="{{ asset('storage/'.$banner->mobile_image) }}" alt="{{ $banner->title }}" class="absolute inset-0 h-full w-full object-cover sm:hidden" loading="{{ $index === 0 ? 'eager' : 'lazy' }}" />
              @endif
              <img src="{{ asset('storage/'.$banner->desktop_image) }}" alt="{{ $banner->title }}" class="absolute inset-0 h-full w-full object-cover {{ !empty($banner->mobile_image) ? 'hidden sm:block' : 'block' }}" loading="{{ $index === 0 ? 'eager' : 'lazy' }}" />
              <div class="absolute inset-0 bg-gradient-to-r from-charcoal-900/70 via-charcoal-900/30 to-transparent"></div>
              <div class="absolute inset-0 flex items-center">
                <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
                  <div class="max-w-lg">
                    @if($banner->subtitle)
                      <span class="mb-3 inline-block rounded-full bg-[#74C9A1]/25 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-[#74C9A1] ring-1 ring-[#74C9A1]/40 backdrop-blur-sm">{{ $banner->subtitle }}</span>
                    @endif
                    <h2 class="font-display text-[26px] font-extrabold leading-tight text-white sm:text-4xl lg:text-5xl">{{ $banner->title }}</h2>
                    @if($banner->button_text)
                      <span class="mt-6 inline-flex items-center gap-2 rounded-xl bg-[#74C9A1] px-7 py-3.5 text-sm font-bold text-[#0C3D22] shadow-lg transition duration-300 hover:bg-white">
                        {{ $banner->button_text }}<x-lucide-arrow-right class="h-4 w-4" />
                      </span>
                    @endif
                  </div>
                </div>
              </div>
            </a>
          @else
            <a href="{{ $banner->button_url ?: '#' }}" class="absolute inset-0 block" title="{{ $banner->title }}">
              <img src="{{ asset('storage/'.$banner->desktop_image) }}" alt="{{ $banner->title }}" class="absolute inset-0 h-full w-full object-cover" loading="{{ $index === 0 ? 'eager' : 'lazy' }}" />
            </a>
          @endif
      </div>
      @endforeach

      @if($banners->count() > 1)
      <div class="absolute bottom-5 left-1/2 z-10 flex -translate-x-1/2 gap-2">
        @foreach($banners as $index => $banner)
          <button @click="active = {{ $index }}" :class="active === {{ $index }} ? 'w-8 bg-[#74C9A1]' : 'w-2 bg-white/60'" class="h-2 rounded-full transition-all duration-300 shadow"></button>
        @endforeach
      </div>
      @endif
  </div>

  {{-- Delivery badge strip --}}
  <div class="bg-[#0C831F] py-2.5">
    <div class="mx-auto flex max-w-7xl items-center justify-center gap-6 px-4 text-xs font-medium text-white/90 sm:gap-10 sm:text-sm">
      <span class="flex items-center gap-1.5"><span class="relative flex h-2 w-2"><span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#74C9A1] opacity-75"></span><span class="relative inline-flex h-2 w-2 rounded-full bg-[#74C9A1]"></span></span>10-min delivery</span>
      <span class="flex items-center gap-1.5"><span class="inline-block h-1.5 w-1.5 rounded-full bg-[#74C9A1]"></span>100% Organic</span>
      <span class="flex items-center gap-1.5"><span class="inline-block h-1.5 w-1.5 rounded-full bg-[#74C9A1]"></span>{{ $deliveryText }}</span>
    </div>
  </div>
</section>
@else
<section class="relative w-full overflow-hidden bg-gradient-to-br from-[#0C831F] to-[#2d9a4e]">
  <div class="mx-auto max-w-7xl px-4 py-14 text-center sm:px-6 sm:py-20 lg:px-8">
    <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 text-sm font-medium text-white backdrop-blur-sm"><x-lucide-zap class="h-4 w-4 text-yellow-300" /><span>Farm-fresh & lightning-fast</span></span>
    <h1 class="mt-5 font-display text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">Pure. Organic. <span class="text-[#74C9A1]">Delivered.</span></h1>
    <p class="mx-auto mt-4 max-w-xl text-lg text-white/80">100% organic groceries from our farm to your door in {{ $deliveryText }}.</p>
    <a href="{{ route('shop.categories') }}" class="mt-8 inline-flex items-center gap-2 rounded-xl bg-white px-7 py-3.5 text-sm font-bold text-[#0C831F] shadow-lg transition hover:bg-[#74C9A1]">Shop Now<x-lucide-arrow-right class="h-4 w-4" /></a>
  </div>
</section>
@endif