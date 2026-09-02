@php
    $navItems = setting_json('display.nav_menu', []);
    $homeIcon = setting('display.logo', '');
    $storeName = setting('store.name', 'AB Organic');
    $tagline = setting('store.tagline', 'FARM');
@endphp

<style>
  .anv-nav-panel{background:#fff}
  .anv-nav-link{display:flex;align-items:center;gap:8px;padding:4px 10px;font-size:14px;font-weight:500;color:#2c2c2c;text-decoration:none;transition:color .15s ease;white-space:nowrap}
  .anv-nav-link:hover{color:#235a49}
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
  .anv-drawer-sub a:hover{color:#235a49}
  .anv-submenu{position:absolute;top:100%;left:0;min-width:230px;background:#fff;border:1px solid #eee;border-top:2px solid #235a49;border-radius:0 0 8px 8px;box-shadow:0 18px 40px -18px rgba(0,0,0,.25);padding:8px 0;opacity:0;visibility:hidden;transform:translateY(8px);transition:all .2s ease}
  .anv-nav-hasmenu:hover .anv-submenu,.anv-nav-hasmenu:focus-within .anv-submenu{opacity:1;visibility:visible;transform:none}
  .anv-submenu a{position:relative;display:flex;align-items:center;gap:10px;padding:9px 18px;font-size:13px;color:#444;text-decoration:none;transition:all .12s ease}
  .anv-submenu a:hover{color:#235a49;background:#f6f9f7;padding-left:22px}
  .anv-submenu a img{width:20px;height:20px;object-fit:contain}
  .anv-search-toggle{display:flex;align-items:center;justify-content:center;width:40px;height:40px;border-radius:9999px;color:#242424;transition:background .15s ease}
  .anv-search-toggle:hover{background:#eef5f0}
</style>

{{-- ════════════════════════════════════════════
     TOP HEADER BAR  (Sticky)
════════════════════════════════════════════ --}}
<header class="sticky top-0 z-40 bg-white shadow-sm" x-data="{ drawer: false, search: false }">
  <div class="mx-auto flex h-16 max-w-[1300px] items-center gap-2 px-4 sm:gap-4 sm:px-6 lg:px-8">

    {{-- Mobile hamburger --}}
    <button type="button" class="lg:hidden flex items-center justify-center p-2 -ml-2 text-[#242424]" @click="drawer = true" aria-label="Open menu">
      <svg width="26" height="24" viewBox="0 0 18 16" fill="currentColor"><path d="M1 .5a.5.5 0 1 0 0 1h15.71a.5.5 0 0 0 0-1zM.5 8a.5.5 0 0 1 .5-.5h15.71a.5.5 0 0 1 0 1H1A.5.5 0 0 1 .5 8m0 7a.5.5 0 0 1 .5-.5h15.71a.5.5 0 0 1 0 1H1a.5.5 0 0 1-.5-.5z"/></svg>
    </button>

    {{-- Logo --}}
    {{-- Desktop inline menu is centered-ish; keep logo left per reference --}}
    <a href="{{ route('shop.index') }}" class="flex shrink-0 items-center gap-2">
      @if($homeIcon)
        <img src="{{ asset('storage/'.$homeIcon) }}" alt="{{ $storeName }} logo" class="h-10 w-auto max-w-[160px] object-contain">
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
      <button type="button" class="anv-search-toggle" @click="search = true" aria-label="Search">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><circle cx="9.75" cy="9.75" r="8.25" stroke="#00584B" stroke-width="2"/><path d="M15.6 15.6 22.5 22.5" stroke="#00584B" stroke-width="2" stroke-linecap="round"/></svg>
      </button>

      <a href="{{ route('account.wishlist') }}" class="hidden items-center justify-center rounded-full p-2.5 text-[#00584B] transition-colors hover:bg-[#eef5f0] sm:flex" aria-label="Wishlist">
        <x-lucide-heart class="h-5 w-5"/>
      </a>

      <button type="button" data-cart-anchor class="relative flex items-center justify-center rounded-full p-2.5 text-[#00584B] transition-colors hover:bg-[#eef5f0]" aria-label="Cart"
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

  {{-- Mobile search row --}}
  <div class="px-4 pb-3 lg:hidden">
    <form action="{{ route('shop.search') }}" method="GET">
      <div class="relative w-full group">
        <x-lucide-search class="absolute left-4 top-1/2 h-[18px] w-[18px] -translate-y-1/2 text-charcoal-600/40 group-focus-within:text-anv-600 transition-colors"/>
        <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ setting('home.search_placeholder', 'Find your favorite items') }}" class="h-11 w-full rounded-full border-2 border-sage-100 bg-leaf-50 pl-11 pr-5 text-sm placeholder:text-charcoal-600/40 transition-all focus:border-anv-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-anv-500/20">
      </div>
    </form>
  </div>

  {{-- ═══ SEARCH MODAL (desktop) ═══ --}}
  <div x-show="search" x-cloak x-transition @keydown.escape.window="search=false" class="fixed inset-0 z-[95] bg-white" @click.self="search=false">
    <div class="mx-auto flex h-full max-w-xl flex-col px-4 pt-10 sm:px-6">
      <div class="flex items-center justify-between">
        <h2 class="font-display text-xl font-bold text-[#235a49]">Search</h2>
        <button @click="search=false" class="p-2 text-charcoal-600/60 hover:text-charcoal-900"><x-lucide-x class="h-6 w-6"/></button>
      </div>
      <form action="{{ route('shop.search') }}" method="GET" class="mt-5">
        <div class="relative w-full">
          <x-lucide-search class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-charcoal-600/40"/>
          <input type="search" name="q" autofocus placeholder="{{ setting('home.search_placeholder', 'Search products, e.g. ghee') }}" class="h-13 w-full rounded-full border-2 border-[#235a49] py-3.5 pl-12 pr-5 text-base outline-none focus:ring-2 focus:ring-anv-500/30">
        </div>
      </form>
      <div class="mt-6 text-sm text-charcoal-600/60">Popular:</div>
      <div class="mt-3 flex flex-wrap gap-2">
        @foreach(setting_json('home.tags', ['Ghee','Oils','Atta','Superfoods']) as $tag)
          <a href="{{ route('shop.search', ['q' => $tag]) }}" class="rounded-full border border-sage-200 px-4 py-1.5 text-sm text-charcoal-700 transition hover:border-anv-600 hover:text-anv-700">{{ $tag }}</a>
        @endforeach
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
            <img src="{{ asset('storage/'.$homeIcon) }}" alt="{{ $storeName }}" class="h-9 w-auto max-w-[150px] object-contain">
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
