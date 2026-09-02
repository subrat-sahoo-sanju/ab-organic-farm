{{-- ═══════════════════════════════════════════════════════════════
     CART DRAWER — slide-in mini-cart from right (Anveshan style)
═══════════════════════════════════════════════════════════════ --}}
<div x-data="cartDrawer()" x-cloak class="fixed inset-0 z-[85]" x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
  {{-- Overlay --}}
  <div class="absolute inset-0 bg-black/50" @click="close()" x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

  {{-- Drawer panel --}}
  <div class="absolute top-0 right-0 bottom-0 flex w-full max-w-[420px] flex-col bg-white shadow-2xl transition-transform duration-300 ease-[cubic-bezier(.4,0,.2,1)]"
       :class="open ? 'translate-x-0' : 'translate-x-full'"
       role="dialog" aria-modal="true" aria-label="Your cart" @click.outside="close()">

    {{-- Header --}}
    <div class="flex items-center justify-between border-b border-sage-100 px-5 py-4">
      <div class="flex items-center gap-2">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#235A49" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
        <h2 class="font-display text-base font-bold text-anv-800">My Cart</h2>
        <span x-show="count > 0" x-cloak class="rounded-full bg-anv-100 px-2 py-0.5 text-[11px] font-bold text-anv-700" x-text="count + ' item' + (count !== 1 ? 's' : '')"></span>
      </div>
      <button @click="close()" class="grid h-8 w-8 place-items-center rounded-full text-charcoal-600/60 transition-colors hover:bg-sage-50 hover:text-charcoal-900" aria-label="Close cart">
        <svg width="18" height="18" viewBox="0 0 18 17" fill="currentColor"><path d="M.865 15.978a.5.5 0 0 0 .707.707l7.433-7.431 7.579 7.282a.501.501 0 0 0 .846-.37.5.5 0 0 0-.153-.351L9.712 8.546l7.417-7.416a.5.5 0 1 0-.707-.708L8.991 7.853 1.413.573a.5.5 0 1 0-.693.72l7.563 7.268z"/></svg>
      </button>
    </div>

    {{-- Offer note --}}
    <div class="border-b border-sage-100 bg-leaf-50 px-5 py-2.5">
      <p class="text-center text-xs font-semibold text-anv-700">Unlock your best offer at checkout!</p>
    </div>

    {{-- Items list --}}
    <div class="flex-1 overflow-y-auto overscroll-contain px-5 pt-4 pb-6">
      {{-- Loading skeleton --}}
      <template x-if="loading && lines.length === 0">
        <div class="space-y-4">
          <template x-for="i in 3" :key="i">
            <div class="flex gap-3 rounded-xl border border-sage-100 p-3">
              <div class="h-16 w-16 animate-pulse rounded-lg bg-sage-100"></div>
              <div class="flex-1 space-y-2">
                <div class="h-3 w-3/4 animate-pulse rounded bg-sage-100"></div>
                <div class="h-3 w-1/2 animate-pulse rounded bg-sage-100"></div>
                <div class="h-3 w-1/4 animate-pulse rounded bg-sage-100"></div>
              </div>
            </div>
          </template>
        </div>
      </template>

      {{-- Empty state --}}
      <template x-if="!loading && lines.length === 0">
        <div class="flex flex-col items-center justify-center py-12 text-center">
          <div class="mb-4 grid h-20 w-20 place-items-center rounded-full bg-leaf-100">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#235A49" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
          </div>
          <p class="text-base font-semibold text-anv-800">Nothing in your cart yet.</p>
          <p class="mt-1 text-sm text-charcoal-600/50">Let's fix that with something pure and delicious.</p>
          <a href="{{ route('shop.categories') }}" class="mt-5 rounded-full bg-gradient-to-r from-anv-600 to-anv-700 px-6 py-2.5 text-sm font-bold text-white shadow-sm transition hover:from-anv-700 hover:to-anv-800 hover:shadow-md" @click="close()">Continue Shopping</a>
        </div>
      </template>

      {{-- Cart items --}}
      <template x-if="lines.length > 0">
        <div class="space-y-3">
          <template x-for="line in lines" :key="line.id">
            <div class="flex gap-3 rounded-xl border border-sage-100 p-3 transition-colors hover:border-sage-200 hover:bg-leaf-50/30">
              {{-- Product image --}}
              <a :href="'/products/' + line.slug" class="shrink-0" @click="close()">
                <img :src="line.image" :alt="line.name" class="h-16 w-16 rounded-lg object-cover bg-leaf-50 ring-1 ring-sage-100">
              </a>
              {{-- Details --}}
              <div class="flex flex-1 flex-col justify-between min-w-0">
                <div>
                  <a :href="'/products/' + line.slug" class="line-clamp-2 text-sm font-semibold text-charcoal-800 hover:text-anv-700" @click="close()" x-text="line.name"></a>
                  <p x-show="line.variant_name" x-cloak class="mt-0.5 text-[11px] text-charcoal-600/50" x-text="line.variant_name"></p>
                </div>
                <div class="mt-1.5 flex items-center justify-between">
                  {{-- Qty stepper --}}
                  <div class="flex items-center gap-0 rounded-full border border-anv-200 bg-leaf-50">
                    <button @click="setQty(line, -1)" class="grid h-7 w-7 place-items-center rounded-full text-charcoal-700 transition-colors hover:bg-anv-100" aria-label="Decrease quantity">
                      <svg width="12" height="12" viewBox="0 0 12 12" fill="currentColor"><rect x="2" y="5" width="8" height="1.5" rx="0.75"/></svg>
                    </button>
                    <span class="min-w-[24px] text-center text-xs font-bold text-charcoal-800" x-text="line.quantity"></span>
                    <button @click="setQty(line, 1)" class="grid h-7 w-7 place-items-center rounded-full text-charcoal-700 transition-colors hover:bg-anv-100" aria-label="Increase quantity">
                      <svg width="12" height="12" viewBox="0 0 12 12" fill="currentColor"><rect x="2" y="5" width="8" height="1.5" rx="0.75"/><rect x="5.25" y="2" width="1.5" height="8" rx="0.75"/></svg>
                    </button>
                  </div>
                  {{-- Price --}}
                  <div class="text-right">
                    <span class="text-sm font-extrabold text-anv-700" x-text="'₹' + line.line_total"></span>
                    <template x-if="line.original_price > line.price && line.quantity === 1">
                      <s class="ml-1 text-[11px] font-medium text-charcoal-600/40" x-text="'₹' + line.original_price"></s>
                    </template>
                  </div>
                </div>
              </div>
              {{-- Remove button --}}
              <button @click="remove(line)" class="self-start mt-0.5 grid h-6 w-6 shrink-0 place-items-center rounded-full text-charcoal-600/30 transition-colors hover:bg-clay-50 hover:text-clay-600" aria-label="Remove item">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><line x1="3" y1="3" x2="11" y2="11"/><line x1="11" y1="3" x2="3" y2="11"/></svg>
              </button>
            </div>
          </template>
        </div>
      </template>
    </div>

    {{-- Sticky footer --}}
    <template x-if="lines.length > 0">
      <div class="border-t border-sage-100 bg-white px-5 pb-[env(safe-area-inset-bottom)] pt-4 shadow-[0_-4px_12px_rgba(0,0,0,0.04)]">
        {{-- Subtotal --}}
        <div class="mb-3 flex items-center justify-between">
          <span class="text-sm text-charcoal-600/60">Subtotal</span>
          <span class="text-lg font-extrabold text-anv-800" x-text="'₹' + total"></span>
        </div>
        {{-- Free delivery hint --}}
        <div x-show="total < 499" x-cloak class="mb-3">
          <div class="flex items-center justify-between text-[11px] text-charcoal-600/50">
            <span>Add <b x-text="'₹' + (499 - total).toFixed(0)"></b> more for free delivery</span>
            <span class="font-semibold text-anv-600">Free</span>
          </div>
          <div class="mt-1 h-1.5 overflow-hidden rounded-full bg-sage-100">
            <div class="h-full rounded-full bg-gradient-to-r from-anv-500 to-anv-600 transition-all duration-500" :style="'width:' + Math.min(100, (total / 499) * 100) + '%'"></div>
          </div>
        </div>
        <div x-show="total >= 499" x-cloak class="mb-3 flex items-center gap-1.5 text-[11px] font-semibold text-anv-600">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
          You've unlocked free delivery!
        </div>
        {{-- Checkout CTA --}}
        <a href="{{ route('checkout') }}" class="block w-full rounded-full bg-gradient-to-r from-anv-600 to-anv-700 py-3.5 text-center text-sm font-bold text-white shadow-lg transition hover:from-anv-700 hover:to-anv-800 hover:shadow-xl active:scale-[0.98]" @click="close()">
          Proceed to Checkout
        </a>
        <a href="{{ route('cart.index') }}" class="mt-2 block text-center text-xs font-semibold text-anv-600 underline underline-offset-2 hover:text-anv-700" @click="close()">View full cart</a>
      </div>
    </template>
  </div>
</div>
