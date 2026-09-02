@extends('layouts.app', ['title' => 'My Orders'])

@section('content')
<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
  <h1 class="font-display text-2xl font-bold text-charcoal mb-8">My Orders</h1>

  @if($orders->count())
    <div class="space-y-4">
      @foreach($orders as $order)
        <a href="{{ route('account.orders.show', $order) }}" class="block rounded-2xl border border-sage/20 bg-white p-5 shadow-sm transition hover:border-forest hover:shadow-md">
          <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-4">
              <div class="text-sm">
                <div class="font-bold text-charcoal">{{ $order->order_number }}</div>
                <div class="text-xs text-charcoal/40 mt-0.5">{{ $order->placed_at?->format('d M Y, h:i A') }} • {{ $order->items_count }} item(s)</div>
              </div>
            </div>
            <div class="flex items-center gap-4">
              <span class="inline-block rounded-full px-2.5 py-1 text-[10px] font-bold uppercase
                @if($order->status->value === 'delivered') bg-forest/10 text-forest
                @elseif($order->status->value === 'cancelled') bg-red-100 text-red-600
                @elseif($order->status->value === 'out_for_delivery') bg-amber-100 text-amber-700
                @else bg-sage/10 text-charcoal/60
                @endif">
                {{ $order->status->label() }}
              </span>
              <div class="text-right text-sm">
                <div class="font-bold text-charcoal">₹{{ number_format($order->grand_total) }}</div>
                <div class="text-[10px] text-charcoal/40 uppercase">{{ $order->payment->method ?? 'COD' }}</div>
              </div>
            </div>
          </div>
          @if($order->items->count())
            <div class="mt-3 flex gap-2 overflow-x-auto">
              @foreach($order->items->take(4) as $item)
                <div class="flex items-center gap-2 rounded-lg bg-sage/5 px-2 py-1 text-xs text-charcoal/60">
                  @if($item->product?->primaryImage)
                    <img src="{{ asset('storage/'.$item->product->primaryImage->thumb_path) }}" alt="" class="h-6 w-6 rounded object-contain bg-forest/5">
                  @endif
                  <span class="line-clamp-1 max-w-[120px]">{{ $item->name_snapshot }}</span>
                  <span class="text-charcoal/30">×{{ $item->quantity }}</span>
                </div>
              @endforeach
              @if($order->items_count > 4)
                <span class="flex items-center text-[10px] text-charcoal/30">+{{ $order->items_count - 4 }} more</span>
              @endif
            </div>
          @endif
        </a>
      @endforeach
    </div>
    <div class="mt-8">{{ $orders->links('pagination::tailwind') }}</div>
  @else
    <div class="rounded-2xl border border-sage/20 bg-white py-16 text-center">
      <div class="text-5xl mb-4 opacity-40">📦</div>
      <p class="text-charcoal/50">No orders yet.</p>
      <a href="{{ route('shop.index') }}" class="mt-4 inline-block text-sm font-semibold text-forest hover:underline">Start Shopping →</a>
    </div>
  @endif
</div>
@endsection
