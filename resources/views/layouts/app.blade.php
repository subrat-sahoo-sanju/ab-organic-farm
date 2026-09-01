<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'AB Organic Farm' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700;800&family=Roboto+Slab:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>[x-cloak]{display:none!important}</style>
    @stack('head')
    @stack('meta')
</head>
<body class="min-h-screen flex flex-col bg-leaf-50 pb-[4.5rem] font-sans md:pb-0">

    @if(session('success') || session('error'))
        <div id="flash-data" data-success="{{ session('success') }}" data-error="{{ session('error') }}" hidden></div>
    @endif

    {{-- ═══ ANNOUNCEMENT BAR · rotating ═══ --}}
    @php
        $msgs = setting_json('display.announcement_items', ['Free delivery on orders above ₹499', '100% certified organic · straight from the farm']);
    @endphp
    @if(count($msgs))
    <div class="bg-anv-800 text-white">
        <div class="mx-auto flex max-w-7xl items-center justify-center px-4 py-2 text-center text-xs font-semibold tracking-wide sm:text-sm" x-data="{ i: 0, msgs: {{ json_encode(array_values($msgs)) }} }" x-init="setInterval(() => i = (i+1) % msgs.length, 6000)">
            <template x-for="(m, idx) in msgs" :key="idx">
                <span x-show="i === idx" x-text="m" x-transition.opacity.duration.500 class="truncate" style="max-width: 90vw"></span>
            </template>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════
         HEADER — sticky, logo + search + account + cart
    ═══════════════════════════════════════════════════════════════ --}}
    <header class="sticky top-0 z-40 bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center gap-3 md:gap-6">

            <a href="{{ route('shop.index') }}" class="flex items-center gap-2 shrink-0">
                @if($siteLogo = setting('display.logo', ''))
                    <img src="{{ asset('storage/'.$siteLogo) }}" alt="{{ config('app.name', 'Store') }} logo" class="h-10 w-auto max-w-[160px] object-contain">
                @else
                    <span class="h-9 w-9 rounded-lg bg-anv-600 text-white grid place-items-center">
                        <x-lucide-leaf class="h-5 w-5"/>
                    </span>
                    <span class="font-display text-lg font-bold text-anv-700 leading-tight hidden sm:block">{{ setting('store.name', 'AB Organic') }}<br><span class="text-xs font-medium text-charcoal-600/60 tracking-wide">{{ setting('store.tagline', 'FARM') }}</span></span>
                @endif
            </a>

            <form action="{{ route('shop.search') }}" method="GET" class="hidden md:flex flex-1 max-w-2xl mx-auto">
                <div class="relative w-full group">
                    <x-lucide-search class="absolute left-4 top-1/2 -translate-y-1/2 h-[18px] w-[18px] text-charcoal-600/40 group-focus-within:text-anv-600 transition-colors"/>
                    <input type="search" name="q" value="{{ request('q') }}"
                           placeholder="Find your favorite items"
                           class="w-full h-11 pl-11 pr-5 rounded-full border-2 border-sage-100 bg-leaf-50 text-sm placeholder:text-charcoal-600/40 focus:border-anv-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-anv-500/20 transition-all">
                </div>
            </form>

            <nav class="flex items-center gap-1 sm:gap-2 ml-auto shrink-0" aria-label="Main" x-data>
                <a href="{{ route('account.wishlist') }}" class="relative hidden sm:flex items-center p-2.5 rounded-full hover:bg-leaf-100 text-charcoal-700 transition-colors" aria-label="Wishlist">
                    <x-lucide-heart class="h-5 w-5"/>
                    @if(auth()->check() && $wishlistCount = auth()->user()->wishlistItems()->count())
                        <span class="absolute -top-0.5 -right-0.5 h-5 min-w-5 px-1 rounded-full bg-clay-500 text-white text-[11px] font-bold grid place-items-center">{{ $wishlistCount }}</span>
                    @endif
                </a>

                <a href="{{ route('cart.index') }}" data-cart-anchor
                   class="relative flex items-center p-2.5 rounded-full hover:bg-leaf-100 text-charcoal-700 transition-colors" aria-label="Cart">
                    <x-lucide-shopping-basket class="h-5 w-5"/>
                    <span x-text="$store.cart.count" x-show="$store.cart.count > 0" x-cloak
                          class="absolute -top-0.5 -right-0.5 h-5 min-w-5 px-1 rounded-full bg-anv-600 text-white text-[11px] font-bold grid place-items-center"></span>
                </a>

                @auth
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button class="flex items-center gap-2 pl-1 pr-3 py-1 rounded-full hover:bg-leaf-100 transition-colors" @click="open = !open" aria-label="Account menu">
                            <span class="h-8 w-8 rounded-full bg-anv-600 text-white grid place-items-center text-sm font-bold uppercase shadow-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            <span class="hidden lg:block text-sm font-medium text-charcoal-700 max-w-[8rem] truncate">{{ auth()->user()->name }}</span>
                            <x-lucide-chevron-down class="h-4 w-4 hidden sm:block text-charcoal-600/60"/>
                        </button>
                        <div x-show="open" x-transition.opacity.duration.150ms x-cloak
                             class="absolute right-0 mt-2 w-60 rounded-2xl bg-white shadow-xl ring-1 ring-black/5 overflow-hidden py-2 z-50">
                            <div class="px-4 py-3 border-b border-leaf-100">
                                <p class="text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-charcoal-600/50 truncate mt-0.5">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('account.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-leaf-50 transition-colors"><x-lucide-user class="h-4 w-4 text-charcoal-600/50"/> My Account</a>
                            <a href="{{ route('account.orders') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-leaf-50 transition-colors"><x-lucide-shopping-basket class="h-4 w-4 text-charcoal-600/50"/> My Orders</a>
                            <a href="{{ route('account.wishlist') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-leaf-50 transition-colors"><x-lucide-heart class="h-4 w-4 text-charcoal-600/50"/> Wishlist</a>
                            @if(auth()->user()->isStaff())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-anv-700 hover:bg-leaf-50 transition-colors"><x-lucide-layout-grid class="h-4 w-4"/> Admin Panel</a>
                            @endif
                            <div class="border-t border-leaf-100 mt-1 pt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="w-full text-left flex items-center gap-3 px-4 py-2.5 text-sm text-clay-600 hover:bg-leaf-50 transition-colors"><x-lucide-menu class="h-4 w-4"/> Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold px-4 py-2 rounded-full border-2 border-anv-600 text-anv-700 hover:bg-leaf-100 transition-colors">Login</a>
                    <a href="{{ route('register') }}" class="hidden sm:inline-flex items-center h-9 px-5 rounded-full bg-anv-600 text-white text-sm font-semibold hover:bg-anv-700 shadow-sm transition-colors">Sign Up</a>
                @endauth
            </nav>
        </div>

        <div class="md:hidden px-4 pb-3">
            <form action="{{ route('shop.search') }}" method="GET">
                <div class="relative w-full group">
                    <x-lucide-search class="absolute left-4 top-1/2 -translate-y-1/2 h-[18px] w-[18px] text-charcoal-600/40 group-focus-within:text-anv-600 transition-colors"/>
                    <input type="search" name="q" value="{{ request('q') }}"
                           placeholder="Find your favorite items"
                           class="w-full h-11 pl-11 pr-5 rounded-full border-2 border-sage-100 bg-leaf-50 text-sm placeholder:text-charcoal-600/40 focus:border-anv-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-anv-500/20 transition-all">
                </div>
            </form>
        </div>
    </header>

    {{-- ═══ APP DOWNLOAD BAR · full-width #00584b (reference: anveshan.farm) ═══ --}}
    @php
        $dlHeading = setting('display.app_download_heading', 'Download the {{ config("app.name") }} App');
        $dlSub     = setting('display.app_download_sub', 'Unlock 17% OFF Exclusively on App');
        $dlUrl     = setting('display.app_download_url2', '#');
        $dlEnabled = setting('display.app_download_enabled', '1');
    @endphp
    @if($dlEnabled === '1' || $dlEnabled === true)
    <div class="app-download-bar" id="appDownloadBar" x-data="{ closed: false }" x-show="!closed" x-transition>
        <div class="app-download-bar__inner">
            <div class="app-download-bar__left">
                <div class="app-download-bar__icon">
                    <img src="{{ asset('images/app-icon.jpg') }}" alt="App icon" width="44" height="44" loading="eager">
                </div>
                <div class="app-download-bar__divider"></div>
                <div class="app-download-bar__text">
                    <p class="app-download-bar__heading">{{ $dlHeading }}</p>
                    <p class="app-download-bar__sub">{{ $dlSub }}</p>
                </div>
            </div>
            <div class="app-download-bar__right">
                <a href="{{ $dlUrl }}" class="app-download-bar__btn" target="_blank" rel="noopener">
                    <span>Download Now</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
                <button type="button" class="app-download-bar__close" @click="closed = true" aria-label="Close">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════
         MAIN CONTENT
    ═══════════════════════════════════════════════════════════════ --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- ═══════════════════════════════════════════════════════════════
         FOOTER — Anveshan-style: services · policies · help · newsletter
    ═══════════════════════════════════════════════════════════════ --}}
    <footer class="mt-16 bg-anv-900 text-white/80 [background-image:radial-gradient(ellipse_at_bottom,rgba(255,255,255,.06),transparent_60%)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid gap-10 sm:grid-cols-2 lg:grid-cols-4 text-sm">

            <div>
                <div class="flex items-center gap-2 mb-4">
                    @if($siteLogoWhite = setting('display.logo_white', ''))
                        <img src="{{ asset('storage/'.$siteLogoWhite) }}" alt="{{ config('app.name', 'Store') }} logo" class="h-9 w-auto max-w-[180px] object-contain">
                    @else
                        <span class="h-8 w-8 rounded-lg bg-anv-600 text-white grid place-items-center"><x-lucide-leaf class="h-4 w-4"/></span>
                        <span class="font-display text-base font-bold text-white">{{ setting('store.name', 'AB Organic Farm') }}</span>
                    @endif
                </div>
                <p class="leading-relaxed text-white/50">{{ setting('footer.tagline', setting('store.tagline', 'Farm-fresh certified organic products, delivered to your doorstep.')) }}</p>
                <div class="mt-5 flex gap-3">
                    @php $socials = setting_json('footer.socials', [
                        ['icon' => 'facebook', 'url' => '#'], ['icon' => 'instagram', 'url' => '#'], ['icon' => 'twitter', 'url' => '#'],
                    ]); @endphp
                    @foreach($socials as $s)
                        @if(in_array(($s['icon'] ?? ''), ['facebook','instagram','twitter','youtube']))
                            <a href="{{ $s['url'] }}" target="_blank" rel="noopener" class="grid h-9 w-9 place-items-center rounded-full bg-white/10 text-white/70 transition hover:bg-gold-400 hover:text-anv-900">
                                <x-lucide-{{ $s['icon'] }} class="h-4 w-4" />
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

            @php $footerServices = setting_json('footer.links_services', [
                ['label' => 'Shop', 'url' => route('shop.categories')],
                ['label' => 'Track Your Order', 'url' => route('account.orders')],
                ['label' => 'Our Story', 'url' => route('shop.index') . '#story'],
                ['label' => 'Contact Us', 'url' => '#contact'],
            ]); @endphp
            <div>
                <h4 class="font-semibold text-white mb-4">Services</h4>
                <ul class="space-y-2.5">
                    @foreach($footerServices as $link)
                        <li><a href="{{ $link['url'] }}" class="hover:text-gold-300 transition-colors">{{ $link['label'] }}</a></li>
                    @endforeach
                    @auth @if(auth()->user()->isStaff())<li><a href="{{ route('admin.dashboard') }}" class="hover:text-gold-300 font-medium">Admin Panel</a></li>@endif @endauth
                </ul>
            </div>

            @php $footerPolicies = setting_json('footer.links_policies', [
                ['label' => 'Privacy Policy', 'url' => '#'], ['label' => 'Shipping Policy', 'url' => '#'],
                ['label' => 'Refund Policy', 'url' => '#'], ['label' => 'Terms of Service', 'url' => '#'],
            ]); @endphp
            <div>
                <h4 class="font-semibold text-white mb-4">Policies</h4>
                <ul class="space-y-2.5">
                    @foreach($footerPolicies as $link)
                        <li><a href="{{ $link['url'] }}" class="hover:text-gold-300 transition-colors">{{ $link['label'] }}</a></li>
                    @endforeach
                </ul>
                <h4 class="font-semibold text-white mb-3 mt-6">Need Help?</h4>
                <a href="{{ setting('store.contact_link', '#') }}" class="inline-flex items-center gap-2 rounded-full border border-white/25 px-5 py-2 text-xs font-bold text-white transition hover:bg-white/10">
                    <x-lucide-headphones class="h-3.5 w-3.5" /> Contact Us
                </a>
            </div>

            <div>
                <h4 class="font-semibold text-white mb-4">Stay in the loop</h4>
                <p class="text-white/50 text-xs mb-3">Fresh offers & farm stories. No spam.</p>
                <form x-data="{ email: '', done: false }" @submit.prevent="fetch('/newsletter', { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Content-Type':'application/json','Accept':'application/json'}, body: JSON.stringify({email}) }).then(r=>r.json()).then(()=>done=true); email=''">
                    <div class="flex rounded-full border border-white/25 bg-white/5 overflow-hidden">
                        <input x-model="email" type="email" required placeholder="Your email" class="flex-1 bg-transparent px-4 py-2.5 text-sm text-white placeholder:text-white/40 focus:outline-none">
                        <button type="submit" class="px-4 text-anv-200 hover:text-gold-300 transition-colors" aria-label="Subscribe"><x-lucide-arrow-right class="h-4 w-4"/></button>
                    </div>
                    <p x-show="done" x-cloak class="mt-2 text-xs text-gold-300">You're subscribed. Welcome!</p>
                </form>

                <h4 class="font-semibold text-white mb-3 mt-6">Download the App</h4>
                <div class="flex gap-2">
                    <a href="{{ setting('display.app_store_url', '#') }}" target="_blank" rel="noopener" class="flex items-center gap-2 rounded-lg bg-white/10 px-3 py-2 text-white ring-1 ring-white/20 transition hover:ring-gold-300">
                        <x-lucide-play class="h-4 w-4 text-gold-300"/><span class="text-left leading-tight"><span class="block text-[8px] uppercase text-white/60">Get it on</span><span class="block text-[11px] font-bold">Google Play</span></span>
                    </a>
                    <a href="{{ setting('display.app_download_url', '#') }}" target="_blank" rel="noopener" class="flex items-center gap-2 rounded-lg bg-white/10 px-3 py-2 text-white ring-1 ring-white/20 transition hover:ring-gold-300">
                        <x-lucide-apple class="h-4 w-4 text-gold-300"/><span class="text-left leading-tight"><span class="block text-[8px] uppercase text-white/60">Download on</span><span class="block text-[11px] font-bold">App Store</span></span>
                    </a>
                </div>
            </div>
        </div>

        <div class="border-t border-white/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 text-center text-xs text-white/40">
                Corporate Office — {{ setting('footer.address', 'Sector 32, Gurugram') }} · &copy; {{ now()->year }} AB Organic Farm Pvt. Ltd.
            </div>
        </div>
    </footer>

    {{-- ═══════════════════════════════════════════════════════════════
         MOBILE BOTTOM NAV — Home · Deal · Combos · Account
    ═══════════════════════════════════════════════════════════════ --}}
    @php $navItems = setting_json('display.bottom_nav', [
        ['label' => 'Home', 'icon' => 'home', 'url' => route('shop.index')],
        ['label' => 'Family Deal', 'icon' => 'leaf', 'url' => route('shop.categories')],
        ['label' => 'Deal', 'icon' => 'badge-percent', 'url' => route('shop.search') . '?q=deal'],
        ['label' => 'Combos', 'icon' => 'shopping-bag', 'url' => route('shop.categories')],
        ['label' => 'Account', 'icon' => 'user', 'url' => auth()->check() ? route('account.dashboard') : route('login')],
    ]); @endphp
    <nav class="md:hidden fixed bottom-0 inset-x-0 z-50 bg-white border-t border-sage-100 shadow-[0_-2px_10px_rgba(0,0,0,0.06)] pb-[env(safe-area-inset-bottom)]" aria-label="Mobile navigation" x-data>
        <div class="grid grid-cols-5 h-16 text-[10px] font-semibold tracking-wide">
            @foreach($navItems as $ni)
                @php $active = request()->url() === $ni['url'] || (($ni['label'] === 'Account') && request()->routeIs('account.*')); @endphp
                <a href="{{ $ni['url'] }}" class="relative flex flex-col items-center justify-center gap-1 transition-colors {{ $active ? 'text-anv-600' : 'text-charcoal-600' }}">
                    <x-lucide-{{ $ni['icon'] }} class="h-[22px] w-[22px]"/>
                    @if($ni['label'] === 'Cart')
                        <span x-text="$store.cart.count" x-show="$store.cart.count > 0" x-cloak class="absolute top-2 right-[calc(50%-18px)] h-4 min-w-4 px-1 rounded-full bg-anv-600 text-white text-[10px] font-bold grid place-items-center leading-none"></span>
                    @endif
                    <span>{{ $ni['label'] }}</span>
                </a>
            @endforeach
        </div>
    </nav>

    {{-- ═══ STICKY REWARDS BAR + POPUP ═══ --}}
    @php
        $rwEnabled   = setting('display.rewards_enabled', '1');
        $rwMainline  = setting('display.rewards_mainline', 'Earn rewards on every order!');
        $rwCoins     = setting('display.rewards_coins', '0');
        $rwSubline   = setting('display.rewards_subline', 'Your rewards await');
        $rwItems     = setting_json('display.rewards_items', [
            ['title' => 'Sign up', 'points' => '50 pts'],
            ['title' => 'Every ₹100 spent', 'points' => '10 pts'],
            ['title' => 'Refer a friend', 'points' => '100 pts'],
        ]);
    @endphp
    @if($rwEnabled === '1' || $rwEnabled === true)
    <div x-data="{ popup: false, hide: false }" @scroll.window="hide = window.scrollY > 600" class="hidden md:block" id="rewards-bar-wrapper">
        <button type="button" @click="popup = true"
                :class="hide ? 'scroll-hidden' : ''"
                class="anv-rewards-bar fixed bottom-0 inset-x-0 z-40 flex items-center justify-center gap-2 bg-anv-700 text-white text-sm font-semibold py-2.5 shadow-lg">
            <x-lucide-gift class="h-4 w-4 text-gold-300" />
            <span>{{ $rwMainline }}</span>
            <span class="ml-2 inline-flex items-center gap-1 rounded-full bg-gold-300 px-2.5 py-0.5 text-xs font-extrabold text-anv-800"><x-lucide-coins class="h-3 w-3" />{{ $rwCoins }}</span>
        </button>
    </div>
    <div x-data="{ popup: false }" id="rewards-bar-wrapper-mobile">
        <button type="button" @click="popup = true" class="fixed bottom-[4.5rem] inset-x-0 z-40 bg-anv-700 text-white text-xs font-semibold py-2 text-center md:hidden shadow-lg">
            <x-lucide-gift class="inline h-3.5 w-3.5 text-gold-300" /> {{ $rwMainline }} <span class="inline-flex items-center gap-1 rounded-full bg-gold-300 px-2 py-0.5 text-[10px] font-extrabold text-anv-800">{{ $rwCoins }} pts</span>
        </button>
        <div x-show="popup" x-cloak @keydown.escape.window="popup=false" class="fixed inset-0 z-[80] grid place-items-center bg-black/40 p-4" @click.self="popup=false">
            <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl">
                <div class="flex items-start justify-between">
                    <h3 class="font-display text-lg font-bold text-anv-800">Rewards</h3>
                    <button @click="popup=false" class="text-charcoal-600/60 hover:text-charcoal-900"><x-lucide-x class="h-5 w-5"/></button>
                </div>
                <p class="mt-1 text-sm text-charcoal-600/60">{{ $rwSubline }}</p>
                <div class="mt-4 space-y-2">
                    @foreach($rwItems as $item)
                        <div class="flex items-center justify-between rounded-xl bg-leaf-50 px-4 py-3">
                            <span class="text-sm font-medium text-charcoal-800">{{ $item['title'] }}</span>
                            <span class="inline-flex items-center gap-1 rounded-full bg-gold-100 px-2.5 py-1 text-xs font-extrabold text-gold-600">{{ $item['points'] }}</span>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('register') }}" class="mt-5 block rounded-xl bg-anv-600 py-3 text-center text-sm font-bold text-white transition hover:bg-anv-700">Earn rewards now</a>
            </div>
        </div>
    </div>
    @endif

    {{-- ═══ WHATSAPP WIDGET ═══ --}}
    @php
        $waEnabled  = setting('display.whatsapp_enabled', '0');
        $waNumber   = setting('display.whatsapp_number', '919999999999');
        $waMessage  = setting('display.whatsapp_message', 'Hi! I have a question about your products.');
    @endphp
    @if($waEnabled === '1' || $waEnabled === true)
    <div x-data="{ open: false }" class="fixed bottom-[5.5rem] right-4 z-40 md:bottom-6">
        <div x-show="open" x-cloak x-transition class="mb-3 w-72 rounded-2xl overflow-hidden shadow-2xl" style="background:#ECE5DD">
            <div class="bg-[#075E54] px-4 py-3 flex items-center gap-2 text-white">
                <span class="grid h-8 w-8 place-items-center rounded-full bg-white/20 text-lg">🌿</span>
                <div><p class="text-sm font-bold leading-tight">AB Organic Farm</p><p class="text-[10px] text-green-100">Online now</p></div>
            </div>
            <div class="p-3">
                <div class="rounded-[15px] bg-white p-3 text-sm text-charcoal-800 shadow">
                    Hi there 👋 How can we help you today?
                    <span class="mt-1 block text-right text-[9px] text-charcoal-600/40">12:00</span>
                </div>
            </div>
            <a href="https://wa.me/{{ preg_replace('/\D+/', '', $waNumber) }}?text={{ urlencode($waMessage) }}" target="_blank" rel="noopener"
               class="block bg-[#25D366] py-3 text-center text-sm font-bold text-white transition hover:bg-[#1ebe5d]">Chat on WhatsApp</a>
        </div>
        <button @click="open = !open" class="grid h-12 w-12 place-items-center rounded-full text-white shadow-lg transition"
                style="background:linear-gradient(135deg,#25D366,#128C7E)">
            <x-lucide-message-circle class="h-6 w-6"/>
        </button>
    </div>
    @endif

    {{-- ═══ FLOATING CART PILL (Blinkit-style) ═══ --}}
    @php $showFloat = !request()->routeIs(['cart.*','checkout','shop.product']); @endphp
    @if($showFloat)
    <a href="{{ route('cart.index') }}" x-cloak x-show="$store.cart.count > 0"
       class="fixed inset-x-4 top-auto bottom-[5.5rem] z-40 mx-auto grid h-14 max-w-md overscroll-none grid-cols-[1fr_auto] items-center gap-3 rounded-full bg-gradient-to-r from-anv-600 to-anv-700 px-5 text-white shadow-2xl ring-1 ring-white/20 md:bottom-6"
       style="transition: transform .3s ease" :style="(window.innerWidth >= 768 && window.scrollY > 600) ? 'transform:translateY(120%)' : ''">
        <span class="flex items-center gap-3">
            <span class="grid h-8 w-8 place-items-center rounded-full bg-white/20"><x-lucide-shopping-basket class="h-5 w-5"/></span>
            <span class="text-left leading-tight">
                <span class="block text-lg font-extrabold leading-none" x-text="$store.cart.count + ' item' + ($store.cart.count>1?'s':'')"></span>
                <span class="block text-[11px] text-white/70">Total <b x-text="'₹' + ($store.cart.total || '')"></b></span>
            </span>
        </span>
        <span class="grid h-10 place-items-center rounded-full bg-gold-300 px-5 text-sm font-extrabold text-anv-800" x-text="$store.cart.count > 0 ? 'View Cart →' : ''"></span>
    </a>
    @endif

    {{-- ═══ VARIANT SELECTOR MODAL ═══ --}}
    <div x-data="variantModal()">
        <div x-show="open" x-cloak x-transition @keydown.escape.window="close()" class="fixed inset-0 z-[90] grid place-items-end bg-black/40 sm:place-items-center" @click.self="close()">
            <div class="w-full max-w-md rounded-t-2xl bg-white shadow-2xl sm:my-8 sm:rounded-2xl" @click.outside="close()">
                <div class="flex items-center justify-between border-b border-leaf-100 px-5 py-4">
                    <div>
                        <h3 class="font-display text-base font-bold text-anv-800" x-text="name"></h3>
                        <p class="text-xs text-charcoal-600/50">Select a weight / pack size</p>
                    </div>
                    <button @click="close()" class="text-charcoal-600/60 hover:text-charcoal-900"><x-lucide-x class="h-5 w-5"/></button>
                </div>
                <div class="max-h-[60vh] overflow-y-auto p-4">
                    <template x-for="v in variants" :key="v.id">
                        <div class="flex items-center gap-3 rounded-xl border border-sage-100 p-3" x-data="variantRow(v, v.available)">
                            <img :src="v.image || '/images/placeholder.png'" class="h-14 w-14 rounded-lg object-cover bg-leaf-50">
                            <div class="flex-1 leading-tight">
                                <p class="text-sm font-semibold text-charcoal-900" x-text="v.label"></p>
                                <p class="mt-0.5 text-sm font-extrabold text-charcoal-900"><span x-text="'₹' + v.sale"></span>
                                    <s class="ml-1 text-xs font-medium text-[#9AA79F]" x-show="v.price > v.sale" x-text="v.price > v.sale ? '₹'+v.price : ''"></s>
                                </p>
                                <p class="text-[10px] font-bold text-gold-600" x-show="v.available">Best Price ₹<span x-text="v.sale"></span></p>
                            </div>
                            <template x-if="!v.available">
                                <span class="rounded-full border border-[#CF9726] px-3 py-1 text-[10px] font-bold uppercase text-[#CF9726]">Notify Me</span>
                            </template>
                            <template x-if="v.available && qty === 0">
                                <button @click="add()" class="flex items-center gap-1 rounded-full border-2 border-anv-600 px-4 py-1.5 text-xs font-extrabold uppercase text-anv-600 transition hover:bg-anv-600 hover:text-white">ADD<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="h-3.5 w-3.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg></button>
                            </template>
                            <template x-if="v.available && qty > 0">
                                <div class="flex items-center gap-2 rounded-full bg-anv-600 px-3 py-1 text-white">
                                    <button @click="minus()" class="text-lg font-bold leading-none">−</button>
                                    <span x-text="qty" class="text-sm font-bold"></span>
                                    <button @click="add()" class="text-lg font-bold leading-none">+</button>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ NOTIFY-ME MODAL ═══ --}}
    <div x-data="notifyModal()">
        <div x-show="open" x-cloak x-transition @keydown.escape.window="close()" class="fixed inset-0 z-[90] grid place-items-center bg-black/40 p-4" @click.self="close()">
            <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl">
                <template x-if="!submitted">
                    <div>
                        <div class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-gold-100 text-gold-600">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-6 w-6"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                        </div>
                        <h3 class="mt-4 text-center font-display text-lg font-bold text-anv-800">Get notified when it's back</h3>
                        <p class="mt-1 text-center text-sm text-charcoal-600/60">We'll email you the moment <b x-text="name"></b> is back in stock.</p>
                        <input x-model="email" type="email" placeholder="Enter your email"
                               class="mt-4 w-full rounded-full border-2 border-sage-100 px-5 py-3 text-sm focus:border-anv-500 focus:outline-none">
                        <p x-show="error" x-text="error" class="mt-2 text-xs text-clay-600" x-cloak></p>
                        <button @click="submit()" :disabled="busy" class="mt-3 w-full rounded-full bg-anv-600 py-3 text-sm font-bold text-white transition hover:bg-anv-700"
                                x-text="busy ? 'Submitting…' : 'Notify Me'"></button>
                    </div>
                </template>
                <template x-if="submitted">
                    <div class="text-center">
                        <div class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-leaf-100 text-anv-600 text-2xl">🔔</div>
                        <h3 class="mt-4 font-display text-lg font-bold text-anv-800">You're on the list!</h3>
                        <p class="mt-1 text-sm text-charcoal-600/60">We'll ping you when it's available again.</p>
                        <button @click="close()" class="mt-5 w-full rounded-full bg-anv-600 py-3 text-sm font-bold text-white transition hover:bg-anv-700">Done</button>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <x-ui.toaster/>
</body>
</html>