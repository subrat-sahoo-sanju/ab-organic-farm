<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title.' — '.setting('store.name') : setting('store.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..900;1,9..144,300..900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('storage/sections/app-icon.jpg') }}" type="image/jpeg">
    @php $css = @file_get_contents(public_path('static/assets/app-CwmQRRjJ.css')); @endphp
    <style>{!! $css !!}</style>
</head>
<body class="min-h-screen bg-cream-50 font-sans antialiased">

@if(session('success') || session('error'))
    <div id="flash-data" data-success="{{ session('success') }}" data-error="{{ session('error') }}" hidden></div>
@endif

<div class="min-h-screen flex">
    {{-- ── Left Branding Panel (hidden on mobile) ────────────── --}}
    <div class="hidden lg:flex lg:w-[45%] xl:w-[48%] relative overflow-hidden bg-gradient-to-br from-forest-600 via-forest-700 to-forest-800 flex-col items-center justify-center p-12 text-white">
        {{-- Decorative floating shapes --}}
        <div class="absolute top-[-60px] left-[-60px] h-64 w-64 rounded-full bg-white/5"></div>
        <div class="absolute bottom-[-80px] right-[-40px] h-80 w-80 rounded-full bg-white/[0.04]"></div>
        <div class="absolute top-1/4 right-10 h-40 w-40 rounded-full bg-mint-400/10"></div>
        <div class="absolute bottom-1/3 left-8 h-24 w-24 rounded-full bg-mint-300/8"></div>
        <div class="absolute top-16 right-1/4 h-16 w-16 rounded-2xl rotate-12 bg-white/[0.06]"></div>
        <div class="absolute bottom-24 left-1/4 h-20 w-20 rounded-2xl -rotate-6 bg-white/[0.05]"></div>

        {{-- Content --}}
        <div class="relative z-10 flex flex-col items-center text-center max-w-sm">
            {{-- Large leaf icon --}}
            <div class="mb-8 flex items-center justify-center w-28 h-28 rounded-[2rem] bg-white/10 backdrop-blur-sm ring-1 ring-white/20 shadow-lift animate-fade-up">
                <x-lucide-leaf class="h-14 w-14 text-mint-300" />
            </div>

            {{-- Brand name --}}
            <h1 class="font-display text-4xl xl:text-5xl font-bold tracking-tight mb-3 animate-fade-up stagger-1">
                {{ setting('store.name') }}
            </h1>

            {{-- Tagline --}}
            <p class="text-lg font-medium text-mint-200/90 mb-6 animate-fade-up stagger-2">
                Fresh &middot; Organic &middot; Trusted
            </p>

            {{-- Decorative divider --}}
            <div class="flex items-center gap-3 mb-6 animate-fade-up stagger-3">
                <span class="h-px w-10 bg-white/30"></span>
                <span class="h-2 w-2 rounded-full bg-mint-400"></span>
                <span class="h-px w-10 bg-white/30"></span>
            </div>

            <p class="text-sm text-white/60 leading-relaxed animate-fade-up stagger-4">
                Farm-fresh organic produce delivered to your doorstep. Nourish your body, nurture the planet.
            </p>
        </div>

        {{-- Bottom accent line --}}
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-mint-400 via-forest-400 to-mint-300"></div>
    </div>

    {{-- ── Right Form Panel ─────────────────────────────────── --}}
    <div class="flex-1 flex flex-col items-center justify-center p-6 sm:p-8 lg:p-12">
        <div class="w-full max-w-md">
            {{-- Logo --}}
            <a href="/" class="flex items-center justify-center gap-2.5 mb-10 font-display text-2xl font-bold text-forest-700 animate-fade-up">
                <span class="h-10 w-10 rounded-2xl bg-forest-600 grid place-items-center text-mint-300 shadow-card">
                    <x-lucide-leaf class="h-5 w-5"/>
                </span>
                {{ setting('store.name') }}
            </a>

            {{-- Form card --}}
            <div class="rounded-card bg-white shadow-card ring-1 ring-cream-200 p-6 sm:p-8 animate-fade-up stagger-1">
                {{ $slot }}
            </div>

            {{-- Footer text --}}
            <p class="mt-6 text-center text-xs text-charcoal-600/50 animate-fade-up stagger-2">
                Fresh &middot; Organic &middot; Trusted &mdash; Farm to Home
            </p>
        </div>
    </div>
</div>

<x-ui.toaster/>
    @stack('scripts')
    @php $js = @file_get_contents(public_path('static/assets/app-CfqTcJ9y.js')); @endphp
    <script>{!! $js !!}</script>
</body>
</html>
