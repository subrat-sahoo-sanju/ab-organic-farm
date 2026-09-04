@props(['title' => null])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? $title.' — '.setting('store.name') : setting('store.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,600;9..144,700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('storage/sections/app-icon.jpg') }}" type="image/jpeg">
    @php $css = @file_get_contents(public_path('static/assets/app-Dgsg4rRZ.css')); @endphp
    <style>{!! $css !!}</style>
</head>
<body class="min-h-screen bg-cream flex items-center justify-center p-4">

@if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition class="fixed top-4 right-4 z-50 rounded-xl border border-forest/20 bg-forest/10 px-4 py-3 text-sm text-forest shadow-lg max-w-sm">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition class="fixed top-4 right-4 z-50 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600 shadow-lg max-w-sm">{{ session('error') }}</div>
@endif

<div class="w-full max-w-md">
    <a href="/" class="flex items-center justify-center gap-2 mb-8 font-display text-2xl font-bold text-forest">
        <span class="h-10 w-10 rounded-2xl bg-forest grid place-items-center text-white text-lg font-bold">{{ strtoupper(substr(setting('store.name'), 0, 1)) }}</span>
        {{ setting('store.name') }}
    </a>

    <div class="rounded-2xl bg-white shadow-lg ring-1 ring-sage/20 p-6 sm:p-8">
        {{ $slot }}
    </div>

    <p class="mt-6 text-center text-xs text-charcoal/40">Fresh · Organic · Trusted — Farm to Home</p>
</div>
</body>
    @stack('scripts')
    @php $js = @file_get_contents(public_path('static/assets/app-CfqTcJ9y.js')); @endphp
    <script>{!! $js !!}</script>
</html>
