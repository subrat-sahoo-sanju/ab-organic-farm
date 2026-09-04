@php
    $navItems = setting_json('display.nav_menu', []);
    $homeIcon = setting('display.logo', '');
    $storeName = setting('store.name', 'AB Organic');
    $tagline = setting('store.tagline', 'FARM');
@endphp

<style>
  .anv-nav-panel{background:#fff}
  .anv-nav-link{display:flex;align-items:center;gap:8px;padding:4px 10px;font-size:14px;font-weight:500;color:#2c2c2c;text-decoration:none;transition:color .15s ease;white-space:nowrap}
  .anv-nav-link:hover{color:#1F5C3F}
  .anv-nav-link img{width:22px;height:22px;object-fit:contain}
  .nav-rainbow{background:linear-gradient(90deg,#f5350a,#d8296b,#6344b7,#2e7d32);-webkit-background-clip:text;background-clip:text;color:transparent;font-weight:700}
  .anv-drawer{position:fixed;inset:0;z-index:90;visibility:hidden;transition:visibility 0s .4s}
  .anv-drawer.open{visibility:visible;transition:none}
  .anv-drawer-backdrop{position:absolute;inset:0;background:rgba(0,0,0,.45);opacity:0;transition:opacity .35s ease}
  .anv-drawer.open .anv-drawer-backdrop{opacity:1}
  .anv-drawer-panel{position:absolute;top:0;left:0;bottom:0;width:min(340px,88vw);background:#fff;transform:translateX(-100%);transition:transform .35s cubic-bezier(.4,0,.2,1);display:flex;flex-direction:column;overflow:hidden}
  .anv-drawer.open .anv-drawer-panel{transform:none}
  .anv-drawer-scroll{flex:1;overflow-y:auto;overscroll-behavior:contain}
  .anv-drawer-item{display:flex;align-items:center;gap:14px;padding:13px 20px;font-size:14px;font-weight:500;color:#2c2c2c;text-decoration:none;border-bottom:1px solid #f1f1f1}
  .anv-drawer-item:hover{background:#f7f7f7}
  .anv-drawer-item img{width:24px;height:24px;object-fit:contain}
  .anv-drawer-sub{margin-left:50px;padding:8px 0;border-bottom:1px solid #f1f1f1}
  .anv-drawer-sub a{display:block;padding:8px 18px;font-size:13px;color:#555;text-decoration:none}
  .anv-drawer-sub a:hover{color:#1F5C3F}
  .anv-submenu{position:absolute;top:100%;left:0;min-width:230px;background:#fff;border:1px solid #eee;border-top:2px solid #1F5C3F;border-radius:0 0 8px 8px;box-shadow:0 18px 40px -18px rgba(0,0,0,.25);padding:8px 0;opacity:0;visibility:hidden;transform:translateY(8px);transition:all .2s ease}
  .anv-nav-hasmenu:hover .anv-submenu,.anv-nav-hasmenu:focus-within .anv-submenu{opacity:1;visibility:visible;transform:none}
  .anv-submenu a{position:relative;display:flex;align-items:center;gap:10px;padding:9px 18px;font-size:13px;color:#444;text-decoration:none;transition:all .12s ease}
  .anv-submenu a:hover{color:#1F5C3F;background:#f6f9f7;padding-left:22px}
  .anv-submenu a img{width:20px;height:20px;object-fit:contain}
  .anv-search-toggle{display:flex;align-items:center;justify-content:center;width:40px;height:40px;border-radius:9999px;color:#242424;transition:background .15s ease}
  .anv-search-toggle:hover{background:#EAF4EE}

  {{-- ═══ LIVE SEARCH PANEL ═══ --}}
  .anv-search-panel{position:fixed;left:0;right:0;top:0;bottom:0;z-index:95;background:rgba(244,240,233,.94);backdrop-filter:blur(6px);overflow-y:auto;visibility:hidden;opacity:0;transition:opacity .22s ease,visibility 0s .22s}
  .anv-search-panel.open{visibility:visible;opacity:1;transition:opacity .22s ease}
  .anv-search-card{width:min(680px,94vw);margin:56px auto 0;background:#fff;border:1px solid #eadfc9;border-top:2px solid #1F5C3F;border-radius:20px;box-shadow:0 26px 60px -20px rgba(20,60,40,.4);transform:translateY(10px);transition:transform .25s cubic-bezier(.34,1.56,.64,1)}
  .anv-search-panel.open .anv-search-card{transform:none}
  .anv-sr-head{font-size:16px;font-weight:800;color:#173F2A;letter-spacing:.2px}
  .anv-sr-hint{font-size:12px;color:#A9A9A9}
  .anv-sr-item{display:flex;align-items:center;gap:12px;padding:10px 14px;border-radius:12px;transition:background .15s}
  .anv-sr-item:hover{background:#EAF4EE}
  .anv-sr-img{width:46px;height:46px;border-radius:10px;object-fit:cover;background:#f3efe7;flex-shrink:0}
  .anv-sr-name{font-size:14px;font-weight:600;color:#2f2a24;line-height:1.25}
  .anv-sr-cat{font-size:11px;color:#8a8578}
  .anv-sr-sale{font-size:15px;font-weight:800;color:#1F5C3F}
  .anv-sr-price{font-size:12px;color:#a9a9a9}
  .anv-sr-loading{display:flex;align-items:center;gap:10px;padding:16px;color:#1F5C3F;font-weight:600;font-size:13px}
  .anv-sr-empty{display:flex;align-items:center;gap:10px;padding:16px;color:#666;font-size:13px}
  .anv-sr-spin{width:16px;height:16px;border:2px solid #1F5C3F;border-top-color:transparent;border-radius:50%;animation:anvSpin .7s linear infinite}
  @keyframes anvSpin{to{transform:rotate(360deg)}}
  .anv-chip{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:9999px;border:1.5px solid #d9c9a8;background:#fff;color:#5a4a2f;font-size:13px;font-weight:600;cursor:pointer;transition:all .15s}
  .anv-chip:hover{border-color:#1F5C3F;color:#1F5C3F;background:#EAF4EE}
</style>

{{-- ════════════════════════════════════════════
     TOP HEADER BAR  (Sticky)
════════════════════════════════════════════ --}}
<header class="sticky top-0 z-40 bg-white shadow-sm" x-data="headerSearch()">
  <div class="mx-auto flex h-16 max-w-[1440px] items-center gap-2 px-4 sm:gap-4 sm:px-6 lg:px-8">

    {{-- Mobile hamburger --}}
    <button type="button" class="lg:hidden flex items-center justify-center p-2 -ml-2 text-[#242424]" @click="drawer = true" aria-label="Open menu">
      <svg width="26" height="24" viewBox="0 0 18 16" fill="currentColor"><path d="M1 .5a.5.5 0 1 0 0 1h15.71a.5.5 0 0 0 0-1zM.5 8a.5.5 0 0 1 .5-.5h15.71a.5.5 0 0 1 0 1H1A.5.5 0 0 1 .5 8m0 7a.5.5 0 0 1 .5-.5h15.71a.5.5 0 0 1 0 1H1a.5.5 0 0 1-.5-.5z"/></svg>
    </button>

    {{-- Logo --}}
    {{-- Desktop inline menu is centered-ish; keep logo left per reference --}}
    <a href="{{ route('shop.index') }}" class="flex shrink-0 items-center gap-2">
      @if($homeIcon)
        <img src="{{ asset('storage/'.$homeIcon) }}" alt="{{ $storeName }} logo" class="h-11 w-auto max-w-[210px] object-contain sm:h-12 sm:max-w-[240px]">
      @else
        <span class="grid h-9 w-9 place-items-center rounded-lg bg-anv-600 text-white"><x-lucide-leaf class="h-5 w-5"/></span>
        <span class="hidden font-display text-lg font-bold leading-tight text-anv-700 sm:block">{{ $storeName }}<br><span class="text-xs font-medium tracking-wide text-charcoal-600/60">{{ $tagline }}</span></span>
      @endif
    </a>

    {{-- Desktop inline nav --}}
    <nav class="mx-auto hidden items-center lg:flex" aria-label="Main" x-data="{ open: null }">
      @forelse($navItems as $item)
        @php $children = collect($item['children'] ?? []); @endphp
        @if($children->count())
          <div class="anv-nav-hasmenu relative" @mouseenter="open = '{{ $loop->index }}'" @mouseleave="open = null">
            <a href="{{ $item['url'] ?? '#' }}" class="anv-nav-link {{ !empty($item['highlight']) ? 'nav-rainbow' : '' }}">
              @if(!empty($item['icon']))<img src="{{ asset('images/nav/'.$item['icon'].'.svg') }}" alt="" onerror="this.style.display='none'">@endif
              <span>{{ $item['label'] }}</span>
              <svg width="10" height="6" viewBox="0 0 10 6" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M9.354.646a.5.5 0 0 0-.708 0L5 4.293 1.354.646a.5.5 0 0 0-.708.708l4 4a.5.5 0 0 0 .708 0l4-4a.5.5 0 0 0 0-.708z"/></svg>
            </a>
            <div class="anv-submenu" x-show="open === '{{ $loop->index }}'" x-cloak x-transition>
              @foreach($children as $child)
                <a href="{{ $child['url'] ?? '#' }}">{{ $child['label'] }}</a>
              @endforeach
            </div>
          </div>
        @else
          <a href="{{ $item['url'] ?? '#' }}" class="anv-nav-link {{ !empty($item['highlight']) ? 'nav-rainbow' : '' }}">
            @if(!empty($item['icon']))<img src="{{ asset('images/nav/'.$item['icon'].'.svg') }}" alt="" onerror="this.style.display='none'">@endif
            <span>{{ $item['label'] }}</span>
          </a>
        @endif
      @empty
      @endforelse
    </nav>

    {{-- Right icons: search · wishlist · cart · account --}}
    <div class="ml-auto flex shrink-0 items-center gap-0.5 sm:gap-1 lg:ml-0">
      <button type="button" class="anv-search-toggle" @click="openSearch()" aria-label="Search">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><circle cx="9.75" cy="9.75" r="8.25" stroke="#C9A227" stroke-width="2"/><path d="M15.6 15.6 22.5 22.5" stroke="#C9A227" stroke-width="2" stroke-linecap="round"/></svg>
      </button>

      <a href="{{ route('account.wishlist') }}" class="hidden items-center justify-center rounded-full p-2.5 text-[#7C522A] transition-colors hover:bg-[#eef5f0] sm:flex" aria-label="Wishlist">
        <x-lucide-heart class="h-5 w-5"/>
      </a>

      <button type="button" data-cart-anchor class="relative flex items-center justify-center rounded-full p-2.5 text-[#7C522A] transition-colors hover:bg-[#eef5f0]" aria-label="Cart"
              @click="window.dispatchEvent(new CustomEvent('anv:cart-drawer-open'))">
        <x-lucide-shopping-basket class="h-5 w-5"/>
        <span x-text="$store.cart.count" x-show="$store.cart.count > 0" x-cloak class="absolute -right-0.5 -top-0.5 h-5 min-w-5 rounded-full bg-anv-600 px-1 text-center text-[11px] font-bold text-white grid place-items-center"></span>
      </button>

      @auth
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
          <button class="flex items-center gap-1 rounded-full p-1.5 transition-colors hover:bg-[#eef5f0]" @click="open = !open" aria-label="Account menu">
            <span class="grid h-8 w-8 place-items-center rounded-full bg-anv-600 text-sm font-bold uppercase text-white shadow-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
          </button>
          <div x-show="open" x-transition.opacity.duration.150ms x-cloak class="absolute right-0 mt-2 w-60 rounded-2xl bg-white py-2 shadow-xl ring-1 ring-black/5">
            <div class="border-b border-leaf-100 px-4 py-3">
              <p class="text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
              <p class="mt-0.5 truncate text-xs text-charcoal-600/50">{{ auth()->user()->email }}</p>
            </div>
            <a href="{{ route('account.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-leaf-50"><x-lucide-user class="h-4 w-4 text-charcoal-600/50"/> My Account</a>
            <a href="{{ route('account.orders') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-leaf-50"><x-lucide-shopping-basket class="h-4 w-4 text-charcoal-600/50"/> My Orders</a>
            <a href="{{ route('account.wishlist') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-leaf-50"><x-lucide-heart class="h-4 w-4 text-charcoal-600/50"/> Wishlist</a>
            @if(auth()->user()->isStaff())
              <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-anv-700 hover:bg-leaf-50"><x-lucide-layout-grid class="h-4 w-4"/> Admin Panel</a>
            @endif
            <div class="mt-1 border-t border-leaf-100 pt-1">
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="flex w-full items-center gap-3 px-4 py-2.5 text-left text-sm text-clay-600 hover:bg-leaf-50"><x-lucide-menu class="h-4 w-4"/> Logout</button>
              </form>
            </div>
          </div>
        </div>
      @else
        <a href="{{ route('login') }}" class="ml-1 hidden rounded-full px-4 py-2 text-sm font-semibold text-anv-700 transition-colors hover:bg-[#eef5f0] sm:block">{{ __('Login') }}</a>
      @endauth
    </div>
  </div>

  {{-- Mobile search row — opens the live-search panel --}}
  <div class="px-4 pb-3 lg:hidden">
    <button type="button" @click="openSearch()" class="flex w-full items-center gap-3 rounded-full border-2 border-sage-100 bg-leaf-50 px-4 py-3 text-left text-sm text-charcoal-600/70 transition hover:border-anv-500 hover:bg-white">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9.75" cy="9.75" r="6.5"/><path d="m15.3 15.3 5 5" stroke-linecap="round"/></svg>
      <span class="flex-1">{{ setting('home.search_placeholder', 'Find your favorite items') }}</span>
    </button>
  </div>

  {{-- ═══ LIVE SEARCH PANEL (desktop + mobile) ═══ --}}
  <div class="anv-search-panel" :class="search ? 'open' : ''" x-cloak @keydown.escape.window="closeSearch" @click.self="closeSearch">
    <div class="anv-search-card">
      <div class="flex items-center justify-between px-5 pt-4">
        <p class="anv-sr-head">Search our organic store</p>
        <button @click="closeSearch" class="grid h-8 w-8 place-items-center rounded-full text-[#7C522A] transition hover:bg-[#f6efe3]" aria-label="Close search"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
      </div>
      <p class="anv-sr-hint px-5 pt-1">Start typing — results appear live</p>

      <div class="px-5 pt-4">
        <div class="relative">
          <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-[#C9A227]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9.75" cy="9.75" r="6.5"/><path d="m15.3 15.3 5 5" stroke-linecap="round"/></svg>
          <input type="text" x-ref="searchInput" x-model="q"
                 @input.debounce.300ms="runSearch()"
                 @keydown.enter.prevent="goSearch()"
                 placeholder="Search products, e.g. ghee, atta, oil" autocomplete="off"
                 class="h-[52px] w-full rounded-full border-2 border-[#eadfc9] bg-[#fbf7ef] py-3 pl-12 pr-24 text-[15px] text-[#2f2a24] outline-none transition focus:border-[#1F5C3F] focus:bg-white focus:ring-2 focus:ring-[#1F5C3F]/20 placeholder:text-[#a9a9a9]">
          <button @click.prevent="goSearch()" class="absolute right-1.5 top-1/2 -translate-y-1/2 rounded-full bg-[#1F5C3F] px-4 py-2 text-[13px] font-extrabold uppercase tracking-wide text-white transition hover:bg-[#173F2A]">Search</button>
        </div>
      </div>

      {{-- Loaded results / states --}}
      <div class="max-h-[48vh] overflow-y-auto px-3 pb-3 pt-3">
        <template x-if="loading">
          <div class="anv-sr-loading"><span class="anv-sr-spin"></span> Searching our farm stock…</div>
        </template>

        <template x-if="!loading && q.trim().length < 2">
          <div class="px-2 pb-1">
            <p class="anv-sr-hint mb-2">Popular: </p>
            <div class="flex flex-wrap gap-2">
              <template x-for="tag in popular" :key="tag">
                <button @click="q = tag; runSearch()" class="anv-chip"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="10" cy="10" r="6"/><path d="m16 16 5 5"/></svg><span x-text="tag"></span></button>
              </template>
            </div>
          </div>
        </template>

        <template x-if="!loading && q.trim().length >= 2 && results.length === 0">
          <div class="anv-sr-empty">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
            No matches for “<b x-text="q" class="mx-1"></b>”. Try a different keyword.
          </div>
        </template>

        <template x-if="!loading && q.trim().length >= 2 && results.length > 0">
          <div class="grid gap-1 sm:grid-cols-2">
            <template x-for="p in results" :key="p.slug">
              <a :href="p.url" @click="closeSearch" class="anv-sr-item">
                <img :src="p.image" :alt="p.name" loading="lazy" class="anv-sr-img">
                <span class="min-w-0 flex-1">
                  <span class="anv-sr-name block truncate" x-text="p.name"></span>
                  <span class="anv-sr-cat" x-show="p.category" x-text="p.category"></span>
                  <span class="mt-0.5 flex items-baseline gap-1.5">
                    <span class="anv-sr-sale" x-text="'₹' + p.sale.toLocaleString('en-IN')"></span>
                    <s class="anv-sr-price" x-show="p.price > p.sale" x-text="'₹' + p.price.toLocaleString('en-IN')"></s>
                    <span class="anv-sr-cat" x-show="p.unit">/ <span x-text="p.unit"></span></span>
                  </span>
                </span>
                <svg class="h-4 w-4 flex-shrink-0 text-[#C9A22A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
              </a>
            </template>
          </div>
        </template>
      </div>

      <div class="border-t border-[#eadfc9] px-5 py-3">
        <a href="#" @click.prevent="goSearch()" class="flex items-center justify-center gap-2 rounded-xl bg-[#f6efe3] py-2.5 text-[13px] font-bold text-[#7C522A] transition hover:bg-[#7C522A] hover:text-white">
          See all results <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
      </div>
    </div>
  </div>

  {{-- ═══ MOBILE DRAWER ═══ --}}
  <div class="anv-drawer" :class="drawer ? 'open' : ''" x-cloak>
    <div class="anv-drawer-backdrop" @click="drawer = false"></div>
    <div class="anv-drawer-panel" role="dialog" aria-modal="true" aria-label="Menu">
      <div class="flex items-center justify-between border-b border-sage-100 px-4 py-4">
        <a href="{{ route('shop.index') }}" class="flex items-center gap-2" @click="drawer=false">
          @if($homeIcon)
            <img src="{{ asset('storage/'.$homeIcon) }}" alt="{{ $storeName }}" class="h-10 w-auto max-w-[200px] object-contain sm:h-11">
          @else
            <span class="grid h-8 w-8 place-items-center rounded-lg bg-anv-600 text-white"><x-lucide-leaf class="h-4 w-4"/></span>
            <span class="font-display font-bold text-anv-700">{{ $storeName }}</span>
          @endif
        </a>
        <button @click="drawer=false" class="p-2 text-charcoal-600/60 hover:text-charcoal-900" aria-label="Close">
          <svg width="22" height="22" viewBox="0 0 18 17" fill="currentColor"><path d="M.865 15.978a.5.5 0 0 0 .707.707l7.433-7.431 7.579 7.282a.501.501 0 0 0 .846-.37.5.5 0 0 0-.153-.351L9.712 8.546l7.417-7.416a.5.5 0 1 0-.707-.708L8.991 7.853 1.413.573a.5.5 0 1 0-.693.72l7.563 7.268z"/></svg>
        </button>
      </div>

      <div class="anv-drawer-scroll">
        @forelse($navItems as $item)
          @php $children = collect($item['children'] ?? []); @endphp
          <a href="{{ $item['url'] ?? '#' }}" class="anv-drawer-item {{ !empty($item['highlight']) ? 'nav-rainbow' : '' }}" @click="drawer=false">
            @if(!empty($item['icon']))<img src="{{ asset('images/nav/'.$item['icon'].'.svg') }}" alt="" onerror="this.style.display='none'">@endif
            <span>{{ $item['label'] }}</span>
          </a>
          @if($children->count())
            <div class="anv-drawer-sub">
              @foreach($children as $child)
                <a href="{{ $child['url'] ?? '#' }}" @click="drawer=false">{{ $child['label'] }}</a>
              @endforeach
            </div>
          @endif
        @empty
        @endforelse
      </div>

      <div class="border-t border-sage-100 p-4">
        @auth
          <a href="{{ route('account.dashboard') }}" class="flex items-center gap-3 rounded-xl bg-leaf-50 px-4 py-3 text-sm font-semibold text-anv-700" @click="drawer=false"><x-lucide-user class="h-4 w-4"/> My Account</a>
        @else
          <div class="flex gap-2">
            <a href="{{ route('login') }}" class="flex-1 rounded-full border-2 border-anv-600 px-4 py-2.5 text-center text-sm font-bold text-anv-700" @click="drawer=false">Log in</a>
            <a href="{{ route('register') }}" class="flex-1 rounded-full bg-anv-600 px-4 py-2.5 text-center text-sm font-bold text-white" @click="drawer=false">Sign Up</a>
          </div>
        @endauth
      </div>
    </div>
  </div>
</header>
