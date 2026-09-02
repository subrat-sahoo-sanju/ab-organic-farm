@php
    $cfg = $sec->config ?? [];
    $android = $cfg['android_url'] ?? null;
    $ios = $cfg['ios_url'] ?? null;
    $imgs = $cfg['images'] ?? [];
    $phoneImg = $imgs['desktop'] ?? 'app-icon.jpg';
    $phoneSrc = str_starts_with((string)$phoneImg, 'http') ? $phoneImg : asset('storage/'.$phoneImg);
@endphp

@if($android || $ios || ! empty($imgs['desktop']))
<section class="relative w-full overflow-hidden">
    <div class="mx-auto w-full max-w-[1300px] px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
        <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-[#0B3B30] via-[#0C5B3B] to-[#0C831F] px-6 py-12 sm:px-12 lg:px-16">
            <div class="pointer-events-none absolute -right-20 -top-16 h-72 w-72 rounded-full bg-[#74C9A1]/25 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-24 left-1/3 h-72 w-72 rounded-full bg-gold-400/20 blur-3xl"></div>
            <div class="pointer-events-none absolute inset-0 opacity-[0.06]" style="background-image:radial-gradient(#fff 1.5px, transparent 1.5px); background-size:22px 22px;"></div>

            <div class="relative flex flex-col items-center gap-10 lg:flex-row lg:justify-between">
                <div class="text-center lg:max-w-lg lg:text-left">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3 py-1 text-[11px] font-extrabold uppercase tracking-wider text-gold-300 ring-1 ring-gold-300/40">
                        <x-lucide-smartphone class="h-3.5 w-3.5" />Mobile App
                    </span>
                    <h2 class="mt-4 font-display text-3xl font-bold text-white sm:text-4xl">{{ $sec->title }}</h2>
                    <p class="mt-3 max-w-md text-sm text-white/80 sm:text-base">{{ $sec->subtitle }}</p>

                    <div class="mt-7 flex flex-wrap items-center justify-center gap-3 lg:justify-start">
                        @if($android)
                            <a href="{{ $android }}" target="_blank" rel="noopener" class="transition hover:opacity-90 hover:scale-[1.03]">
                                <img src="{{ asset('images/play-store.png') }}" alt="Available on Google Play" class="h-14 w-auto drop-shadow-lg">
                            </a>
                        @endif
                        @if($ios)
                            <a href="{{ $ios }}" target="_blank" rel="noopener" class="transition hover:opacity-90 hover:scale-[1.03]">
                                <img src="{{ asset('images/app-store.png') }}" alt="Download on the App Store" class="h-14 w-auto drop-shadow-lg">
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Phone mockup with admin-uploadable app icon --}}
                <div class="relative hidden shrink-0 rotate-6 items-end lg:flex">
                    <div class="h-52 w-32 rounded-[1.8rem] border-[6px] border-white/95 bg-white p-2 shadow-2xl">
                        <div class="flex h-full w-full flex-col items-center justify-center gap-3 overflow-hidden rounded-2xl bg-leaf-50">
                            <span class="grid h-16 w-16 place-items-center overflow-hidden rounded-2xl bg-anv-600 shadow-md ring-2 ring-white">
                                <img src="{{ $phoneSrc }}" alt="App icon" class="h-full w-full object-cover">
                            </span>
                            <span class="text-[11px] font-extrabold text-anv-800">{{ $sec->title ?: setting('store.name') }}</span>
                            <span class="flex gap-1">
                                <span class="h-1.5 w-6 rounded-full bg-anv-600"></span>
                                <span class="h-1.5 w-2 rounded-full bg-leaf-200"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
