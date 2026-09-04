@extends('layouts.app', ['title' => 'Order '.$order->order_number])

@section('content')
<div class="min-h-screen bg-gray-50 pb-8">

  {{-- Flash Messages --}}
  @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
      class="fixed top-4 left-1/2 z-50 flex -translate-x-1/2 items-center gap-2 rounded-xl border border-emerald-200 bg-white px-5 py-3 text-sm font-medium text-emerald-700 shadow-xl">
      <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
      {{ session('success') }}
    </div>
  @endif

  <div class="mx-auto max-w-4xl px-4 py-5 sm:px-6 lg:px-8">

    {{-- Back Link --}}
    <a href="{{ route('account.orders') }}" class="mb-5 inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 transition hover:text-[#7C522A]">
      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      My Orders
    </a>

    {{-- ========== ORDER HEADER ========== --}}
    <div class="mb-6 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
      <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
          <div class="flex items-center gap-3">
            <h1 class="text-lg font-extrabold text-gray-900">Order #{{ $order->order_number }}</h1>
            @php
              $statusColors = [
                'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                'confirmed' => 'bg-blue-50 text-blue-700 border-blue-200',
                'preparing' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                'packed' => 'bg-purple-50 text-purple-700 border-purple-200',
                'assigned' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                'out_for_delivery' => 'bg-orange-50 text-orange-700 border-orange-200',
                'delivered' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'cancelled' => 'bg-red-50 text-red-600 border-red-200',
                'returned' => 'bg-gray-50 text-gray-600 border-gray-200',
                'failed_delivery' => 'bg-red-50 text-red-600 border-red-200',
              ];
            @endphp
            <span class="inline-flex items-center gap-1 rounded-full border px-3 py-1 text-xs font-bold {{ $statusColors[$order->status->value] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
              @if($order->status->value === 'delivered')
                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
              @endif
              {{ $order->status->label() }}
            </span>
          </div>
          <p class="mt-1 text-sm text-gray-400">Placed on {{ $order->placed_at?->format('d M Y, h:i A') ?? 'N/A' }}</p>
        </div>

        <div class="flex gap-2">
          @if(in_array($order->status->value, ['pending','confirmed']))
            <form action="{{ route('account.orders.cancel', $order) }}" method="POST" x-data x-on:submit.prevent="if(confirm('Are you sure you want to cancel this order?')) $el.submit()">
              @csrf @method('DELETE')
              <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-100">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Cancel Order
              </button>
            </form>
          @endif
          @if($order->status->value === 'delivered')
            <form action="{{ route('account.orders.reorder', $order) }}" method="POST">
              @csrf
              <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-[#7C522A] px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-[#7C522A]/20 transition hover:bg-[#613E20]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Reorder
              </button>
            </form>
          @endif
        </div>
      </div>
    </div>

    {{-- ========== ORDER TRACKING (Blinkit-style) ========== --}}
    @if(!in_array($order->status->value, ['cancelled', 'returned', 'failed_delivery']))
      <div class="mb-6 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
        <h2 class="mb-5 text-sm font-bold uppercase tracking-wide text-gray-400">Order Progress</h2>

        @php
          $flow = ['pending', 'confirmed', 'preparing', 'packed', 'out_for_delivery', 'delivered'];
          $currentIdx = array_search($order->status->value, $flow);
          if ($currentIdx === false) $currentIdx = -1;
          $progress = $currentIdx >= 0 ? (($currentIdx + 1) / count($flow)) * 100 : 0;

          $stepIcons = [
            'pending' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            'confirmed' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            'preparing' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>',
            'packed' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
            'out_for_delivery' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>',
            'delivered' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
          ];
          $stepLabels = [
            'pending' => 'Order Placed',
            'confirmed' => 'Confirmed',
            'preparing' => 'Preparing',
            'packed' => 'Packed',
            'out_for_delivery' => 'Out for Delivery',
            'delivered' => 'Delivered',
          ];
        @endphp

        {{-- Progress Bar --}}
        <div class="mb-6">
          <div class="h-2 overflow-hidden rounded-full bg-gray-100">
            <div class="h-full rounded-full transition-all duration-700 ease-out {{ $order->status->value === 'cancelled' ? 'bg-red-400' : 'bg-[#7C522A]' }}"
              style="width: {{ $progress }}%"></div>
          </div>
        </div>

        {{-- Steps --}}
        <div class="grid grid-cols-3 gap-3 sm:grid-cols-6">
          @foreach($flow as $i => $status)
            @php
              $reached = $currentIdx >= $i;
              $isCurrent = $currentIdx === $i;
            @endphp
            <div class="text-center">
              <div class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-full transition-all duration-300 {{ $isCurrent ? 'bg-[#7C522A] text-white shadow-lg shadow-[#7C522A]/30 ring-4 ring-[#7C522A]/10' : ($reached ? 'bg-[#7C522A] text-white' : 'bg-gray-100 text-gray-300') }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $stepIcons[$status] !!}</svg>
              </div>
              <div class="text-[11px] font-semibold {{ $isCurrent ? 'text-[#7C522A]' : ($reached ? 'text-gray-700' : 'text-gray-300') }}">{{ $stepLabels[$status] }}</div>
            </div>
          @endforeach
        </div>

        @if($order->status->value === 'out_for_delivery')
          <div class="mt-5 rounded-xl bg-[#7C522A]/5 border border-[#7C522A]/20 p-4 text-center">
            <div class="flex items-center justify-center gap-2 text-sm font-bold text-[#7C522A]">
              <span class="relative flex h-2.5 w-2.5"><span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#7C522A] opacity-75"></span><span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-[#7C522A]"></span></span>
              Your order is on the way!
            </div>
            <p class="mt-1 text-xs text-gray-500">Estimated delivery in 30-45 minutes</p>
          </div>
        @endif
      </div>
    @endif

    {{-- Cancelled Banner --}}
    @if(in_array($order->status->value, ['cancelled', 'returned', 'failed_delivery']))
      <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5 shadow-sm sm:p-6">
        <div class="flex items-center gap-3">
          <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100">
            <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </div>
          <div>
            <div class="text-sm font-bold text-red-700">Order {{ $order->status->label() }}</div>
            @if($order->cancellation_reason)
              <div class="text-xs text-red-500">{{ $order->cancellation_reason }}</div>
            @endif
          </div>
        </div>
        <form action="{{ route('account.orders.reorder', $order) }}" method="POST" class="mt-4">
          @csrf
          <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-[#7C522A] px-5 py-2.5 text-sm font-bold text-white shadow-md transition hover:bg-[#613E20]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Reorder
          </button>
        </form>
      </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[1fr_340px]">

      {{-- ===== LEFT COLUMN ===== --}}
      <div class="space-y-5">

        {{-- ITEMS --}}
        <section class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
          <div class="border-b border-gray-50 px-5 py-4">
            <h2 class="text-sm font-bold text-gray-900">Items Ordered ({{ $order->items->count() }})</h2>
          </div>
          <div class="divide-y divide-gray-50">
            @foreach($order->items as $item)
              <div class="flex items-center gap-4 px-5 py-4 transition hover:bg-gray-50/50">
                <div class="relative h-16 w-16 shrink-0 overflow-hidden rounded-xl border border-gray-100 bg-gray-50 p-1">
                  @if($item->product?->primaryImage)
                    <img src="{{ asset('storage/'.$item->product->primaryImage->thumb_path) }}" alt="" class="h-full w-full object-contain">
                  @else
                    <div class="flex h-full items-center justify-center text-xl">🌿</div>
                  @endif
                  <span class="absolute -top-1 -right-1 flex h-5 min-w-[20px] items-center justify-center rounded-full bg-gray-700 px-1 text-[10px] font-bold text-white">{{ $item->quantity }}</span>
                </div>
                <div class="min-w-0 flex-1">
                  <div class="text-sm font-bold text-gray-900 line-clamp-1">{{ $item->product_name }}</div>
                  @if($item->variant_name)
                    <div class="text-xs text-gray-400">{{ $item->variant_name }}</div>
                  @endif
                  <div class="mt-0.5 text-xs text-gray-400">Qty: {{ $item->quantity }} × ₹{{ number_format($item->unit_price) }}</div>
                </div>
                <div class="text-sm font-extrabold text-gray-900">₹{{ number_format($item->line_total) }}</div>
              </div>
            @endforeach
          </div>
        </section>

        {{-- TIMELINE --}}
        <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
          <h2 class="mb-4 text-sm font-bold text-gray-900">Order Timeline</h2>
          <div class="relative ml-1 border-l-2 border-gray-100 space-y-0">
            @forelse($timeline as $event)
              @php
                $histories = $order->statusHistories->where('to_status', $event['status']->value);
                $history = $histories->first();
              @endphp
              <div class="relative pl-6 pb-5 last:pb-0">
                <div class="absolute -left-[9px] top-0 h-4 w-4 rounded-full border-2 {{ $event['reached'] ? 'border-[#7C522A] bg-[#7C522A]' : 'border-gray-200 bg-white' }}">
                  @if($event['reached'] && !$event['is_current'])
                    <svg class="absolute inset-0.5 h-2.5 w-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                  @endif
                </div>
                <div class="flex items-start justify-between">
                  <div>
                    <div class="text-sm font-bold {{ $event['is_current'] ? 'text-[#7C522A]' : ($event['reached'] ? 'text-gray-900' : 'text-gray-300') }}">
                      {{ $event['status']->label() }}
                    </div>
                    @if($history && $history->note)
                      <div class="text-xs text-gray-400 mt-0.5">{{ $history->note }}</div>
                    @endif
                  </div>
                  <div class="text-[11px] text-gray-400 shrink-0">
                    @if($event['is_current'])
                      <span class="inline-flex items-center gap-1 rounded-full bg-[#7C522A]/10 px-2 py-0.5 text-[10px] font-bold text-[#7C522A]">
                        <span class="h-1 w-1 rounded-full bg-[#7C522A] animate-pulse"></span>
                        Current
                      </span>
                    @elseif($event['reached'])
                      Done
                    @else
                      Pending
                    @endif
                  </div>
                </div>
              </div>
            @empty
              <p class="pl-6 text-sm text-gray-400">No timeline events yet.</p>
            @endforelse
          </div>
        </section>

        {{-- DELIVERY ADDRESS --}}
        <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
          <h2 class="mb-3 text-sm font-bold text-gray-900">Delivery Address</h2>
          <div class="flex items-start gap-3">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#7C522A]/10">
              <svg class="h-4 w-4 text-[#7C522A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div class="text-sm text-gray-600 leading-relaxed">
              <span class="font-bold text-gray-900">{{ $order->ship_name }}</span>
              <span class="text-gray-400"> · </span>
              <span class="text-gray-400">{{ $order->ship_phone }}</span>
              <br>
              {{ $order->ship_house_no }}{{ $order->ship_street ? ', '.$order->ship_street : '' }}{{ $order->ship_area ? ', '.$order->ship_area : '' }}{{ $order->ship_landmark ? ', Near '.$order->ship_landmark : '' }}
              <br>
              {{ $order->ship_city }}, {{ $order->ship_state }} - {{ $order->ship_pincode }}
            </div>
          </div>
        </section>
      </div>

      {{-- ===== RIGHT COLUMN: SUMMARY ===== --}}
      <div class="space-y-4 lg:sticky lg:top-24 lg:self-start">

        {{-- BILL DETAILS --}}
        <div class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
          <div class="border-b border-gray-50 px-5 py-4">
            <h3 class="text-sm font-bold text-gray-900">Bill Details</h3>
          </div>
          <div class="p-5">
            <div class="space-y-2.5 text-sm">
              <div class="flex justify-between">
                <span class="text-gray-500">Item total</span>
                <span class="font-medium text-gray-900">₹{{ number_format($order->subtotal + $order->product_discount) }}</span>
              </div>
              @if($order->product_discount > 0)
                <div class="flex justify-between">
                  <span class="text-[#7C522A]">Product discount</span>
                  <span class="font-medium text-[#7C522A]">-₹{{ number_format($order->product_discount) }}</span>
                </div>
              @endif
              @if($order->coupon_discount > 0)
                <div class="flex justify-between">
                  <span class="text-[#7C522A]">Coupon discount</span>
                  <span class="font-medium text-[#7C522A]">-₹{{ number_format($order->coupon_discount) }}</span>
                </div>
              @endif
              <div class="flex justify-between">
                <span class="text-gray-500">Delivery charge</span>
                @if($order->delivery_charge > 0)
                  <span class="font-medium text-gray-900">₹{{ number_format($order->delivery_charge) }}</span>
                @else
                  <span class="font-bold text-[#7C522A]">FREE</span>
                @endif
              </div>
            </div>
            <div class="my-3.5 border-t border-dashed border-gray-200"></div>
            <div class="flex items-center justify-between">
              <span class="text-base font-extrabold text-gray-900">Total Paid</span>
              <span class="text-xl font-extrabold text-gray-900">₹{{ number_format($order->grand_total) }}</span>
            </div>
            <div class="mt-1 text-right text-[11px] text-gray-400">Inclusive of all taxes</div>
          </div>
        </div>

        {{-- PAYMENT --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
          <h3 class="mb-3 text-sm font-bold text-gray-900">Payment</h3>
          <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-50">
              <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div class="flex-1">
              <div class="text-sm font-bold text-gray-900">Cash on Delivery</div>
              <div class="text-xs text-gray-400">Pay when your order arrives</div>
            </div>
            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[10px] font-bold uppercase
              {{ ($order->payment->status ?? '') === 'collected' ? 'bg-[#7C522A]/10 text-[#7C522A]' : 'bg-amber-50 text-amber-600' }}">
              {{ ucfirst($order->payment->status ?? 'pending') }}
            </span>
          </div>
        </div>

        {{-- COUPON --}}
        @if($order->coupon)
          <div class="rounded-2xl border border-[#7C522A]/20 bg-[#7C522A]/[0.03] p-4">
            <div class="flex items-center gap-2">
              <svg class="h-4 w-4 shrink-0 text-[#7C522A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
              <div class="flex-1">
                <div class="text-sm font-bold text-[#7C522A]">{{ $order->coupon->code }}</div>
                <div class="text-xs text-gray-500">You saved ₹{{ number_format($order->coupon_discount) }}</div>
              </div>
            </div>
          </div>
        @endif

        {{-- Help --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm text-center">
          <p class="text-xs text-gray-400">Need help with this order?</p>
          <a href="mailto:support@aborganicfarm.com" class="mt-2 inline-flex items-center gap-1.5 text-sm font-semibold text-[#7C522A] hover:underline">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Contact Support
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
