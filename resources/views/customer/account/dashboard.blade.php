@extends('layouts.app', ['title' => 'My Account — AB Organic Farm'])

@section('content')
<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
  <div class="mb-8">
    <h1 class="font-display text-2xl font-bold text-charcoal">Welcome back, {{ $user->name }} 👋</h1>
    <p class="mt-1 text-sm text-charcoal/50">Manage your orders, addresses, and preferences</p>
  </div>

  <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
    <div class="rounded-2xl border border-sage/20 bg-white p-5 shadow-sm">
      <div class="text-sm text-charcoal/50">Total Orders</div>
      <div class="mt-1 text-2xl font-bold text-charcoal">{{ $orderStats['total'] }}</div>
    </div>
    <div class="rounded-2xl border border-sage/20 bg-white p-5 shadow-sm">
      <div class="text-sm text-charcoal/50">Active Orders</div>
      <div class="mt-1 text-2xl font-bold text-forest">{{ $orderStats['active'] }}</div>
    </div>
    <div class="rounded-2xl border border-sage/20 bg-white p-5 shadow-sm">
      <div class="text-sm text-charcoal/50">Total Spent</div>
      <div class="mt-1 text-2xl font-bold text-charcoal">₹{{ number_format($orderStats['spent']) }}</div>
    </div>
    <div class="rounded-2xl border border-sage/20 bg-white p-5 shadow-sm">
      <div class="text-sm text-charcoal/50">Wishlist</div>
      <div class="mt-1 text-2xl font-bold text-clay">{{ $user->wishlist_items_count ?? 0 }} items</div>
    </div>
  </div>

  <div class="grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2 space-y-6">
      @if($recentOrders->count())
        <section class="rounded-2xl border border-sage/20 bg-white p-6 shadow-sm">
          <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-charcoal">Recent Orders</h2>
            <a href="{{ route('account.orders') }}" class="text-sm font-semibold text-forest hover:underline">View All →</a>
          </div>
          <div class="divide-y divide-sage/20">
            @foreach($recentOrders as $order)
              <a href="{{ route('account.orders.show', $order) }}" class="flex items-center justify-between py-3 text-sm transition hover:bg-forest/5 -mx-2 px-2 rounded-lg">
                <div>
                  <div class="font-semibold text-charcoal">{{ $order->order_number }}</div>
                  <div class="text-xs text-charcoal/40">{{ $order->placed_at?->diffForHumans() }} • {{ $order->items_count }} items</div>
                </div>
                <div class="text-right">
                  <div class="font-semibold text-charcoal">₹{{ number_format($order->grand_total) }}</div>
                  <span class="inline-block rounded-full bg-forest/10 px-2 py-0.5 text-[10px] font-semibold text-forest uppercase">{{ $order->status->label() }}</span>
                </div>
              </a>
            @endforeach
          </div>
        </section>
      @endif

      @if($recentlyViewed->count())
        <section class="rounded-2xl border border-sage/20 bg-white p-6 shadow-sm">
          <h2 class="font-semibold text-charcoal mb-4">Recently Viewed</h2>
          <div class="grid grid-cols-4 gap-3">
            @foreach($recentlyViewed as $product)
              <a href="{{ route('shop.product', $product->slug) }}" class="group rounded-xl bg-forest/5 p-3 text-center transition hover:bg-forest/10">
                @if($product->primaryImage)
                  <img src="{{ asset('storage/'.$product->primaryImage->thumb_path) }}" alt="{{ $product->name }}" class="mx-auto h-12 w-12 object-contain">
                @else
                  <div class="text-2xl opacity-30">🌿</div>
                @endif
                <div class="mt-2 text-[11px] font-semibold text-charcoal line-clamp-2 group-hover:text-forest transition">{{ $product->name }}</div>
              </a>
            @endforeach
          </div>
        </section>
      @endif
    </div>

    <div class="space-y-4">
      <a href="{{ route('account.orders') }}" class="flex items-center gap-3 rounded-2xl border border-sage/20 bg-white p-4 shadow-sm transition hover:border-forest">
        <span class="text-xl">📦</span>
        <div><div class="font-semibold text-charcoal text-sm">My Orders</div><div class="text-xs text-charcoal/40">Track and manage orders</div></div>
      </a>
      <a href="{{ route('account.addresses') }}" class="flex items-center gap-3 rounded-2xl border border-sage/20 bg-white p-4 shadow-sm transition hover:border-forest">
        <span class="text-xl">📍</span>
        <div><div class="font-semibold text-charcoal text-sm">My Addresses</div><div class="text-xs text-charcoal/40">Manage delivery addresses</div></div>
      </a>
      <a href="{{ route('account.wishlist') }}" class="flex items-center gap-3 rounded-2xl border border-sage/20 bg-white p-4 shadow-sm transition hover:border-forest">
        <span class="text-xl">💚</span>
        <div><div class="font-semibold text-charcoal text-sm">Wishlist</div><div class="text-xs text-charcoal/40">Your saved products</div></div>
      </a>
      <a href="{{ route('account.notifications') }}" class="flex items-center gap-3 rounded-2xl border border-sage/20 bg-white p-4 shadow-sm transition hover:border-forest">
        <span class="text-xl">🔔</span>
        <div><div class="font-semibold text-charcoal text-sm">Notifications</div><div class="text-xs text-charcoal/40">View all updates</div></div>
      </a>

      <section class="rounded-2xl border border-sage/20 bg-white p-6 shadow-sm">
        <h3 class="font-semibold text-charcoal text-sm mb-3">Update Profile</h3>
        <form action="{{ route('account.profile') }}" method="POST">
          @csrf @method('PATCH')
          <div class="space-y-3">
            <input type="text" name="name" value="{{ $user->name }}" class="input" placeholder="Full name" required>
            <input type="email" name="email" value="{{ $user->email }}" class="input" placeholder="Email" required>
            <input type="text" name="phone" value="{{ $user->phone }}" class="input" placeholder="Phone" required pattern="\d{10}">
            <button type="submit" class="btn btn-primary btn-sm w-full">Save Changes</button>
          </div>
        </form>
      </section>
    </div>
  </div>
</div>
@endsection
