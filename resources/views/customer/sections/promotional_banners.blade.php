@php
    $banners = $data ?? collect();
@endphp

@if($banners->count())
<section class="pb-4">
  <div class="mx-auto grid w-full max-w-[1440px] grid-cols-1 gap-4 px-4 sm:grid-cols-2 sm:px-6 lg:px-8">
    @foreach($banners as $banner)
      <a href="{{ $banner->button_url ?: '#' }}" class="group relative block overflow-hidden rounded-2xl bg-cream-100 shadow-sm transition duration-300 hover:shadow-xl">
        @if(!empty($banner->mobile_image))
          <img src="{{ asset('storage/'.$banner->mobile_image) }}" alt="{{ $banner->title }}" class="block h-auto w-full object-cover sm:hidden" loading="lazy" />
        @endif
        <img src="{{ asset('storage/'.$banner->desktop_image) }}" alt="{{ $banner->title }}" class="{{ !empty($banner->mobile_image) ? 'hidden sm:block' : 'block' }} h-auto w-full object-cover" loading="lazy" />
        @if(!empty($banner->show_text))
          <div class="absolute inset-0 bg-gradient-to-t from-charcoal-900/70 via-transparent to-transparent"></div>
          <div class="absolute inset-x-0 bottom-0 p-5 sm:p-6">
            @if($banner->subtitle)<span class="mb-1 inline-block rounded-full bg-[#A9CB92]/90 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-white">{{ $banner->subtitle }}</span>@endif
            <h3 class="font-display text-lg font-extrabold text-white sm:text-xl">{{ $banner->title }}</h3>
            @if($banner->button_text)
              <span class="mt-1 inline-flex items-center gap-1 text-sm font-bold text-[#A9CB92] transition group-hover:text-white">{{ $banner->button_text }}<x-lucide-arrow-right class="h-4 w-4" /></span>
            @endif
          </div>
        @endif
      </a>
    @endforeach
  </div>
</section>
@endif