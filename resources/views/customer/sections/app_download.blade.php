@php
    $cfg = $sec->config ?? [];
    $android = $cfg['android_url'] ?? null;
    $ios = $cfg['ios_url'] ?? null;
@endphp

@if($android || $ios)
<section class="py-10">
  <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#0C831F] via-[#146B28] to-[#0C3D22] px-6 py-10 sm:px-12">
      <div class="pointer-events-none absolute -right-20 -top-16 h-64 w-64 rounded-full bg-[#74C9A1]/20 blur-3xl"></div>
      <div class="pointer-events-none absolute -bottom-24 left-1/3 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>

      <div class="relative flex flex-col items-center gap-8 lg:flex-row lg:justify-between">
        <div class="text-center lg:text-left">
          <span class="inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3 py-1 text-[11px] font-extrabold uppercase tracking-wider text-[#74C9A1] ring-1 ring-[#74C9A1]/40">
            <x-lucide-smartphone class="h-3.5 w-3.5" />Mobile App
          </span>
          <h2 class="mt-3 font-display text-3xl font-bold text-white sm:text-4xl">{{ $sec->title }}</h2>
          <p class="mt-2 max-w-md text-sm text-white/75 sm:text-base">{{ $sec->subtitle }}</p>
          <div class="mt-6 flex items-center justify-center gap-3 lg:justify-start">
            @if($android)
              <a href="{{ $android }}" target="_blank" rel="noopener" class="flex items-center gap-2 rounded-xl bg-charcoal-900 px-5 py-3 text-white ring-1 ring-white/20 transition hover:ring-[#74C9A1]">
                <x-lucide-play class="h-5 w-5 text-[#74C9A1]" /><span class="text-left"><span class="block text-[9px] uppercase tracking-wide text-white/60">Get it on</span><span class="block text-sm font-bold">Google Play</span></span>
              </a>
            @endif
            @if($ios)
              <a href="{{ $ios }}" target="_blank" rel="noopener" class="flex items-center gap-2 rounded-xl bg-charcoal-900 px-5 py-3 text-white ring-1 ring-white/20 transition hover:ring-[#74C9A1]">
                <x-lucide-apple class="h-5 w-5 text-[#74C9A1]" /><span class="text-left"><span class="block text-[9px] uppercase tracking-wide text-white/60">Download on</span><span class="block text-sm font-bold">App Store</span></span>
              </a>
            @endif
          </div>
        </div>

        <div class="relative hidden rotate-3 items-end lg:flex">
          <div class="h-44 w-28 rounded-[1.6rem] border-[6px] border-white/90 bg-gradient-to-b from-[#74C9A1] to-forest-700 p-2 shadow-2xl">
            <div class="flex h-full w-full flex-col items-center justify-center gap-2 rounded-xl bg-white/90">
              <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-forest-600 text-white"><x-lucide-leaf class="h-5 w-5" /></span>
              <span class="text-[10px] font-extrabold text-forest-700">AB Organic</span>
              <span class="flex gap-1"><span class="h-1.5 w-6 rounded-full bg-forest-500"></span><span class="h-1.5 w-2 rounded-full bg-forest-200"></span></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endif