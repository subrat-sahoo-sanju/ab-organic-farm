@extends('layouts.delivery')

@section('title', 'Delivery: Order #' . $order->order_number)

@section('content')
<div class="mx-auto max-w-5xl space-y-6">

  {{-- Back Link --}}
  <nav>
    <a href="{{ route('delivery.deliveries') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 transition hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
      <x-lucide-arrow-left class="h-4 w-4" />
      Back to Deliveries
    </a>
  </nav>

  {{-- Page Header --}}
  <div class="flex flex-wrap items-start justify-between gap-4">
    <div>
      <div class="flex items-center gap-3">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Order #{{ $order->order_number }}</h1>
        @php
          $statusColors = [
            'assigned' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400',
            'picked_up' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400',
            'out_for_delivery' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400',
            'delivered' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400',
            'failed' => 'bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-400',
          ];
        @endphp
        <span class="inline-flex items-center rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-wide {{ $statusColors[$assignment->status->value] ?? 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' }}">
          {{ $assignment->status->label() }}
        </span>
      </div>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Placed on {{ $order->placed_at?->format('d M Y, h:i A') ?? 'N/A' }}</p>
    </div>
  </div>

  <div class="grid gap-6 lg:grid-cols-[1fr_380px]">

    {{-- Left Column --}}
    <div class="space-y-6">

      {{-- Items Card --}}
      <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <h2 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
          <x-lucide-package class="h-4 w-4" />
          Order Items
        </h2>
        <div class="divide-y divide-gray-100 dark:divide-gray-800">
          @foreach($order->items as $item)
            <div class="flex items-center gap-4 py-4 first:pt-0 last:pb-0">
              <div class="h-16 w-16 shrink-0 overflow-hidden rounded-xl bg-gray-100 p-1 dark:bg-gray-800">
                @if($item->product?->primaryImage)
                  <img src="{{ asset('storage/'.$item->product->primaryImage->thumb_path) }}" alt="" class="h-full w-full object-contain">
                @else
                  <div class="flex h-full items-center justify-center text-2xl opacity-20">🌿</div>
                @endif
              </div>
              <div class="flex-1 min-w-0">
                <div class="font-semibold text-gray-900 dark:text-white">{{ $item->name_snapshot }}</div>
                @if($item->variant_snapshot)
                  <div class="text-xs text-gray-400 dark:text-gray-500">{{ $item->variant_snapshot }}</div>
                @endif
                <div class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">Qty: {{ $item->quantity }} × ₹{{ number_format($item->unit_price) }}</div>
              </div>
              <div class="text-sm font-bold text-gray-900 dark:text-white">₹{{ number_format($item->line_total) }}</div>
            </div>
          @endforeach
        </div>
      </section>

      {{-- Payment Summary --}}
      <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <h2 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
          <x-lucide-receipt class="h-4 w-4" />
          Payment Summary
        </h2>
        <div class="space-y-3 text-sm">
          <div class="flex justify-between text-gray-500 dark:text-gray-400">
            <span>Subtotal</span>
            <span>₹{{ number_format($order->subtotal) }}</span>
          </div>
          @if($order->discount_total > 0)
            <div class="flex justify-between text-emerald-600 dark:text-emerald-400">
              <span>Discount</span>
              <span>-₹{{ number_format($order->discount_total) }}</span>
            </div>
          @endif
          @if($order->delivery_charge > 0)
            <div class="flex justify-between text-gray-500 dark:text-gray-400">
              <span>Delivery</span>
              <span>₹{{ number_format($order->delivery_charge) }}</span>
            </div>
          @endif
          @if($order->tax_total > 0)
            <div class="flex justify-between text-gray-500 dark:text-gray-400">
              <span>Tax</span>
              <span>₹{{ number_format($order->tax_total) }}</span>
            </div>
          @endif
          <div class="border-t border-gray-200 pt-3 flex justify-between text-base font-bold text-gray-900 dark:text-white dark:border-gray-800">
            <span>Total</span>
            <span>₹{{ number_format($order->grand_total) }}</span>
          </div>
        </div>

        <div class="mt-4 flex items-center gap-3 rounded-xl bg-gray-50 px-4 py-3 dark:bg-gray-800/50">
          <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Method:</span>
          <span class="text-sm font-bold text-gray-900 dark:text-white">{{ strtoupper($order->payment->method ?? 'COD') }}</span>
          @php
            $payStatus = $order->payment->status ?? 'pending';
            $payColor = match($payStatus) {
              'collected' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400',
              'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400',
              default => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
            };
          @endphp
          <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold uppercase {{ $payColor }}">
            {{ ucfirst($payStatus) }}
          </span>
        </div>
      </section>
    </div>

    {{-- Right Column (Sticky) --}}
    <div class="space-y-4 lg:sticky lg:top-24 lg:self-start">

      {{-- Order Info --}}
      <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <h3 class="mb-3 flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
          <x-lucide-hash class="h-3.5 w-3.5" />
          Order Info
        </h3>
        <div class="space-y-2 text-sm">
          <div class="flex justify-between">
            <span class="text-gray-500 dark:text-gray-400">Order</span>
            <span class="font-bold text-gray-900 dark:text-white">#{{ $order->order_number }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-500 dark:text-gray-400">Placed</span>
            <span class="text-gray-700 dark:text-gray-300">{{ $order->placed_at?->format('d M, h:i A') ?? 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-500 dark:text-gray-400">Status</span>
            <span class="font-bold text-gray-900 dark:text-white">{{ $order->status->label() }}</span>
          </div>
        </div>
      </div>

      {{-- Customer --}}
      <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <h3 class="mb-3 flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
          <x-lucide-user class="h-3.5 w-3.5" />
          Customer
        </h3>
        <div class="space-y-2">
          <p class="font-semibold text-gray-900 dark:text-white">{{ $customer->name ?? '—' }}</p>
          @if($customer->phone)
            <a href="tel:{{ $customer->phone }}" class="inline-flex items-center gap-2 text-sm text-emerald-600 transition hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300">
              <x-lucide-phone class="h-3.5 w-3.5" />
              {{ $customer->phone }}
            </a>
          @endif
        </div>
      </div>

      {{-- Delivery Address --}}
      <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <h3 class="mb-3 flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
          <x-lucide-map-pin class="h-3.5 w-3.5" />
          Delivery Address
        </h3>
        <div class="space-y-0.5 text-sm leading-relaxed text-gray-600 dark:text-gray-400">
          @if($address->address_line_1 ?? null)
            <p>{{ $address->address_line_1 }}</p>
          @endif
          @if($address->address_line_2 ?? null)
            <p>{{ $address->address_line_2 }}</p>
          @endif
          <p>{{ $address->city ?? '' }}, {{ $address->state ?? '' }} {{ $address->pincode ?? '' }}</p>
          @if($address->landmark ?? null)
            <p class="text-xs text-gray-400 dark:text-gray-500">Landmark: {{ $address->landmark }}</p>
          @endif
          @if($address->latitude && $address->longitude)
            <a href="https://www.google.com/maps?q={{ $address->latitude }},{{ $address->longitude }}" target="_blank" rel="noopener" class="mt-2 inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 transition hover:text-emerald-700 dark:text-emerald-400">
              <x-lucide-map-pin class="h-3 w-3" />
              Open in Google Maps
            </a>
          @endif
        </div>
      </div>

      {{-- Action Buttons --}}
      @if(in_array($assignment->status->value, ['assigned', 'out_for_delivery']))
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
          <h3 class="mb-3 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Actions</h3>
          <div class="space-y-3">
            @if($assignment->status->value === 'assigned')
              <form action="{{ route('delivery.pickup', $assignment) }}" method="POST">
                @csrf
                <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 hover:shadow-md">
                  <x-lucide-truck class="h-4 w-4" />
                  Picked Up
                </button>
              </form>
            @endif

            @if($assignment->status->value === 'out_for_delivery')
              <form action="{{ route('delivery.delivered', $assignment) }}" method="POST">
                @csrf
                <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 hover:shadow-md">
                  <x-lucide-check-circle class="h-4 w-4" />
                  Mark Delivered
                </button>
              </form>

              <form action="{{ route('delivery.failed', $assignment) }}" method="POST" x-data="{ showReason: false }">
                @csrf
                <div x-show="!showReason">
                  <button type="button" @click="showReason = true" class="flex w-full items-center justify-center gap-2 rounded-xl border border-red-200 bg-white px-4 py-3 text-sm font-semibold text-red-600 transition hover:bg-red-50 dark:border-red-900 dark:bg-transparent dark:text-red-400 dark:hover:bg-red-950">
                    <x-lucide-x-circle class="h-4 w-4" />
                    Mark Failed
                  </button>
                </div>
                <div x-show="showReason" x-cloak class="space-y-3">
                  <div>
                    <label class="mb-1.5 block text-xs font-semibold text-gray-500 dark:text-gray-400">Reason for failure</label>
                    <input type="text" name="reason" placeholder="e.g. Customer not available" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 transition focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-100 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500 dark:focus:border-red-500 dark:focus:ring-red-900/30" required>
                  </div>
                  <div class="flex gap-2">
                    <button type="button" @click="showReason = false" class="flex-1 rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-500 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                      Cancel
                    </button>
                    <button type="submit" class="flex-1 rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700">
                      Confirm Failed
                    </button>
                  </div>
                </div>
              </form>
            @endif
          </div>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
