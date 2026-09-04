@extends('layouts.app', ['title' => 'Your Basket'])

@section('content')
<div class="min-h-screen bg-gray-50 pb-52 lg:pb-8" x-data="cartPage()" x-cloak>

  {{-- Flash Messages --}}
  <template x-if="flash">
    <div x-transition
      class="fixed top-4 left-1/2 z-50 flex -translate-x-1/2 items-center gap-2 rounded-xl border bg-white px-5 py-3 text-sm font-medium shadow-xl"
      :class="flash.type === 'error' ? 'border-red-200 text-red-600' : 'border-emerald-200 text-emerald-700'">
      <span x-text="flash.msg"></span>
    </div>
  </template>

  <div class="mx-auto max-w-6xl px-4 py-5 sm:px-6 lg:px-8">

    {{-- ========== EMPTY STATE ========== --}}
    <template x-if="lines.length === 0 && !loading">
      <div class="mx-auto mt-16 max-w-sm text-center">
        <div class="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-[#7C522A]/10">
          <svg class="h-12 w-12 text-[#7C522A]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
          </svg>
        </div>
        <h2 class="text-xl font-extrabold text-gray-900">Your basket is empty</h2>
        <p class="mt-2 text-sm text-gray-400">Looks like you haven't added any organic goodness yet.</p>
        <a href="{{ route('shop.index') }}"
          class="mt-6 inline-flex items-center gap-2 rounded-xl bg-[#7C522A] px-7 py-3.5 text-sm font-bold text-white shadow-lg shadow-[#7C522A]/25 hover:bg-[#613E20] transition">
          Start Shopping
        </a>
      </div>
    </template>

    {{-- ========== CART CONTENT ========== --}}
    <template x-if="lines.length > 0">
      <div>
        {{-- Header --}}
        <div class="mb-5 flex items-center justify-between">
          <div>
            <h1 class="text-xl font-extrabold text-gray-900">My Basket</h1>
            <p class="text-xs text-gray-400">
              <span x-text="itemCount"></span> item<span x-show="itemCount > 1">s</span> from
              <span x-text="productCount"></span> product<span x-show="productCount > 1">s</span>
            </p>
          </div>
          <a href="{{ route('shop.index') }}" class="flex items-center gap-1 rounded-full border border-gray-200 bg-white px-3.5 py-2 text-xs font-semibold text-gray-600 transition hover:border-[#7C522A] hover:text-[#7C522A]">
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add More
          </a>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1fr_380px]">

          {{-- ===== LEFT: CART ITEMS ===== --}}
          <div class="space-y-3">
            <template x-for="line in lines" :key="line.id">
              <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm transition-all hover:shadow-md"
                x-transition:enter="transition duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100">

                <div class="flex gap-4">
                  {{-- Product Image --}}
                  <div class="h-20 w-20 shrink-0 overflow-hidden rounded-xl border border-gray-100 bg-gray-50 p-1.5">
                    <div class="flex h-full items-center justify-center text-2xl">🌿</div>
                  </div>

                  {{-- Details --}}
                  <div class="flex min-w-0 flex-1 flex-col">
                    <div class="flex items-start justify-between gap-2">
                      <div class="min-w-0">
                        <div class="text-sm font-bold text-gray-900 line-clamp-1" x-text="line.product_name"></div>
                        <div class="mt-0.5 flex items-center gap-1.5 text-xs text-gray-400">
                          <span x-text="line.variant_name" x-show="line.variant_name"></span>
                          <span class="text-[#7C522A] font-medium">In stock</span>
                        </div>
                      </div>
                      <button @click="removeItem(line.id)" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-gray-300 transition hover:bg-red-50 hover:text-red-500" title="Remove">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                      </button>
                    </div>

                    {{-- Price + Quantity --}}
                    <div class="mt-3 flex items-end justify-between">
                      {{-- Quantity Stepper --}}
                      <div class="flex items-center overflow-hidden rounded-xl border-2 transition-colors duration-200"
                        :class="updatingId === line.id ? 'border-gray-200 opacity-60' : 'border-[#7C522A]'">
                        <button type="button" @click="updateQty(line.id, line.quantity - 1)" :disabled="updatingId === line.id"
                          class="flex h-9 w-9 items-center justify-center text-[#7C522A] transition hover:bg-[#7C522A]/10 disabled:pointer-events-none">
                          <template x-if="line.quantity <= 1">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                          </template>
                          <template x-if="line.quantity > 1">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/></svg>
                          </template>
                        </button>
                        <span class="flex h-9 w-9 items-center justify-center text-sm font-extrabold text-[#7C522A]" x-text="line.quantity"></span>
                        <button type="button" @click="updateQty(line.id, line.quantity + 1)" :disabled="updatingId === line.id"
                          class="flex h-9 w-9 items-center justify-center text-[#7C522A] transition hover:bg-[#7C522A]/10 disabled:pointer-events-none">
                          <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        </button>
                      </div>

                      {{-- Price --}}
                      <div class="text-right">
                        <template x-if="line.discount_per_unit > 0">
                          <div class="text-[11px] text-gray-300 line-through" x="'₹' + formatNum(line.unit_list * line.quantity)"></div>
                        </template>
                        <div class="text-base font-extrabold text-gray-900" x-text="'₹' + formatNum(line.line_total)"></div>
                        <template x-if="line.discount_per_unit > 0">
                          <span class="inline-block rounded bg-[#7C522A]/10 px-1.5 py-0.5 text-[10px] font-bold text-[#7C522A]"
                            x-text="'SAVE ₹' + formatNum(line.discount_per_unit * line.quantity)"></span>
                        </template>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </template>

            {{-- Free delivery progress --}}
            <div class="rounded-2xl border p-4 transition-all duration-300"
              :class="grandTotal >= freeAbove ? 'border-[#7C522A]/20 bg-[#7C522A]/[0.03]' : 'border-[#7C522A]/20 bg-[#7C522A]/[0.03]'">
              <template x-if="freeRemaining > 0">
                <div>
                  <div class="flex items-center gap-2 text-xs font-medium text-[#7C522A]">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"/></svg>
                    Add <span x-text="'₹' + formatNum(freeRemaining)" class="font-bold"></span> more to get <span class="font-bold">FREE delivery!</span>
                  </div>
                  <div class="mt-2 h-2 overflow-hidden rounded-full bg-[#7C522A]/10">
                    <div class="h-full rounded-full bg-[#7C522A] transition-all duration-700" :style="'width:' + freeProgress + '%'"></div>
                  </div>
                </div>
              </template>
              <template x-if="freeRemaining <= 0">
                <div class="flex items-center justify-center gap-2 text-xs font-bold text-[#7C522A]">
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                  You've unlocked FREE delivery!
                </div>
              </template>
            </div>
          </div>

          {{-- ===== RIGHT: ORDER SUMMARY ===== --}}
          <div class="lg:sticky lg:top-24 lg:self-start">
            <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

              {{-- Coupon --}}
              <div class="border-b border-gray-50 p-5">
                <form action="{{ route('cart.apply-coupon') }}" method="POST" class="flex gap-2" x-data>
                  @csrf
                  <input type="text" name="coupon_code" placeholder="Enter coupon code"
                    class="flex-1 rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-sm placeholder:text-gray-300 focus:border-[#7C522A] focus:bg-white focus:ring-2 focus:ring-[#7C522A]/10 transition">
                  <button type="submit" class="shrink-0 rounded-xl border-2 border-[#7C522A] bg-[#7C522A] px-5 py-2.5 text-sm font-bold text-white transition hover:bg-[#613E20]">Apply</button>
                </form>
              </div>

              {{-- Bill Details --}}
              <div class="p-5">
                <h3 class="mb-3.5 text-xs font-bold uppercase tracking-wider text-gray-400">Bill Details</h3>

                <div class="space-y-2.5 text-sm">
                  <div class="flex justify-between">
                    <span class="text-gray-500">Item total</span>
                    <span class="font-medium text-gray-900" x-text="'₹' + formatNum(subtotal + productDiscount)"></span>
                  </div>

                  <template x-if="productDiscount > 0">
                    <div class="flex justify-between">
                      <span class="text-[#7C522A]">Discounts</span>
                      <span class="font-medium text-[#7C522A]" x-text="'-₹' + formatNum(productDiscount)"></span>
                    </div>
                  </template>

                  <template x-if="couponDiscount > 0">
                    <div class="flex justify-between">
                      <span class="text-[#7C522A]">Coupon discount</span>
                      <span class="font-medium text-[#7C522A]" x-text="'-₹' + formatNum(couponDiscount)"></span>
                    </div>
                  </template>

                  <div class="flex justify-between">
                    <span class="text-gray-500">Delivery charge</span>
                    <template x-if="deliveryCharge > 0">
                      <span class="font-medium text-gray-900" x-text="'₹' + formatNum(deliveryCharge)"></span>
                    </template>
                    <template x-if="deliveryCharge <= 0">
                      <span class="font-bold text-[#7C522A]">FREE</span>
                    </template>
                  </div>
                </div>

                <div class="my-3.5 border-t border-dashed border-gray-200"></div>

                <div class="flex items-center justify-between">
                  <span class="text-base font-extrabold text-gray-900">Grand Total</span>
                  <span class="text-xl font-extrabold text-gray-900" x-text="'₹' + formatNum(grandTotal)"></span>
                </div>

                <template x-if="totalSavings > 0">
                  <div class="mt-3 rounded-xl bg-[#7C522A]/5 px-4 py-2.5 text-center">
                    <span class="text-xs font-bold text-[#7C522A]" x-text="'You\\'re saving ₹' + formatNum(totalSavings) + '!'"></span>
                  </div>
                </template>
              </div>

              {{-- Checkout Button (Desktop) --}}
              <div class="hidden lg:block px-5 pb-5">
                <a href="{{ route('checkout') }}"
                  class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#7C522A] px-6 py-4 text-sm font-bold text-white shadow-lg shadow-[#7C522A]/25 transition hover:bg-[#613E20]">
                  Proceed to Checkout
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                <p class="mt-2.5 text-center text-[11px] text-gray-300">
                  Free delivery on orders above ₹<span x-text="formatNum(freeAbove)"></span>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
  </div>

  {{-- ===== BOTTOM STICKY BAR (Mobile) ===== --}}
  <template x-if="lines.length > 0">
    <div class="sticky-total-bar fixed inset-x-0 z-40 border-t border-gray-200 bg-white shadow-[0_-4px_20px_rgba(0,0,0,0.08)] lg:hidden">
      <div class="flex items-center gap-4 px-4 py-3.5">
        <div class="flex-1">
          <div class="text-xs text-gray-400">Total</div>
          <div class="text-xl font-extrabold text-gray-900" x-text="'₹' + formatNum(grandTotal)"></div>
          <template x-if="totalSavings > 0">
            <div class="text-[11px] font-bold text-[#7C522A]" x-text="'You save ₹' + formatNum(totalSavings)"></div>
          </template>
        </div>
        <a href="{{ route('checkout') }}"
          class="flex items-center gap-2 rounded-xl bg-[#7C522A] px-8 py-4 text-sm font-bold text-white shadow-lg shadow-[#7C522A]/25 transition hover:bg-[#613E20]">
          Checkout
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
      </div>
    </div>
  </template>
</div>

<script>
function cartPage() {
  return {
    loading: false,
    updatingId: null,
    flash: null,
    lines: @js($lines->map(fn($l) => [
      'id' => $l['item']->id,
      'product_name' => $l['item']->product->name ?? '',
      'variant_name' => $l['item']->variant?->name ?? '',
      'quantity' => $l['item']->quantity,
      'unit_price' => $l['unit_effective'],
      'unit_list' => $l['unit_list'],
      'line_total' => $l['line_total'],
      'discount_per_unit' => $l['discount_per_unit'],
      'in_stock' => $l['in_stock'],
    ])->values()),
    subtotal: @js($breakdown['subtotal']),
    productDiscount: @js($breakdown['product_discount'] ?? 0),
    couponDiscount: @js($breakdown['coupon_discount'] ?? 0),
    deliveryCharge: @js($breakdown['delivery_charge']),
    grandTotal: @js($breakdown['grand_total']),
    freeAbove: {{ (float) setting('delivery.free_above', 500) }},

    get itemCount() {
      return this.lines.reduce((sum, l) => sum + l.quantity, 0);
    },
    get productCount() {
      return this.lines.length;
    },
    get totalSavings() {
      return this.productDiscount + this.couponDiscount;
    },
    get freeRemaining() {
      const current = this.subtotal - this.productDiscount - this.couponDiscount;
      return Math.max(0, this.freeAbove - current);
    },
    get freeProgress() {
      const current = this.subtotal - this.productDiscount - this.couponDiscount;
      return Math.min(100, (current / this.freeAbove) * 100);
    },

    formatNum(n) {
      return Number(n).toLocaleString('en-IN', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    },

    flashMsg(msg, type = 'success') {
      this.flash = { msg, type };
      setTimeout(() => this.flash = null, 3000);
    },

    async updateQty(itemId, qty) {
      if (qty < 0) return;
      if (qty === 0) {
        if (!confirm('Remove this item from your basket?')) return;
      }
      this.updatingId = itemId;
      try {
        const res = await fetch('/cart/items/' + itemId, {
          method: 'PATCH',
          headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            'Accept': 'application/json'
          },
          body: JSON.stringify({ quantity: qty })
        });
        const data = await res.json();
        if (res.ok && data.ok) {
          this.lines = data.lines;
          this.subtotal = data.subtotal;
          this.productDiscount = data.product_discount;
          this.couponDiscount = data.coupon_discount;
          this.deliveryCharge = data.delivery_charge;
          this.grandTotal = data.grand_total;
          Alpine.store('cart').load();
        } else {
          this.flashMsg(data.error || 'Something went wrong', 'error');
        }
      } catch (e) {
        this.flashMsg('Network error. Please try again.', 'error');
      }
      this.updatingId = null;
    },

    async removeItem(itemId) {
      if (!confirm('Remove this item from your basket?')) return;
      this.updatingId = itemId;
      try {
        const res = await fetch('/cart/items/' + itemId, {
          method: 'DELETE',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            'Accept': 'application/json'
          }
        });
        const data = await res.json();
        if (res.ok && data.ok) {
          this.lines = data.lines;
          this.subtotal = data.subtotal;
          this.productDiscount = data.product_discount;
          this.couponDiscount = data.coupon_discount;
          this.deliveryCharge = data.delivery_charge;
          this.grandTotal = data.grand_total;
          Alpine.store('cart').load();
          this.flashMsg(data.message || 'Item removed');
        }
      } catch (e) {
        this.flashMsg('Network error.', 'error');
      }
      this.updatingId = null;
    }
  }
}
</script>
<style>[x-cloak] { display: none !important; }
/* Keep the Total/Checkout bar clear of the fixed mobile bottom nav (h-16 + safe-area). */
.sticky-total-bar { bottom: calc(env(safe-area-inset-bottom, 0px) + 4rem) !important; }
@media (min-width: 1024px) {
  .sticky-total-bar { bottom: 0 !important; }
}
</style>
@endsection
