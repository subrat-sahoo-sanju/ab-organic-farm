@extends('layouts.admin')

@section('title', 'Customer: '.$customer->name)

@section('content')
<div class="space-y-6">

    {{-- Back Link --}}
    <a href="{{ route('admin.customers.index') }}" class="adm-back">
        <x-lucide-arrow-left class="h-3.5 w-3.5" />
        Back to Customers
    </a>

    {{-- Customer Info Card --}}
    <div class="adm-section overflow-hidden">
        <div class="h-20 bg-gradient-to-r from-forest to-forest/70 dark:from-green-600 dark:to-green-700"></div>
        <div class="px-6 pb-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:gap-6">
                @php
                    $colors = ['bg-forest', 'bg-blue-500', 'bg-purple-500', 'bg-amber-500', 'bg-rose-500', 'bg-teal-500'];
                    $colorIndex = crc32($customer->email) % count($colors);
                    $initials = strtoupper(substr($customer->name, 0, 2));
                @endphp
                <div class="-mt-8 flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl {{ $colors[$colorIndex] }} text-xl font-bold text-white shadow-lg ring-4 ring-white dark:ring-gray-800">
                    {{ $initials }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="adm-page-title text-xl">{{ $customer->name }}</h1>
                        <x-admin.status-badge :status="$customer->is_active ? 'active' : 'inactive'" />
                    </div>
                    <div class="mt-1 flex flex-wrap items-center gap-4 text-sm adm-text-muted">
                        <span class="inline-flex items-center gap-1.5">
                            <x-lucide-mail class="h-3.5 w-3.5" />
                            {{ $customer->email }}
                        </span>
                        @if($customer->phone)
                            <span class="inline-flex items-center gap-1.5">
                                <x-lucide-phone class="h-3.5 w-3.5" />
                                {{ $customer->phone }}
                            </span>
                        @endif
                        <span class="inline-flex items-center gap-1.5">
                            <x-lucide-calendar class="h-3.5 w-3.5" />
                            Joined {{ $customer->created_at->format('M d, Y') }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if($customer->is_active)
                        <form method="POST" action="{{ route('admin.customers.block', $customer) }}">
                            @csrf
                            <button type="submit" onclick="return confirm('Block this customer?')"
                                class="adm-btn-danger">
                                <x-lucide-ban class="h-3.5 w-3.5" />
                                Block Customer
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.customers.unblock', $customer) }}">
                            @csrf
                            <button type="submit"
                                class="adm-action-link">
                                <x-lucide-check-circle class="h-3.5 w-3.5" />
                                Unblock Customer
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="adm-grid-2 lg:grid-cols-4">
        <div class="adm-stat">
            <div class="adm-stat-label">Total Orders</div>
            <div class="adm-stat-value">{{ $orderStats['total'] }}</div>
        </div>
        <div class="adm-stat">
            <div class="adm-stat-label">Delivered</div>
            <div class="adm-stat-value text-green-600">{{ $orderStats['delivered'] }}</div>
        </div>
        <div class="adm-stat">
            <div class="adm-stat-label">Cancelled</div>
            <div class="adm-stat-value text-red-600">{{ $orderStats['cancelled'] }}</div>
        </div>
        <div class="adm-stat">
            <div class="adm-stat-label">Total Spent</div>
            <div class="adm-stat-value">₹{{ number_format($orderStats['total_spent'], 2) }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Addresses --}}
        <div class="adm-section p-5 lg:col-span-1">
            <div class="mb-4 flex items-center gap-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 dark:bg-amber-500/10">
                    <x-lucide-map-pin class="h-4 w-4 text-amber-600 dark:text-amber-400" />
                </div>
                <h2 class="adm-section-title">Addresses</h2>
                @if($customer->addresses->count())
                    <span class="adm-page-count ml-auto">{{ $customer->addresses->count() }}</span>
                @endif
            </div>

            @if($customer->addresses->count())
                <div class="space-y-3">
                    @foreach($customer->addresses as $address)
                        <div class="relative rounded-lg border border-sage/20 p-3.5 transition hover:border-forest/30 dark:border-gray-600 dark:hover:border-green-700">
                            @if($address->is_default)
                                <span class="absolute right-2 top-2 rounded-full bg-forest/10 px-2 py-0.5 text-[10px] font-bold text-forest dark:bg-forest/20 dark:text-green-400">Default</span>
                            @endif
                            @if($address->label)
                                <span class="mb-1.5 inline-block rounded-md bg-charcoal/[0.04] px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-charcoal/50 dark:bg-gray-700 dark:text-gray-400">{{ $address->label }}</span>
                            @endif
                            <p class="text-sm font-medium text-charcoal dark:text-white">{{ $address->full_address ?? $address->address_line_1 }}</p>
                            <p class="mt-0.5 text-xs adm-text-muted">{{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}</p>
                            @if($address->phone)
                                <p class="mt-1 text-[11px] text-charcoal/40 dark:text-gray-500">
                                    <x-lucide-phone class="mr-1 inline h-3 w-3" />{{ $address->phone }}
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <x-admin.empty-state icon="map-pin" title="No addresses" description="Customer hasn't added any addresses yet." />
            @endif
        </div>

        {{-- Recent Orders --}}
        <div class="adm-section p-5 lg:col-span-2">
            <div class="mb-4 flex items-center gap-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-500/10">
                    <x-lucide-shopping-cart class="h-4 w-4 text-blue-600 dark:text-blue-400" />
                </div>
                <h2 class="adm-section-title">Recent Orders</h2>
                @if($customer->orders->count())
                    <span class="adm-page-count ml-auto">{{ $customer->orders->count() }}</span>
                @endif
            </div>

            @if($customer->orders->count())
                <div class="adm-table-wrap">
                    <table class="adm-table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer->orders as $order)
                                <tr>
                                    <td class="font-semibold text-forest dark:text-green-400">#{{ $order->order_number }}</td>
                                    <td class="adm-text-muted">{{ $order->created_at->format('M d, Y') }}</td>
                                    <td>{{ $order->items_count ?? $order->items->count() }}</td>
                                    <td class="font-semibold text-charcoal dark:text-white">₹{{ number_format($order->total, 2) }}</td>
                                    <td>
                                        <x-admin.status-badge :status="$order->status" />
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                           class="adm-action-link">
                                            <x-lucide-eye class="h-3 w-3" />
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <x-admin.empty-state icon="shopping-cart" title="No orders yet" description="This customer hasn't placed any orders." />
            @endif
        </div>
    </div>
</div>
@endsection
