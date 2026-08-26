@extends('layouts.admin', ['title' => 'Order #'.$order->order_number])

@section('content')
<div class="space-y-6">
  <nav>
    <a href="{{ route('admin.orders.index') }}" class="adm-back">
      <x-lucide-arrow-left class="h-3.5 w-3.5" />
      Back to Orders
    </a>
  </nav>

  <div class="flex flex-wrap items-start justify-between gap-4">
    <div>
      <h1 class="adm-page-title text-2xl">Order {{ $order->order_number }}</h1>
      <p class="mt-1 text-sm adm-text-muted">Placed {{ $order->placed_at?->format('d M Y, h:i A') ?? '—' }}</p>
    </div>
    @php
      $badgeColor = match($order->status->value) {
        'pending' => 'bg-amber-100 text-amber-700',
        'confirmed' => 'bg-blue-100 text-blue-700',
        'preparing' => 'bg-indigo-100 text-indigo-700',
        'packed' => 'bg-slate-100 text-slate-700',
        'assigned' => 'bg-cyan-100 text-cyan-700',
        'out_for_delivery' => 'bg-orange-100 text-orange-700',
        'delivered' => 'bg-green-100 text-green-700',
        'cancelled' => 'bg-red-100 text-red-600',
        'returned', 'failed_delivery' => 'bg-purple-100 text-purple-700',
        default => 'bg-sage/10 text-charcoal/60',
      };
    @endphp
    <span class="adm-badge {{ $badgeColor }}">{{ $order->status->label() }}</span>
  </div>

  <div class="adm-section p-5">
    @php
      $timelineSteps = ['pending','confirmed','preparing','packed','assigned','out_for_delivery','delivered'];
      $currentIndex = array_search($order->status->value, $timelineSteps);
      $isTerminal = in_array($order->status->value, ['cancelled','returned','failed_delivery']);
    @endphp
    <div class="relative flex items-center gap-1 overflow-x-auto pb-2">
      @foreach($timelineSteps as $i => $step)
        @php
          $reached = $currentIndex !== false && $i <= $currentIndex;
          $isCurrent = $i === $currentIndex;
        @endphp
        <div class="flex items-center gap-1.5">
          <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-xs font-bold transition
            {{ $isTerminal ? 'bg-red-100 text-red-500' : ($reached ? 'bg-forest text-white' : 'bg-sage/10 text-charcoal/30') }}">
            @if($reached && !$isCurrent)✓@else{{ $i + 1 }}@endif
          </div>
          <span class="text-[11px] whitespace-nowrap pr-1 {{ $isCurrent ? 'font-bold text-forest' : ($reached ? 'text-charcoal/70' : 'text-charcoal/30') }}">
            {{ \App\Enums\OrderStatus::from($step)->label() }}
          </span>
        </div>
        @if($i < count($timelineSteps) - 1)
          <div class="h-0.5 w-6 shrink-0 rounded {{ $reached && !$isCurrent ? 'bg-forest' : 'bg-sage/20' }}"></div>
        @endif
      @endforeach
      @if($isTerminal)
        <div class="flex items-center gap-1.5">
          <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-red-100 text-xs font-bold text-red-500">✕</div>
          <span class="text-[11px] font-bold text-red-500 whitespace-nowrap">{{ $order->status->label() }}</span>
        </div>
      @endif
    </div>
  </div>

  <div class="grid gap-6 lg:grid-cols-[2fr_1fr]">
    <div class="space-y-6">
      <section class="adm-section overflow-x-auto">
        <div class="border-b border-sage/20 px-5 py-3">
          <h2 class="adm-section-title">Items</h2>
        </div>
        <div class="adm-table-wrap">
          <table class="adm-table">
            <thead>
              <tr>
                <th>Product</th>
                <th>Variant</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Total</th>
              </tr>
            </thead>
            <tbody>
              @foreach($order->items as $item)
                <tr>
                  <td>
                    <div class="flex items-center gap-3">
                      <div class="h-10 w-10 shrink-0 overflow-hidden rounded-lg bg-forest/5 p-1">
                        @if($item->product?->primaryImage)
                          <img src="{{ asset('storage/'.$item->product->primaryImage->thumb_path) }}" alt="" class="h-full w-full object-contain">
                        @else
                          <div class="flex h-full items-center justify-center text-lg opacity-30">🌿</div>
                        @endif
                      </div>
                      <span class="font-medium text-charcoal">{{ $item->name_snapshot }}</span>
                    </div>
                  </td>
                  <td class="text-xs adm-text-muted">{{ $item->variant_snapshot ?? '—' }}</td>
                  <td class="text-center">{{ $item->quantity }}</td>
                  <td class="text-right adm-text-muted">₹{{ number_format($item->unit_price) }}</td>
                  <td class="text-right font-semibold text-charcoal">₹{{ number_format($item->line_total) }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </section>

      <section class="adm-section p-5">
        <h2 class="adm-section-title mb-4">Status History</h2>
        <div class="space-y-3">
          @forelse($order->statusHistories as $history)
            <div class="flex items-start gap-3 text-sm">
              <div class="mt-1.5 h-2 w-2 shrink-0 rounded-full {{ $loop->first ? 'bg-forest' : 'bg-sage/30' }}"></div>
              <div class="flex-1">
                <div class="flex items-center gap-2">
                  @php
                    try { $statusLabel = \App\Enums\OrderStatus::from($history->to_status)->label(); }
                    catch (\ValueError $e) { $statusLabel = ucfirst(str_replace('_', ' ', $history->to_status ?? '')); }
                  @endphp
                  <span class="font-semibold text-charcoal">{{ $statusLabel }}</span>
                  <span class="text-[10px] adm-text-muted">{{ $history->created_at?->format('d M Y, h:i A') }}</span>
                </div>
                @if($history->note)
                  <div class="text-xs adm-text-muted mt-0.5">{{ $history->note }}</div>
                @endif
                @if($history->changedBy)
                  <div class="text-[10px] text-charcoal/30 mt-0.5">by {{ $history->changedBy->name }}</div>
                @endif
              </div>
            </div>
          @empty
            <p class="text-sm adm-text-muted">No status history yet.</p>
          @endforelse
        </div>
      </section>
    </div>

    <div class="space-y-4 lg:sticky lg:top-24 lg:self-start">
      <div class="adm-section p-5 text-sm space-y-2">
        <h3 class="adm-section-title mb-2">Order Summary</h3>
        <div class="flex justify-between adm-text-muted"><span>Subtotal</span><span>₹{{ number_format($order->subtotal) }}</span></div>
        @if($order->product_discount > 0)<div class="flex justify-between text-forest"><span>Product Discount</span><span>-₹{{ number_format($order->product_discount) }}</span></div>@endif
        @if($order->coupon_discount > 0)<div class="flex justify-between text-forest"><span>Coupon Discount</span><span>-₹{{ number_format($order->coupon_discount) }}</span></div>@endif
        @if($order->delivery_charge > 0)<div class="flex justify-between adm-text-muted"><span>Delivery</span><span>₹{{ number_format($order->delivery_charge) }}</span></div>@endif
        <div class="border-t border-sage/20 pt-2 flex justify-between font-bold text-charcoal"><span>Grand Total</span><span>₹{{ number_format($order->grand_total) }}</span></div>
      </div>

      <div class="adm-section p-5 text-sm">
        <h3 class="adm-section-title mb-2">Payment</h3>
        <div class="space-y-1 adm-text-muted">
          <div class="flex justify-between"><span>Method</span><span class="font-medium text-charcoal">{{ strtoupper($order->payment_method ?? $order->payment->method ?? 'COD') }}</span></div>
          <div class="flex justify-between items-center">
            <span>Status</span>
            @php
              $payBadge = match($order->payment->status ?? null) {
                'collected' => 'bg-forest/10 text-forest',
                'pending' => 'bg-amber-100 text-amber-700',
                default => 'bg-sage/10 text-charcoal/50',
              };
            @endphp
            <span class="adm-badge {{ $payBadge }}">{{ ucfirst($order->payment->status ?? 'pending') }}</span>
          </div>
          @if($order->payment?->codCollection)
            <div class="flex justify-between"><span>Collected At</span><span class="adm-text-muted">{{ $order->payment->codCollection->collected_at?->format('d M Y, h:i A') ?? '—' }}</span></div>
          @endif
        </div>
      </div>

      <div class="adm-section p-5 text-sm">
        <h3 class="adm-section-title mb-2">Delivery Address</h3>
        <div class="adm-text-muted leading-relaxed text-xs">
          {{ $order->ship_name }}<br>
          @if($order->ship_house_no){{ $order->ship_house_no }}, @endif
          @if($order->ship_street){{ $order->ship_street }}, @endif
          @if($order->ship_area){{ $order->ship_area }}, @endif
          @if($order->ship_landmark)Landmark: {{ $order->ship_landmark }}, @endif
          {{ $order->ship_city }}, {{ $order->ship_state }} {{ $order->ship_pincode }}<br>
          📞 {{ $order->ship_phone }}
        </div>
      </div>

      @if($order->coupon)
        <div class="adm-section border-forest/20 bg-forest/5 p-4 text-sm">
          <div class="font-semibold text-forest">Coupon: {{ $order->coupon->code }}</div>
          <div class="text-xs text-forest/70 mt-0.5">Discount: -₹{{ number_format($order->coupon_discount) }}</div>
        </div>
      @endif

      @if(!empty($nextStatuses))
        <div class="adm-section p-5 text-sm">
          <h3 class="adm-section-title mb-3">Transition Status</h3>
          <form action="{{ route('admin.orders.transition', $order) }}" method="POST" class="space-y-3">
            @csrf
            @method('PATCH')
            <div>
              <label class="adm-label">New Status</label>
              <select name="to_status" required class="adm-input">
                @foreach($nextStatuses as $ns)
                  <option value="{{ $ns instanceof \App\Enums\OrderStatus ? $ns->value : $ns }}">{{ $ns instanceof \App\Enums\OrderStatus ? $ns->label() : \App\Enums\OrderStatus::from($ns)->label() }}</option>
                @endforeach
              </select>
            </div>
            <div>
              <label class="adm-label">Note</label>
              <textarea name="note" rows="3" placeholder="Optional note…" class="adm-input"></textarea>
            </div>
            <button type="submit" class="adm-btn-primary w-full">Update Status</button>
          </form>
        </div>
      @endif

      @if($order->status->value === 'packed')
        <div class="adm-section p-5 text-sm">
          <h3 class="adm-section-title mb-3">Assign Delivery Person</h3>
          <form action="{{ route('admin.delivery.assign', $order) }}" method="POST" class="space-y-3">
            @csrf
            <div>
              <label class="adm-label">Delivery Person</label>
              <select name="delivery_person_id" required class="adm-input">
                <option value="">Select…</option>
                @foreach($deliveryPersons as $dp)
                  <option value="{{ $dp->id }}">{{ $dp->user->name ?? '—' }}</option>
                @endforeach
              </select>
            </div>
            <button type="submit" class="adm-btn-primary w-full">Assign</button>
          </form>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
