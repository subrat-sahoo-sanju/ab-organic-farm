<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'AB Organic Farm' }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>[x-cloak]{display:none!important}</style>
    @stack('head')
    @stack('meta')
</head>
<body class="min-h-screen flex flex-col bg-cream-50 pb-20 md:pb-0">

    @if(session('success') || session('error'))
        <div id="flash-data" data-success="{{ session('success') }}" data-error="{{ session('error') }}" hidden></div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════
         HEADER — Sticky top, Blinkit-style
    ═══════════════════════════════════════════════════════════════ --}}
    <header class="sticky top-0 z-40 bg-white shadow-sm">

        {{-- Top Bar --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center gap-3 md:gap-5">

            {{-- Logo --}}
            <a href="{{ route('shop.index') }}" class="flex items-center gap-2 shrink-0">
                <span class="h-9 w-9 rounded-lg bg-forest-600 text-white grid place-items-center">
                    <x-lucide-leaf class="h-5 w-5"/>
                </span>
                <span class="font-display text-lg font-bold text-forest-700 leading-tight hidden sm:block">AB Organic<br><span class="text-xs font-medium text-charcoal-600/60 tracking-wide">FARM</span></span>
            </a>

            {{-- Search Bar (desktop + tablet) --}}
            <form action="{{ route('shop.search') }}" method="GET" class="hidden md:flex flex-1 max-w-2xl mx-auto">
                <div class="relative w-full group">
                    <x-lucide-search class="absolute left-4 top-1/2 -translate-y-1/2 h-[18px] w-[18px] text-charcoal-600/40 group-focus-within:text-forest-600 transition-colors"/>
                    <input
                        type="search"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Search for organic products..."
                        class="w-full h-11 pl-11 pr-5 rounded-full border-2 border-cream-200 bg-cream-50 text-sm placeholder:text-charcoal-600/40 focus:border-forest-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-forest-500/20 transition-all"
                    >
                </div>
            </form>

            {{-- Right Actions --}}
            <nav class="flex items-center gap-1 sm:gap-2 ml-auto shrink-0" aria-label="Main">

                {{-- Wishlist (desktop) --}}
                <a href="{{ route('account.wishlist') }}"
                   class="relative hidden sm:flex items-center gap-1.5 p-2.5 rounded-full hover:bg-cream-100 text-charcoal-700 transition-colors"
                   aria-label="Wishlist">
                    <x-lucide-heart class="h-5 w-5"/>
                    @if(auth()->check() && $wishlistCount = auth()->user()->wishlistItems()->count())
                        <span class="absolute -top-0.5 -right-0.5 h-5 min-w-5 px-1 rounded-full bg-clay-500 text-white text-[11px] font-bold grid place-items-center">{{ $wishlistCount }}</span>
                    @endif
                </a>

                {{-- Cart --}}
                <a href="{{ route('cart.index') }}"
                   class="relative flex items-center gap-1.5 p-2.5 rounded-full hover:bg-cream-100 text-charcoal-700 transition-colors"
                   aria-label="Cart" x-data x-cloak>
                    <x-lucide-shopping-basket class="h-5 w-5"/>
                    <span id="cart-badge"
                          x-text="$store.cart.count"
                          x-show="$store.cart.count > 0"
                          class="absolute -top-0.5 -right-0.5 h-5 min-w-5 px-1 rounded-full bg-forest-600 text-white text-[11px] font-bold grid place-items-center">
                    </span>
                </a>

                {{-- Auth: User dropdown / Login + Sign Up --}}
                @auth
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button class="flex items-center gap-2 pl-1 pr-3 py-1 rounded-full hover:bg-cream-100 transition-colors"
                                @click="open = !open" aria-label="Account menu">
                            <span class="h-8 w-8 rounded-full bg-forest-600 text-white grid place-items-center text-sm font-bold uppercase shadow-sm">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </span>
                            <span class="hidden lg:block text-sm font-medium text-charcoal-700 max-w-[8rem] truncate">{{ auth()->user()->name }}</span>
                            <x-lucide-chevron-down class="h-4 w-4 hidden sm:block text-charcoal-600/60"/>
                        </button>

                        {{-- Dropdown --}}
                        <div x-show="open" x-transition.opacity.duration.150ms x-cloak
                             class="absolute right-0 mt-2 w-60 rounded-2xl bg-white shadow-xl ring-1 ring-black/5 overflow-hidden py-2 z-50">
                            <div class="px-4 py-3 border-b border-cream-100">
                                <p class="text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-charcoal-600/50 truncate mt-0.5">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('account.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-cream-50 transition-colors">
                                <x-lucide-user class="h-4 w-4 text-charcoal-600/50"/> My Account
                            </a>
                            <a href="{{ route('account.orders') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-cream-50 transition-colors">
                                <x-lucide-shopping-basket class="h-4 w-4 text-charcoal-600/50"/> My Orders
                            </a>
                            <a href="{{ route('account.wishlist') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-cream-50 transition-colors">
                                <x-lucide-heart class="h-4 w-4 text-charcoal-600/50"/> Wishlist
                            </a>
                            @if(auth()->user()->isStaff())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-forest-700 hover:bg-cream-50 transition-colors">
                                    <x-lucide-layout-grid class="h-4 w-4"/> Admin Panel
                                </a>
                            @endif
                            <div class="border-t border-cream-100 mt-1 pt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="w-full text-left flex items-center gap-3 px-4 py-2.5 text-sm text-clay-600 hover:bg-cream-50 transition-colors">
                                        <x-lucide-menu class="h-4 w-4"/> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                       class="text-sm font-semibold px-4 py-2 rounded-full border-2 border-forest-600 text-forest-700 hover:bg-forest-50 transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="hidden sm:inline-flex items-center h-9 px-5 rounded-full bg-forest-600 text-white text-sm font-semibold hover:bg-forest-700 shadow-sm transition-colors">
                        Sign Up
                    </a>
                @endauth
            </nav>
        </div>

        {{-- Mobile Search Bar --}}
        <div class="md:hidden px-4 pb-3">
            <form action="{{ route('shop.search') }}" method="GET">
                <div class="relative w-full group">
                    <x-lucide-search class="absolute left-4 top-1/2 -translate-y-1/2 h-[18px] w-[18px] text-charcoal-600/40 group-focus-within:text-forest-600 transition-colors"/>
                    <input
                        type="search"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Search for organic products..."
                        class="w-full h-11 pl-11 pr-5 rounded-full border-2 border-cream-200 bg-cream-50 text-sm placeholder:text-charcoal-600/40 focus:border-forest-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-forest-500/20 transition-all"
                    >
                </div>
            </form>
        </div>

        {{-- Category Pills Row --}}
        <div class="border-t border-cream-100 bg-white/80 backdrop-blur-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex gap-2 overflow-x-auto no-scrollbar py-2.5">
                <a href="{{ route('shop.categories') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-medium whitespace-nowrap transition-all
                          {{ request()->routeIs('shop.categories*') ? 'bg-forest-600 text-white shadow-sm' : 'bg-cream-100 text-charcoal-700 hover:bg-cream-200' }}">
                    <x-lucide-layout-grid class="h-3.5 w-3.5"/>
                    All
                </a>
                @foreach($categories ?? [] as $cat)
                    <a href="/categories/{{ $cat->slug }}"
                       class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium whitespace-nowrap transition-all
                              {{ request()->is('categories/'.$cat->slug) ? 'bg-forest-600 text-white shadow-sm' : 'bg-cream-100 text-charcoal-700 hover:bg-cream-200' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </header>

    {{-- ═══════════════════════════════════════════════════════════════
         MAIN CONTENT
    ═══════════════════════════════════════════════════════════════ --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- ═══════════════════════════════════════════════════════════════
         FOOTER — Dark forest, 4-col
    ═══════════════════════════════════════════════════════════════ --}}
    <footer class="mt-16 bg-[#0C211A] text-emerald-100/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid gap-10 sm:grid-cols-2 lg:grid-cols-4 text-sm">

            {{-- Brand Info --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="h-8 w-8 rounded-lg bg-forest-600 text-white grid place-items-center">
                        <x-lucide-leaf class="h-4 w-4"/>
                    </span>
                    <span class="font-display text-base font-bold text-white">AB Organic Farm</span>
                </div>
                <p class="leading-relaxed text-emerald-100/60">{{ setting('footer.tagline', setting('store.tagline', 'Certified organic products from partner farms, delivered fresh to your home.')) }}</p>
            </div>

            {{-- Shop --}}
            @php $footerShopLinks = setting_json('footer.links_shop', [
                ['label' => 'Fresh Fruits', 'url' => '/categories/fresh-fruits'],
                ['label' => 'Fresh Vegetables', 'url' => '/categories/fresh-vegetables'],
                ['label' => 'Rice & Grains', 'url' => '/categories/rice-grains-flour'],
                ['label' => 'Oils & Ghee', 'url' => '/categories/oils-ghee'],
                ['label' => 'Dry Fruits', 'url' => '/categories/dry-fruits-nuts'],
                ['label' => 'All Categories', 'url' => route('shop.categories')],
            ]); @endphp
            @if(count($footerShopLinks))
            <div>
                <h4 class="font-semibold text-white mb-4">{{ setting('footer.shop_title', 'Shop') }}</h4>
                <ul class="space-y-2.5">
                    @foreach($footerShopLinks as $link)
                        <li><a href="{{ $link['url'] }}" class="hover:text-white transition-colors">{{ $link['label'] }}</a></li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Account --}}
            @php $footerAccountLinks = setting_json('footer.links_account', [
                ['label' => 'My Account', 'url' => route('account.dashboard')],
                ['label' => 'My Orders', 'url' => route('account.orders')],
                ['label' => 'Wishlist', 'url' => route('account.wishlist')],
                ['label' => 'Cart', 'url' => route('cart.index')],
            ]); @endphp
            <div>
                <h4 class="font-semibold text-white mb-4">{{ setting('footer.account_title', 'Account') }}</h4>
                <ul class="space-y-2.5">
                    @foreach($footerAccountLinks as $link)
                        <li><a href="{{ $link['url'] }}" class="hover:text-white transition-colors">{{ $link['label'] }}</a></li>
                    @endforeach
                    @auth
                        @if(auth()->user()->isStaff())
                            <li><a href="{{ route('admin.dashboard') }}" class="hover:text-white transition-colors font-medium">Admin Panel</a></li>
                        @endif
                    @endauth
                </ul>
            </div>

            {{-- Payment --}}
            <div>
                <h4 class="font-semibold text-white mb-4">{{ setting('footer.payment_title', 'We Accept') }}</h4>
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-xl px-4 py-2.5 font-semibold tracking-wide text-white">
                    <x-lucide-banknote class="h-5 w-5"/>
                    Cash on Delivery
                </div>
                <p class="mt-3 text-xs text-emerald-100/50">{{ setting('footer.payment_text', 'Pay when your order arrives at your door. Secure & hassle-free.') }}</p>
            </div>
        </div>

        {{-- Copyright --}}
        <div class="border-t border-white/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-emerald-100/40">
                <span>&copy; {{ now()->year }} AB Organic Farm — Fresh · Organic · Trusted</span>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-emerald-100/70 transition-colors">Privacy</a>
                    <a href="#" class="hover:text-emerald-100/70 transition-colors">Terms</a>
                    <a href="#" class="hover:text-emerald-100/70 transition-colors">Contact</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- ═══════════════════════════════════════════════════════════════
         MOBILE BOTTOM NAV — Blinkit-style 5-tab
    ═══════════════════════════════════════════════════════════════ --}}
    <nav class="md:hidden fixed bottom-0 inset-x-0 z-50 bg-white border-t border-cream-200 shadow-[0_-2px_10px_rgba(0,0,0,0.06)] pb-[env(safe-area-inset-bottom)]"
         aria-label="Mobile navigation" x-data>
        <div class="grid grid-cols-5 h-16 text-[10px] font-semibold tracking-wide">
            <a href="{{ route('shop.index') }}"
               class="flex flex-col items-center justify-center gap-1 transition-colors {{ request()->routeIs('shop.index') ? 'text-forest-600' : 'text-charcoal-600' }}">
                <x-lucide-home class="h-[22px] w-[22px]"/>
                <span>Home</span>
            </a>
            <a href="{{ route('shop.categories') }}"
               class="flex flex-col items-center justify-center gap-1 transition-colors {{ request()->routeIs('shop.categories*') ? 'text-forest-600' : 'text-charcoal-600' }}">
                <x-lucide-layout-grid class="h-[22px] w-[22px]"/>
                <span>Categories</span>
            </a>
            <a href="{{ route('shop.search-page') }}"
               class="flex flex-col items-center justify-center gap-1 transition-colors {{ request()->routeIs('shop.search*') ? 'text-forest-600' : 'text-charcoal-600' }}">
                <x-lucide-search class="h-[22px] w-[22px]"/>
                <span>Search</span>
            </a>
            <a href="{{ route('cart.index') }}"
               class="relative flex flex-col items-center justify-center gap-1 transition-colors {{ request()->routeIs('cart*') ? 'text-forest-600' : 'text-charcoal-600' }}">
                <x-lucide-shopping-basket class="h-[22px] w-[22px]"/>
                <span x-text="$store.cart.count"
                      x-show="$store.cart.count > 0"
                      class="absolute top-2 right-[calc(50%-18px)] h-4 min-w-4 px-1 rounded-full bg-forest-600 text-white text-[10px] font-bold grid place-items-center leading-none"></span>
                <span>Cart</span>
            </a>
            <a href="{{ auth()->check() ? route('account.dashboard') : route('login') }}"
               class="flex flex-col items-center justify-center gap-1 transition-colors text-charcoal-600">
                <x-lucide-user class="h-[22px] w-[22px]"/>
                <span>Account</span>
            </a>
        </div>
    </nav>

    <x-ui.toaster/>
</body>
</html>
