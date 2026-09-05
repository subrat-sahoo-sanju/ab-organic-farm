<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php $sName = setting('store.name'); @endphp
    <title>{{ !empty($title) ? (str_contains($title, $sName) ? $title : $title.' — '.$sName) : $sName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700;800&family=Roboto+Slab:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('storage/sections/app-icon.jpg') }}" type="image/jpeg">
    @php $css = @file_get_contents(public_path('static/assets/app-CG2n5-Qg.css')); @endphp
    <style>{!! $css !!}</style>
    <style>[x-cloak]{display:none!important}[data-wishlist].wish-on{background:#fff;box-shadow:0 6px 18px -6px rgba(224,36,65,.45)}</style>
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
        <div class="mx-auto flex max-w-[1440px] items-center justify-center px-4 py-2 text-center text-xs font-semibold tracking-wide sm:text-sm" x-data="{ i: 0, msgs: {{ json_encode(array_values($msgs)) }} }" x-init="setInterval(() => i = (i+1) % msgs.length, 6000)">
            <template x-for="(m, idx) in msgs" :key="idx">
                <span x-show="i === idx" x-text="m" x-transition.opacity.duration.500 class="truncate" style="max-width: 90vw"></span>
            </template>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════
         HEADER — sticky, reference-style (drawer + inline nav + dropdowns)
    ═══════════════════════════════════════════════════════════════ --}}
    @include('layouts.partials.header-nav')

    {{-- ═══ APP DOWNLOAD BAR · full-width #7C522A (reference: anveshan.farm) ═══ --}}
    @php
        $dlHeading = setting('display.app_download_heading');
        $dlSub     = setting('display.app_download_sub', 'Unlock 17% OFF Exclusively on App');
        $dlUrl     = setting('display.app_download_url2', '#');
        $dlEnabled = setting('display.app_download_enabled', '1');
    @endphp
    @if($dlEnabled === '1' || $dlEnabled === true)
    <div class="app-download-bar" id="appDownloadBar" x-data="{ closed: false }" x-show="!closed" x-transition>
        <div class="app-download-bar__inner">
            <div class="app-download-bar__left">
                <div class="app-download-bar__icon">
                    <img src="{{ asset('storage/'.(setting('display.app_icon', '') ?: 'sections/app-icon.jpg')) }}" alt="App icon" width="44" height="44" loading="eager">
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
         FOOTER — Attractive, admin-driven, "real-time professional"
         Warm-organic background image · services · policies · help ·
         newsletter · contact info · social media (all from settings)
    ═══════════════════════════════════════════════════════════════ --}}
    @php
        $ftBg = setting('footer.background_image', '');
        $ftBgUrl = $ftBg ? asset('storage/'.ltrim($ftBg, '/')) : asset('storage/bgs/footer-bg-wide.jpg');
    @endphp
    <footer class="relative mt-16 overflow-hidden text-white/85"
            style="background-color:#173F2A;{{ $ftBgUrl ? ' background-image:url('.$ftBgUrl.'); background-size:cover; background-position:center;' : '' }}">
        {{-- Warm emerald overlay for readability over any backdrop --}}
        <div class="absolute inset-0 bg-gradient-to-t from-[#0E2B1D]/95 via-[#153F2A]/88 to-[#173F2A]/82"></div>
        <div class="absolute inset-0 opacity-40 [background-image:radial-gradient(ellipse_at_bottom,rgba(211,156,47,.12),transparent_65%)] pointer-events-none"></div>

        <div class="relative max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-14 grid gap-10 sm:grid-cols-2 lg:grid-cols-4 text-sm">

            {{-- Column 1: brand + about + contact + socials --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    @if($siteLogoWhite = setting('display.logo_white', ''))
                        <img src="{{ asset('storage/'.$siteLogoWhite) }}" alt="{{ config('app.name', 'Store') }} logo" class="h-10 w-auto max-w-[200px] object-contain sm:h-11">
                    @else
                        <span class="h-9 w-9 rounded-lg bg-gold-400 text-anv-900 grid place-items-center"><x-lucide-leaf class="h-5 w-5"/></span>
                        <span class="font-display text-lg font-bold text-white">{{ setting('footer.company_name', setting('store.name', 'AB Organic Farm')) }}</span>
                    @endif
                </div>
                <p class="leading-relaxed text-white/60">{{ setting('footer.about', setting('footer.tagline', setting('store.tagline', 'Farm-fresh certified organic products, delivered to your doorstep.'))) }}</p>

                {{-- Contact info (admin-managed, real-time) --}}
                @php $ftPhone = setting('footer.phone',''); $ftEmail = setting('footer.email',''); $ftHours = setting('footer.hours',''); @endphp
                @if($ftPhone || $ftEmail || $ftHours || setting('footer.address'))
                <ul class="mt-5 space-y-2.5 text-[13px] text-white/70">
                    @if($ftPhone)<li class="flex items-center gap-2.5"><x-lucide-phone class="h-4 w-4 text-gold-300 shrink-0"/><a href="tel:{{ preg_replace('/\D+/','',$ftPhone) }}" class="hover:text-gold-300 transition-colors">{{ $ftPhone }}</a></li>@endif
                    @if($ftEmail)<li class="flex items-center gap-2.5"><x-lucide-mail class="h-4 w-4 text-gold-300 shrink-0"/><a href="mailto:{{ $ftEmail }}" class="hover:text-gold-300 transition-colors break-all">{{ $ftEmail }}</a></li>@endif
                    @if($ftHours)<li class="flex items-center gap-2.5"><x-lucide-clock class="h-4 w-4 text-gold-300 shrink-0"/><span>{{ $ftHours }}</span></li>@endif
                    @if(setting('footer.address'))<li class="flex items-center gap-2.5"><x-lucide-map-pin class="h-4 w-4 text-gold-300 shrink-0"/><span>{{ setting('footer.address') }}</span></li>@endif
                </ul>
                @endif

                {{-- Social media (admin-driven) --}}
                <div class="mt-6 flex gap-2.5">
                    @php
                        $waNum = preg_replace('/\D+/', '', setting('social.whatsapp',''));
                        // Admin-configured socials if provided; otherwise always show the
                        // four core networks using their social.* URLs (with a sensible
                        // placeholder if not yet configured) so icons are never hidden.
                        $socials = setting_json('footer.socials', []);
                        if (empty($socials)) {
                            $socials = [
                                ['icon' => 'facebook',  'url' => setting('social.facebook')  ?: 'https://www.facebook.com/'],
                                ['icon' => 'instagram', 'url' => setting('social.instagram') ?: 'https://www.instagram.com/'],
                                ['icon' => 'twitter',   'url' => setting('social.twitter')   ?: 'https://x.com/'],
                                ['icon' => 'whatsapp',  'url' => $waNum ? 'https://wa.me/'.$waNum : 'https://wa.me/'],
                            ];
                        }
                    @endphp
                    @foreach($socials as $s)
                        @php $ic = $s['icon'] ?? ''; $u = $s['url'] ?? '#'; @endphp
                        @if(strtolower($ic) === 'whatsapp')
                            <a href="{{ $waNum ? 'https://wa.me/'.$waNum : $u }}" target="_blank" rel="noopener" class="grid h-9 w-9 place-items-center rounded-full bg-white/10 text-white/80 ring-1 ring-white/15 transition hover:bg-[#25D366] hover:text-white hover:ring-[#25D366]">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                            </a>
                        @elseif(in_array(strtolower($ic), ['facebook','instagram','twitter','youtube','threads','linkedin']))
                            <a href="{{ $u }}" target="_blank" rel="noopener" class="grid h-9 w-9 place-items-center rounded-full bg-white/10 text-white/80 ring-1 ring-white/15 transition hover:bg-gold-400 hover:text-anv-900 hover:ring-gold-400">
                                @if($ic === 'threads')
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12.186 23.748c-.766-.01-5.5-.09-6.83-1.318-1.07-.99-1.534-2.32-1.855-4.42-.34-2.25-.4-5.03-.4-7.18h.001c0-2.15.06-4.93.4-7.18.32-2.1.786-3.43 1.855-4.42C6.686.34 11.42.26 12.186.25h.48c.765.01 5.72.09 7.05 1.31 1.07.99 1.53 2.32 1.85 4.42.34 2.25.4 5.03.4 7.18v.23c0 2.15-.06 4.93-.4 7.18-.32 2.1-.78 3.43-1.85 4.42-1.33 1.23-6.06 1.31-6.83 1.32h-.48Zm.24-2.25c.72 0 5.5-.09 6.6-1.24.8-.75 1.2-1.85 1.5-3.8.32-2.02.37-4.7.37-6.88v-.14c0-2.18-.05-4.86-.37-6.88-.3-1.95-.7-3.05-1.5-3.8-1.12-1.15-5.9-1.24-6.62-1.25h-.44c-.72 0-5.5.09-6.6 1.25-.8.75-1.2 1.85-1.5 3.8-.32 2.02-.37 4.7-.37 6.88v.14c0 2.18.05 4.86.37 6.88.3 1.94.7 3.05 1.5 3.8 1.12 1.15 5.9 1.24 6.61 1.24h.45Zm-3.74-8.1c-.4.02-1.5.1-2.1-.53-.3-.32-.5-.67-.53-1.15a1.6 1.6 0 0 1 .2-.83c.34-.6 1.1-.9 2.04-.9 1.13.01 1.8.5 2.2.9l1.8-2.1c-.8-.8-2.1-1.34-4-1.34-1.9 0-3.53.7-4.4 1.93-.8 1.12-.9 2.67-.56 4.09.3 1.15 1.17 2 2.45 2.33 1.02.28 2.02.16 2.86-.12a2.6 2.6 0 0 1-.2-1.15c0-1 .48-1.86 1.3-2.3-.6-.36-1.16-.55-1.86-.5v.23c.33-.18.6-.22.96-.23.36-.01.43.15.2.36l-.02.13c-.46.55-.56 1.1-.56 2.06 0 1.24.6 2.4 1.93 2.4.3 0 .57-.05.86-.2.5-.28.75-.86.89-1.4a7.1 7.1 0 0 0 .22-1.67c0-1.4-.38-2.6-1.13-3.55-.93-1.2-2.46-1.85-4.54-1.85-1.6 0-3.13.5-4.17 1.6-1.41 1.42-1.68 3.3-1.28 4.9.5 2.02 2.2 3.2 4.27 3.2.6 0 1.2-.07 1.75-.2a7.5 7.5 0 0 0 3.44-1.8l-1.23-1.8a5 5 0 0 1-3.36 1.5s.002-.13 0 0Z"/></svg>
                                @else
                                    @svg('lucide-'.strtolower($ic), 'h-4 w-4')
                                @endif
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Column 2: Services --}}
            @php
                $footerServices = setting_json('footer.links_services', []);
                if (empty($footerServices)) {
                    $footerServices = [
                        ['label' => 'Shop Organic', 'url' => route('shop.categories')],
                        ['label' => 'Track Your Order', 'url' => route('account.orders')],
                        ['label' => 'Our Story', 'url' => route('shop.index') . '#story'],
                        ['label' => 'Bulk & Wholesale', 'url' => route('shop.index') . '#story'],
                        ['label' => 'Store Locator', 'url' => route('shop.index')],
                        ['label' => 'FAQs', 'url' => route('shop.index')],
                        ['label' => 'Farming Partners', 'url' => route('shop.index') . '#story'],
                        ['label' => 'Contact Us', 'url' => '#contact'],
                    ];
                }
            @endphp
            <div>
                <h4 class="font-semibold text-white mb-4 flex items-center gap-2"><span class="h-5 w-1 rounded-full bg-gold-400"></span>Services</h4>
                <ul class="space-y-2.5">
                    @foreach($footerServices as $link)
                        <li><a href="{{ $link['url'] }}" class="inline-flex items-center gap-2 hover:text-gold-300 transition-colors"><x-lucide-chevron-right class="h-3.5 w-3.5 text-gold-300/60"/> {{ $link['label'] }}</a></li>
                    @endforeach
                    @auth @if(auth()->user()->isStaff())<li><a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 hover:text-gold-300 font-medium"><x-lucide-chevron-right class="h-3.5 w-3.5 text-gold-300/60"/> Admin Panel</a></li>@endif @endauth
                </ul>
            </div>

            {{-- Column 3: Policies + Help --}}
            @php
                $footerPolicies = setting_json('footer.links_policies', []);
                if (empty($footerPolicies)) {
                    $footerPolicies = [
                        ['label' => 'Privacy Policy', 'url' => route('pages.privacy')],
                        ['label' => 'Shipping Policy', 'url' => route('pages.shipping')],
                        ['label' => 'Refund Policy', 'url' => route('pages.refund')],
                        ['label' => 'Terms of Service', 'url' => route('pages.terms')],
                        ['label' => 'Cancellation Policy', 'url' => route('pages.cancellation')],
                        ['label' => 'Returns & Exchanges', 'url' => route('pages.returns')],
                    ];
                }
            @endphp
            <div>
                <h4 class="font-semibold text-white mb-4 flex items-center gap-2"><span class="h-5 w-1 rounded-full bg-gold-400"></span>Policies</h4>
                <ul class="space-y-2.5">
                    @foreach($footerPolicies as $link)
                        <li><a href="{{ $link['url'] }}" class="inline-flex items-center gap-2 hover:text-gold-300 transition-colors"><x-lucide-chevron-right class="h-3.5 w-3.5 text-gold-300/60"/> {{ $link['label'] }}</a></li>
                    @endforeach
                </ul>
                <h4 class="font-semibold text-white mb-3 mt-6 flex items-center gap-2"><span class="h-5 w-1 rounded-full bg-gold-400"></span>Need Help?</h4>
                <a href="{{ setting('store.contact_link', '#') }}" class="inline-flex items-center gap-2 rounded-full border border-white/25 px-5 py-2.5 text-xs font-bold text-white transition hover:bg-gold-400 hover:text-anv-900 hover:border-gold-400">
                    <x-lucide-headphones class="h-3.5 w-3.5" /> Contact Us
                </a>
            </div>

            {{-- Column 4: Newsletter --}}
            <div>
                <h4 class="font-semibold text-white mb-4 flex items-center gap-2"><span class="h-5 w-1 rounded-full bg-gold-400"></span>{{ setting('footer.newsletter_heading', 'Stay in the loop') }}</h4>
                <p class="text-white/60 text-xs mb-3">{{ setting('footer.newsletter_sub', 'Fresh offers & farm stories. No spam.') }}</p>
                <form x-data="{ email: '', done: false }" @submit.prevent="fetch('/newsletter', { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Content-Type':'application/json','Accept':'application/json'}, body: JSON.stringify({email}) }).then(r=>r.json()).then(()=>done=true); email=''">
                    <div class="flex rounded-full border border-white/25 bg-white/5 overflow-hidden focus-within:border-gold-300">
                        <input x-model="email" type="email" required placeholder="Your email" class="flex-1 bg-transparent px-4 py-2.5 text-sm text-white placeholder:text-white/40 focus:outline-none">
                        <button type="submit" class="px-4 text-anv-200 hover:text-gold-300 transition-colors" aria-label="Subscribe"><x-lucide-arrow-right class="h-4 w-4"/></button>
                    </div>
                    <p x-show="done" x-cloak class="mt-2 text-xs text-gold-300">You're subscribed. Welcome!</p>
                </form>

                {{-- Trust / freshness note (admin-managed) --}}
                <div class="mt-6 flex flex-wrap gap-2 text-[11px] font-semibold">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1.5 text-white/80"><x-lucide-leaf class="h-3.5 w-3.5 text-gold-300"/> 100% Organic</span>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1.5 text-white/80"><x-lucide-truck class="h-3.5 w-3.5 text-gold-300"/> Free Delivery</span>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1.5 text-white/80"><x-lucide-shield-check class="h-3.5 w-3.5 text-gold-300"/> FSSAI Certified</span>
                </div>
            </div>
        </div>

        {{-- Bottom bar: copyright + trust --}}
        <div class="relative border-t border-white/10 bg-black/20">
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col items-center justify-between gap-2 text-center text-xs text-white/40 sm:flex-row sm:text-left">
                <span>&copy; {{ now()->year }} {{ setting('footer.copyright', 'AB Organic Farm Pvt. Ltd.') }}. All rights reserved.</span>
                <span>{{ setting('footer.company_name', setting('store.name', 'AB Organic Farm')) }} · {{ setting('footer.address', '') }}</span>
            </div>
        </div>
    </footer>

    {{-- ═══════════════════════════════════════════════════════════════
         MOBILE BOTTOM NAV — Home · Deal · Combos · Account
    ═══════════════════════════════════════════════════════════════ --}}
    @php
      $navItems = setting_json('display.bottom_nav', []);
      if (empty($navItems)) {
          $navItems = [
              ['label' => 'Home', 'icon' => 'home', 'url' => '/'],
              ['label' => 'Categories', 'icon' => 'layout-grid', 'url' => route('shop.categories')],
              ['label' => 'Wishlist', 'icon' => 'heart', 'url' => auth()->check() ? route('account.wishlist') : route('login')],
              ['label' => 'Cart', 'icon' => 'shopping-cart', 'url' => '#'],
              ['label' => 'Account', 'icon' => 'user', 'url' => auth()->check() ? route('account.dashboard') : route('login')],
          ];
      }
    @endphp
    <nav class="md:hidden fixed bottom-0 inset-x-0 z-50 bg-white border-t border-sage-100 shadow-[0_-2px_12px_rgba(0,0,0,0.07)] pb-[env(safe-area-inset-bottom)]" aria-label="Mobile navigation" x-data>
        <div class="grid grid-cols-5 h-[68px] text-[10px] font-bold tracking-wide">
            @foreach($navItems as $ni)
                @php $active = request()->url() === $ni['url'] || (($ni['label'] === 'Account') && request()->routeIs('account.*')) || (($ni['label'] === 'Categories') && request()->routeIs('shop.categories')); @endphp
                @if($ni['label'] === 'Cart')
                  <button type="button" class="relative flex flex-col items-center justify-center gap-1 text-charcoal-600" @click="window.dispatchEvent(new CustomEvent('anv:cart-drawer-open'))">
                    <span class="relative">
                      <x-lucide-shopping-cart class="h-[22px] w-[22px]"/>
                      <span x-text="$store.cart.count" x-show="$store.cart.count > 0" x-cloak class="cart-badge absolute -top-2 -right-2.5 h-4 min-w-4 px-1 rounded-full bg-anv-600 text-white text-[10px] font-bold grid place-items-center leading-none ring-2 ring-white"></span>
                    </span>
                    <span>{{ $ni['label'] }}</span>
                  </button>
                @else
                  <a href="{{ $ni['url'] }}" class="relative flex flex-col items-center justify-center gap-1 transition-colors {{ $active ? 'text-anv-600' : 'text-charcoal-600' }}">
                    <span class="grid h-7 w-11 items-center justify-center rounded-full transition-colors {{ $active ? 'bg-anv-100 text-anv-700' : '' }}">
                      @svg('lucide-'.$ni['icon'], 'h-[22px] w-[22px]')
                    </span>
                    <span class="{{ $active ? 'text-anv-700' : '' }}">{{ $ni['label'] }}</span>
                  </a>
                @endif
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
                <div><p class="text-sm font-bold leading-tight">{{ setting('display.whatsapp_name', setting('store.name', 'AB Organic Farm')) }}</p><p class="text-[10px] text-green-100">Online now</p></div>
            </div>
            <div class="p-3">
                <div class="rounded-[15px] bg-white p-3 text-sm text-charcoal-800 shadow">
                    {{ setting('display.whatsapp_greeting', 'Hi there 👋 How can we help you today?') }}
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
                        <input x-model="email" x-ref="email" type="email" placeholder="Enter your email" autocomplete="email"
                               class="mt-4 w-full rounded-full border-2 border-sage-100 px-5 py-3 text-sm focus:border-anv-500 focus:outline-none">
                        <p x-show="error" x-text="error" class="mt-2 text-xs text-clay-600" x-cloak></p>
                        <button @click="submit()" :disabled="busy" class="mt-3 w-full rounded-full bg-anv-600 py-3 text-sm font-bold text-white transition hover:bg-anv-700"
                                x-text="busy ? 'Submitting…' : 'Notify Me'"></button>
                    </div>
                </template>
                <template x-if="submitted">
                    <div class="text-center">
                        <div class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-leaf-100 text-anv-600">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="h-6 w-6"><path d="M20 6 9 17l-5-5"/></svg>
                        </div>
                        <h3 class="mt-4 font-display text-lg font-bold text-anv-800">You're on the list!</h3>
                        <p class="mt-1 text-sm text-charcoal-600/60">We'll ping <b x-text="email"></b> the moment it's available again.</p>
                        <button @click="close()" class="mt-5 w-full rounded-full bg-anv-600 py-3 text-sm font-bold text-white transition hover:bg-anv-700">Done</button>
                    </div>
                </template>
            </div>
        </div>
    </div>

    {{-- ═══ CART DRAWER (slide-in mini-cart from right) ═══ --}}
    @include('layouts.partials.cart-drawer')

    <x-ui.toaster/>

    {{-- ═══ BRAND LOADER (professional full-page loader) ═══ --}}
    @php
        $__logoFile = public_path('images/logo/ab-organic-label.svg');
        $__logoData = is_file($__logoFile) ? 'data:image/svg+xml;base64,'.base64_encode(file_get_contents($__logoFile)) : '';
    @endphp
    <div id="brand-loader" class="brand-loader" aria-hidden="true">
        <div class="brand-loader__inner">
            @if($__logoData)
                <img src="{{ $__logoData }}" alt="" class="brand-loader__logo">
            @else
                <span class="brand-loader__logo brand-loader__logo--text">AB Organic</span>
            @endif
            <span class="brand-loader__ring" aria-hidden="true"></span>
        </div>
    </div>

    @stack('scripts')
    @php $js = @file_get_contents(public_path('static/assets/app-Ble7JpfC.js')); @endphp
    <script>{!! $js !!}</script>
</body>
</html>