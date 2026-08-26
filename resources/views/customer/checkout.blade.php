@extends('layouts.app', ['title' => 'Checkout — AB Organic Farm'])

@section('content')
<div class="min-h-screen bg-gray-50 pb-32 lg:pb-8" x-data="checkoutPage()" x-init="init()">

  {{-- Flash Messages --}}
  @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
      class="fixed top-4 left-1/2 z-50 flex -translate-x-1/2 items-center gap-2 rounded-xl border border-emerald-200 bg-white px-5 py-3 text-sm font-medium text-emerald-700 shadow-xl">
      <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
      {{ session('success') }}
    </div>
  @endif
  @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
      class="fixed top-4 left-1/2 z-50 flex -translate-x-1/2 items-center gap-2 rounded-xl border border-red-200 bg-white px-5 py-3 text-sm font-medium text-red-600 shadow-xl">
      <svg class="h-5 w-5 shrink-0 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      {{ session('error') }}
    </div>
  @endif

  <div class="mx-auto max-w-6xl px-4 py-5 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="mb-6 flex items-center gap-3">
      <a href="{{ route('cart.index') }}" class="flex h-9 w-9 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 transition hover:bg-gray-50">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </a>
      <div>
        <h1 class="text-lg font-extrabold text-gray-900">Checkout</h1>
        <p class="text-xs text-gray-400">{{ $breakdown['lines']->count() }} item{{ $breakdown['lines']->count() > 1 ? 's' : '' }} in your basket</p>
      </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1fr_400px]">

      {{-- ===== LEFT COLUMN ===== --}}
      <div class="space-y-4">

        {{-- STEP 1: DELIVERY ADDRESS --}}
        <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
          {{-- Header --}}
          <div class="flex items-center gap-3 border-b border-gray-50 px-5 py-4">
            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#0C831F] text-xs font-bold text-white">1</span>
            <h2 class="flex-1 text-sm font-bold text-gray-900">Delivery Address</h2>
            @error('address_id')
              <span class="rounded-full bg-red-50 px-2.5 py-0.5 text-[11px] font-semibold text-red-500">{{ $message }}</span>
            @enderror
          </div>

          {{-- Saved Addresses --}}
          <div class="px-5 pt-4 pb-2">
            @if($addresses->count())
              <div class="space-y-2.5">
                @foreach($addresses as $address)
                  <label
                    class="flex cursor-pointer items-start gap-3 rounded-xl border-2 p-3.5 transition-all duration-200"
                    :class="selectedAddress === {{ $address->id }} ? 'border-[#0C831F] bg-[#0C831F]/[0.03] shadow-sm' : 'border-gray-100 hover:border-gray-200'"
                    @click="selectedAddress = {{ $address->id }}">
                    <input type="radio" name="address_id" value="{{ $address->id }}"
                      {{ $address->id === ($defaultAddress?->id) ? 'checked' : '' }}
                      class="mt-0.5 h-4 w-4 border-gray-300 text-[#0C831F] focus:ring-[#0C831F]"
                      @change="selectedAddress = {{ $address->id }}">
                    <div class="min-w-0 flex-1">
                      <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider"
                          :class="selectedAddress === {{ $address->id }} ? 'bg-[#0C831F]/10 text-[#0C831F]' : 'bg-gray-100 text-gray-500'">
                          @if($address->label === 'home')
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                          @elseif($address->label === 'office')
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                          @else
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                          @endif
                          {{ $address->label }}
                        </span>
                        <span class="text-sm font-bold text-gray-900">{{ $address->name }}</span>
                        <span class="text-xs text-gray-400">{{ $address->phone }}</span>
                      </div>
                      <p class="mt-1 text-xs leading-relaxed text-gray-500">
                        {{ $address->house_no }}{{ $address->street ? ', '.$address->street : '' }}{{ $address->area ? ', '.$address->area : '' }}{{ $address->landmark ? ', Near '.$address->landmark : '' }}<br>
                        {{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}
                      </p>
                    </div>
                    <div class="mt-0.5 shrink-0">
                      <div class="h-5 w-5 rounded-full border-2 flex items-center justify-center transition-all duration-200"
                        :class="selectedAddress === {{ $address->id }} ? 'border-[#0C831F] bg-[#0C831F]' : 'border-gray-300'">
                        <div class="h-2 w-2 rounded-full bg-white" x-show="selectedAddress === {{ $address->id }}"></div>
                      </div>
                    </div>
                  </label>
                @endforeach
              </div>
            @else
              <div class="rounded-xl border border-dashed border-gray-200 bg-gray-50/50 py-8 text-center">
                <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <p class="mt-2 text-sm font-medium text-gray-500">No delivery addresses yet</p>
                <p class="mt-1 text-xs text-gray-400">Add one below to continue</p>
              </div>
            @endif
          </div>

          {{-- Add New Address Toggle --}}
          <div class="px-5 pb-4">
            <button type="button" @click="showAddressForm = !showAddressForm"
              class="flex w-full items-center justify-center gap-2 rounded-xl border-2 border-dashed border-[#0C831F]/30 py-3 text-sm font-semibold text-[#0C831F] transition-all duration-200 hover:border-[#0C831F] hover:bg-[#0C831F]/5">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
              <span x-text="showAddressForm ? 'Cancel' : 'Add New Address'">Add New Address</span>
            </button>
          </div>

          {{-- Inline Address Form --}}
          <div x-show="showAddressForm" x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition duration-200 ease-in" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-2" x-cloak class="border-t border-gray-100 bg-gray-50/50 px-5 py-5">
            <div class="space-y-3.5">
              {{-- Label --}}
              <div>
                <label class="mb-1.5 block text-xs font-bold text-gray-600">Address Type</label>
                <div class="flex gap-2">
                  <template x-for="lbl in ['home','office','other']" :key="lbl">
                    <button type="button" @click="newAddress.label = lbl"
                      class="flex-1 rounded-xl border-2 py-2.5 text-xs font-bold capitalize transition-all duration-200"
                      :class="newAddress.label === lbl ? 'border-[#0C831F] bg-[#0C831F] text-white' : 'border-gray-200 bg-white text-gray-500 hover:border-gray-300'"
                      x-text="lbl">
                    </button>
                  </template>
                </div>
              </div>

              {{-- Name & Phone --}}
              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="mb-1 block text-xs font-bold text-gray-600">Full Name *</label>
                  <input type="text" x-model="newAddress.name" placeholder="John Doe"
                    class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-3 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-[#0C831F] focus:ring-2 focus:ring-[#0C831F]/10">
                  <template x-if="addressErrors.name">
                    <p class="mt-1 text-[11px] text-red-500" x-text="addressErrors.name[0]"></p>
                  </template>
                </div>
                <div>
                  <label class="mb-1 block text-xs font-bold text-gray-600">Phone *</label>
                  <input type="tel" x-model="newAddress.phone" placeholder="9876543210" maxlength="10"
                    class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-3 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-[#0C831F] focus:ring-2 focus:ring-[#0C831F]/10">
                  <template x-if="addressErrors.phone">
                    <p class="mt-1 text-[11px] text-red-500" x-text="addressErrors.phone[0]"></p>
                  </template>
                </div>
              </div>

              {{-- House/Flat --}}
              <div>
                <label class="mb-1 block text-xs font-bold text-gray-600">House / Flat No. *</label>
                <input type="text" x-model="newAddress.house_no" placeholder="Flat 12B, Skyline Apartments"
                  class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-3 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-[#0C831F] focus:ring-2 focus:ring-[#0C831F]/10">
                <template x-if="addressErrors.house_no">
                  <p class="mt-1 text-[11px] text-red-500" x-text="addressErrors.house_no[0]"></p>
                </template>
              </div>

              {{-- Street --}}
              <div>
                <label class="mb-1 block text-xs font-bold text-gray-600">Street / Road</label>
                <input type="text" x-model="newAddress.street" placeholder="MG Road, 5th Cross"
                  class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-3 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-[#0C831F] focus:ring-2 focus:ring-[#0C831F]/10">
              </div>

              {{-- Area & Landmark --}}
              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="mb-1 block text-xs font-bold text-gray-600">Area / Locality</label>
                  <input type="text" x-model="newAddress.area" placeholder="Koramangala"
                    class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-3 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-[#0C831F] focus:ring-2 focus:ring-[#0C831F]/10">
                </div>
                <div>
                  <label class="mb-1 block text-xs font-bold text-gray-600">Landmark</label>
                  <input type="text" x-model="newAddress.landmark" placeholder="Near City Hospital"
                    class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-3 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-[#0C831F] focus:ring-2 focus:ring-[#0C831F]/10">
                </div>
              </div>

              {{-- City, State, Pincode --}}
              <div class="grid grid-cols-3 gap-3">
                <div>
                  <label class="mb-1 block text-xs font-bold text-gray-600">City *</label>
                  <input type="text" x-model="newAddress.city" placeholder="Bhubaneswar"
                    class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-3 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-[#0C831F] focus:ring-2 focus:ring-[#0C831F]/10">
                  <template x-if="addressErrors.city">
                    <p class="mt-1 text-[11px] text-red-500" x-text="addressErrors.city[0]"></p>
                  </template>
                </div>
                <div>
                  <label class="mb-1 block text-xs font-bold text-gray-600">State *</label>
                  <input type="text" x-model="newAddress.state" placeholder="Odisha"
                    class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-3 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-[#0C831F] focus:ring-2 focus:ring-[#0C831F]/10">
                  <template x-if="addressErrors.state">
                    <p class="mt-1 text-[11px] text-red-500" x-text="addressErrors.state[0]"></p>
                  </template>
                </div>
                <div>
                  <label class="mb-1 block text-xs font-bold text-gray-600">Pincode *</label>
                  <input type="text" x-model="newAddress.pincode" placeholder="751001" maxlength="6"
                    class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-3 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-[#0C831F] focus:ring-2 focus:ring-[#0C831F]/10">
                  <template x-if="addressErrors.pincode">
                    <p class="mt-1 text-[11px] text-red-500" x-text="addressErrors.pincode[0]"></p>
                  </template>
                </div>
              </div>

              {{-- Save Button --}}
              <button type="button" @click="saveAddress()" :disabled="savingAddress"
                class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#0C831F] px-4 py-3.5 text-sm font-bold text-white shadow-md shadow-[#0C831F]/20 transition-all duration-200 hover:bg-[#096818] disabled:opacity-60">
                <template x-if="!savingAddress">
                  <span class="flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Save & Select Address
                  </span>
                </template>
                <template x-if="savingAddress">
                  <span class="flex items-center gap-2">
                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    Saving...
                  </span>
                </template>
              </button>
            </div>
          </div>
        </section>

        {{-- STEP 2: DELIVERY TIME --}}
        <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
          <div class="flex items-center gap-3 border-b border-gray-50 px-5 py-4">
            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#0C831F] text-xs font-bold text-white">2</span>
            <h2 class="text-sm font-bold text-gray-900">Delivery Time</h2>
          </div>

          <div class="grid grid-cols-2 gap-2.5 p-5 sm:grid-cols-4">
            @foreach($deliverySlots as $slot)
              <button type="button"
                @click="{{ $slot['available'] ? "selectedSlot = '{$slot['value']}'" : '' }}"
                {{ !$slot['available'] ? 'disabled' : '' }}
                class="relative rounded-xl border-2 p-3.5 text-center transition-all duration-200 {{ $slot['available'] ? 'cursor-pointer hover:shadow-sm' : 'cursor-not-allowed opacity-40' }}"
                :class="selectedSlot === '{{ $slot['value'] }}' ? 'border-[#0C831F] bg-[#0C831F]/[0.04] shadow-sm' : 'border-gray-100'">
                @if(!$slot['available'])
                  <div class="absolute inset-0 flex items-center justify-center rounded-xl bg-gray-50/80">
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Passed</span>
                  </div>
                @endif
                <div class="text-sm font-bold" :class="selectedSlot === '{{ $slot['value'] }}' ? 'text-[#0C831F]' : 'text-gray-900'">{{ $slot['label'] }}</div>
                <div class="mt-0.5 text-[11px] text-gray-400">{{ $slot['sub'] }}</div>
                <div class="mt-2 flex justify-center">
                  <div class="h-4 w-4 rounded-full border-2 flex items-center justify-center transition-all"
                    :class="selectedSlot === '{{ $slot['value'] }}' ? 'border-[#0C831F] bg-[#0C831F]' : 'border-gray-300'">
                    <div class="h-1.5 w-1.5 rounded-full bg-white" x-show="selectedSlot === '{{ $slot['value'] }}'"></div>
                  </div>
                </div>
              </button>
            @endforeach
          </div>

          @error('delivery_slot')
            <div class="px-5 pb-4">
              <p class="rounded-lg bg-red-50 px-3 py-2 text-xs font-medium text-red-500">{{ $message }}</p>
            </div>
          @enderror
        </section>

        {{-- STEP 3: PAYMENT METHOD --}}
        <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
          <div class="flex items-center gap-3 border-b border-gray-50 px-5 py-4">
            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#0C831F] text-xs font-bold text-white">3</span>
            <h2 class="text-sm font-bold text-gray-900">Payment Method</h2>
          </div>

          <div class="p-5">
            <div class="rounded-xl border-2 border-[#0C831F] bg-[#0C831F]/[0.03] p-4">
              <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#0C831F]/10">
                  <svg class="h-6 w-6 text-[#0C831F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div class="flex-1">
                  <div class="text-sm font-bold text-gray-900">Cash on Delivery (COD)</div>
                  <div class="text-xs text-gray-400">Pay with cash when your order arrives</div>
                </div>
                <div class="flex h-6 w-6 items-center justify-center rounded-full bg-[#0C831F]">
                  <svg class="h-3.5 w-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </div>
              </div>
            </div>

            @if(!$codEnabled)
              <div class="mt-3 rounded-lg bg-amber-50 border border-amber-200 px-4 py-3 text-xs font-medium text-amber-700">
                Cash on Delivery is currently unavailable. Please contact support.
              </div>
            @endif
          </div>
        </section>

        {{-- STEP 4: ORDER ITEMS (Mobile) --}}
        <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm lg:hidden">
          <div class="flex items-center gap-3 border-b border-gray-50 px-5 py-4">
            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#0C831F] text-xs font-bold text-white">4</span>
            <h2 class="text-sm font-bold text-gray-900">Order Items ({{ $breakdown['lines']->count() }})</h2>
          </div>
          <div class="divide-y divide-gray-50 px-5 py-3">
            @foreach($breakdown['lines'] as $line)
              <div class="flex items-center gap-3 py-3 first:pt-0 last:pb-0">
                <div class="relative h-14 w-14 shrink-0 overflow-hidden rounded-xl border border-gray-100 bg-gray-50 p-1">
                  @if($line['item']->product->primaryImage)
                    <img src="{{ asset('storage/'.$line['item']->product->primaryImage->thumb_path) }}" alt="" class="h-full w-full object-contain">
                  @else
                    <div class="flex h-full items-center justify-center text-xl">🌿</div>
                  @endif
                  <span class="absolute -top-1 -right-1 flex h-5 min-w-[20px] items-center justify-center rounded-full bg-[#0C831F] px-1 text-[10px] font-bold text-white">{{ $line['item']->quantity }}</span>
                </div>
                <div class="min-w-0 flex-1">
                  <div class="text-sm font-semibold text-gray-900 line-clamp-1">{{ $line['item']->product->name }}</div>
                  <div class="text-xs text-gray-400">{{ $line['item']->variant->name ?? '' }}</div>
                  <div class="mt-0.5 flex items-center gap-2">
                    @if($line['discount_per_unit'] > 0)
                      <span class="text-xs text-gray-400 line-through">₹{{ number_format($line['unit_list']) }}</span>
                    @endif
                    <span class="text-sm font-bold text-gray-900">₹{{ number_format($line['line_total']) }}</span>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </section>
      </div>

      {{-- ===== RIGHT: ORDER SUMMARY (Sticky Desktop) ===== --}}
      <div class="hidden lg:block lg:sticky lg:top-24 lg:self-start">
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

          {{-- Desktop: Items Preview --}}
          <div class="border-b border-gray-50 px-5 py-4">
            <h3 class="text-sm font-bold text-gray-900">Order Summary ({{ $breakdown['lines']->count() }} items)</h3>
            <div class="mt-3 max-h-52 space-y-2.5 overflow-y-auto pr-1">
              @foreach($breakdown['lines'] as $line)
                <div class="flex items-center gap-3">
                  <div class="relative h-10 w-10 shrink-0 overflow-hidden rounded-lg border border-gray-100 bg-gray-50 p-0.5">
                    @if($line['item']->product->primaryImage)
                      <img src="{{ asset('storage/'.$line['item']->product->primaryImage->thumb_path) }}" alt="" class="h-full w-full object-contain">
                    @else
                      <div class="flex h-full items-center justify-center text-sm">🌿</div>
                    @endif
                    <span class="absolute -top-1 -right-1 flex h-4 min-w-[16px] items-center justify-center rounded-full bg-gray-700 px-0.5 text-[9px] font-bold text-white">{{ $line['item']->quantity }}</span>
                  </div>
                  <div class="min-w-0 flex-1">
                    <div class="text-xs font-semibold text-gray-900 line-clamp-1">{{ $line['item']->product->name }}</div>
                    <div class="text-[11px] text-gray-400">{{ $line['item']->variant->name ?? '' }}</div>
                  </div>
                  <div class="text-xs font-bold text-gray-900">₹{{ number_format($line['line_total']) }}</div>
                </div>
              @endforeach
            </div>
          </div>

          {{-- Coupon --}}
          <div class="border-b border-gray-50 px-5 py-3.5">
            @if($cart && $cart->coupon_id)
              <div class="flex items-center gap-2 rounded-xl bg-[#0C831F]/5 px-3.5 py-2.5">
                <svg class="h-4 w-4 shrink-0 text-[#0C831F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                <span class="flex-1 text-xs font-bold text-[#0C831F]">{{ $cart->coupon->code }}</span>
                <span class="text-xs font-bold text-[#0C831F]">-₹{{ number_format($breakdown['coupon_discount']) }}</span>
              </div>
            @else
              <div class="flex items-center gap-2">
                <svg class="h-4 w-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                <span class="flex-1 text-xs font-medium text-gray-500">Apply Coupon</span>
                <a href="{{ route('cart.index') }}" class="text-xs font-bold text-[#0C831F] hover:underline">Add</a>
              </div>
            @endif
          </div>

          {{-- Bill Details --}}
          <div class="px-5 py-4">
            <h3 class="mb-3 text-xs font-bold uppercase tracking-wider text-gray-400">Bill Details</h3>
            <div class="space-y-2 text-sm">
              <div class="flex justify-between">
                <span class="text-gray-500">Item total</span>
                <span class="font-medium text-gray-900">₹{{ number_format($breakdown['subtotal'] + ($breakdown['product_discount'] ?? 0)) }}</span>
              </div>

              @if(($breakdown['product_discount'] ?? 0) > 0)
                <div class="flex justify-between">
                  <span class="text-[#0C831F]">Product discount</span>
                  <span class="font-medium text-[#0C831F]">-₹{{ number_format($breakdown['product_discount']) }}</span>
                </div>
              @endif

              @if(($breakdown['coupon_discount'] ?? 0) > 0)
                <div class="flex justify-between">
                  <span class="text-[#0C831F]">Coupon discount</span>
                  <span class="font-medium text-[#0C831F]">-₹{{ number_format($breakdown['coupon_discount']) }}</span>
                </div>
              @endif

              <div class="flex justify-between">
                <span class="text-gray-500">Delivery charge</span>
                @if($breakdown['delivery_charge'] > 0)
                  <span class="font-medium text-gray-900">₹{{ number_format($breakdown['delivery_charge']) }}</span>
                @else
                  <span class="font-bold text-[#0C831F]">FREE</span>
                @endif
              </div>
            </div>

            <div class="my-3.5 border-t border-dashed border-gray-200"></div>

            <div class="flex items-center justify-between">
              <span class="text-base font-extrabold text-gray-900">Grand Total</span>
              <span class="text-lg font-extrabold text-gray-900">₹{{ number_format($breakdown['grand_total']) }}</span>
            </div>
            <div class="mt-1 text-right text-[11px] text-gray-400">Inclusive of all taxes</div>
          </div>

          {{-- You Save --}}
          @if(($breakdown['product_discount'] ?? 0) + ($breakdown['coupon_discount'] ?? 0) > 0)
            <div class="mx-5 mb-4 rounded-xl bg-[#0C831F]/5 px-4 py-3 text-center">
              <span class="text-xs font-bold text-[#0C831F]">You're saving ₹{{ number_format(($breakdown['product_discount'] ?? 0) + ($breakdown['coupon_discount'] ?? 0)) }} on this order!</span>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- ===== BOTTOM STICKY BAR (Mobile + Desktop) ===== --}}
  <div class="fixed bottom-0 left-0 right-0 z-40 border-t border-gray-200 bg-white shadow-[0_-4px_20px_rgba(0,0,0,0.08)]">
    <div class="mx-auto flex max-w-6xl items-center gap-4 px-4 py-3.5 sm:px-6 lg:px-8">
      {{-- Total --}}
      <div class="flex-1">
        <div class="text-xs text-gray-400">Total</div>
        <div class="text-xl font-extrabold text-gray-900">₹{{ number_format($breakdown['grand_total']) }}</div>
        @if(($breakdown['product_discount'] ?? 0) + ($breakdown['coupon_discount'] ?? 0) > 0)
          <div class="text-[11px] font-bold text-[#0C831F]">You save ₹{{ number_format(($breakdown['product_discount'] ?? 0) + ($breakdown['coupon_discount'] ?? 0)) }}</div>
        @endif
      </div>

      {{-- Place Order Button --}}
      <button type="button" @click="placeOrder()"
        :disabled="placing || !canPlace"
        class="flex items-center justify-center gap-2 rounded-xl px-8 py-4 text-sm font-bold text-white shadow-lg transition-all duration-200 disabled:cursor-not-allowed disabled:opacity-50"
        :class="canPlace && !placing ? 'bg-[#0C831F] shadow-[#0C831F]/25 hover:bg-[#096818]' : 'bg-gray-300'">
        <template x-if="!placing">
          <span class="flex items-center gap-2">
            Place Order
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          </span>
        </template>
        <template x-if="placing">
          <span class="flex items-center gap-2">
            <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
            Placing Order...
          </span>
        </template>
      </button>
    </div>
  </div>

  {{-- Hidden Form --}}
  <form action="{{ route('checkout.place') }}" method="POST" id="checkout-form" class="hidden">
    @csrf
    <input type="hidden" name="idempotency_token" :value="token">
    <input type="hidden" name="payment_method" value="cod">
    <input type="hidden" name="address_id" :value="selectedAddress">
    <input type="hidden" name="delivery_slot" :value="selectedSlot">
  </form>

  {{-- Validation Errors Banner --}}
  <div x-show="validationError" x-transition x-cloak
    class="fixed bottom-24 left-1/2 z-50 flex -translate-x-1/2 items-center gap-2 rounded-xl bg-red-600 px-5 py-3 text-sm font-medium text-white shadow-2xl">
    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
    <span x-text="validationError"></span>
  </div>
</div>

<script>
function checkoutPage() {
  return {
    token: Array.from({length: 64}, () => 'abcdef0123456789'[Math.floor(Math.random()*16)]).join(''),
    placing: false,
    selectedAddress: @js($defaultAddress?->id),
    selectedSlot: 'evening',
    showAddressForm: false,
    savingAddress: false,
    addressErrors: {},
    validationError: null,

    newAddress: {
      label: 'home',
      name: '',
      phone: '',
      house_no: '',
      street: '',
      area: '',
      landmark: '',
      city: '',
      state: '',
      pincode: '',
    },

    get canPlace() {
      return this.selectedAddress && this.selectedSlot;
    },

    init() {
      if (!this.selectedSlot) {
        this.selectedSlot = 'evening';
      }
    },

    async saveAddress() {
      this.addressErrors = {};
      this.savingAddress = true;
      this.validationError = null;

      try {
        const resp = await fetch('{{ route("checkout.add-address") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
          },
          body: JSON.stringify(this.newAddress),
        });

        const data = await resp.json();

        if (resp.ok && data.ok) {
          this.selectedAddress = data.address.id;

          // Add to page via DOM
          const container = document.querySelector('[x-ref="addressList"]');
          this.showAddressForm = false;
          this.newAddress = { label:'home', name:'', phone:'', house_no:'', street:'', area:'', landmark:'', city:'', state:'', pincode:'' };
          // Reload to show new address
          window.location.reload();
        } else {
          this.addressErrors = data.errors || {};
          if (!data.errors) {
            this.validationError = 'Failed to save address. Please try again.';
            setTimeout(() => this.validationError = null, 4000);
          }
        }
      } catch (e) {
        this.validationError = 'Network error. Please try again.';
        setTimeout(() => this.validationError = null, 4000);
      } finally {
        this.savingAddress = false;
      }
    },

    placeOrder() {
      this.validationError = null;

      if (!this.selectedAddress) {
        this.validationError = 'Please select a delivery address';
        setTimeout(() => this.validationError = null, 4000);
        return;
      }
      if (!this.selectedSlot) {
        this.validationError = 'Please select a delivery time slot';
        setTimeout(() => this.validationError = null, 4000);
        return;
      }

      this.placing = true;

      // Sync hidden inputs and submit form
      const form = document.getElementById('checkout-form');
      const addrInput = form.querySelector('input[name="address_id"]');
      const tokenInput = form.querySelector('input[name="idempotency_token"]');
      if (addrInput) addrInput.value = this.selectedAddress;
      if (tokenInput) tokenInput.value = this.token;

      form.submit();
    },
  }
}
</script>

<style>
[x-cloak] { display: none !important; }
</style>
@endsection
