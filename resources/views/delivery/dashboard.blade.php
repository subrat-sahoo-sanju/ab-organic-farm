@extends('layouts.delivery')

@section('title', 'Delivery Dashboard')

@section('content')
<div class="mx-auto max-w-7xl space-y-6">

  {{-- Stats Grid --}}
  <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Pending Pickups</p>
          <p class="mt-1 text-3xl font-bold text-amber-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 dark:bg-amber-900/30">
          <x-lucide-clock class="h-6 w-6 text-amber-600 dark:text-amber-400" />
        </div>
      </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Out for Delivery</p>
          <p class="mt-1 text-3xl font-bold text-blue-600">{{ $stats['out'] }}</p>
        </div>
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 dark:bg-blue-900/30">
          <x-lucide-truck class="h-6 w-6 text-blue-600 dark:text-blue-400" />
        </div>
      </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Delivered Today</p>
          <p class="mt-1 text-3xl font-bold text-emerald-600">{{ $stats['delivered_today'] }}</p>
        </div>
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 dark:bg-emerald-900/30">
          <x-lucide-check-circle class="h-6 w-6 text-emerald-600 dark:text-emerald-400" />
        </div>
      </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">COD to Collect</p>
          <p class="mt-1 text-3xl font-bold text-orange-600">{{ $stats['cod_pending'] }}</p>
        </div>
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-100 dark:bg-orange-900/30">
          <x-lucide-wallet class="h-6 w-6 text-orange-600 dark:text-orange-400" />
        </div>
      </div>
    </div>
  </div>

  {{-- Active Assignments --}}
  <div>
    <h2 class="mb-4 text-lg font-bold text-gray-900 dark:text-white">Active Assignments</h2>

    @if($assignments->count())
      <div class="space-y-4">
        @foreach($assignments as $assignment)
          <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
              <div class="flex-1 space-y-3">
                {{-- Order Header --}}
                <div class="flex flex-wrap items-center gap-2">
                  <a href="{{ route('delivery.show', $assignment) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-gray-900 px-3 py-1.5 text-xs font-bold text-white dark:bg-white dark:text-gray-900">
                    <x-lucide-hash class="h-3 w-3" />
                    {{ $assignment->order->order_number }}
                  </a>

                  @php
                    $statusColors = [
                      'assigned' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400',
                      'picked_up' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400',
                      'out_for_delivery' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400',
                      'delivered' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400',
                      'failed' => 'bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-400',
                    ];
                  @endphp
                  <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wide {{ $statusColors[$assignment->status] ?? 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' }}">
                    {{ ucfirst(str_replace('_', ' ', $assignment->status)) }}
                  </span>

                  @if($assignment->order->payment_method === 'cod')
                    <span class="inline-flex items-center rounded-full bg-orange-100 px-2 py-0.5 text-[10px] font-bold uppercase text-orange-700 dark:bg-orange-900/40 dark:text-orange-400">COD</span>
                  @endif
                </div>

                {{-- Customer Info --}}
                <div class="space-y-1.5">
                  <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <x-lucide-user class="h-3.5 w-3.5 text-gray-400" />
                    {{ $assignment->order->customer->name ?? 'Customer' }}
                  </div>

                  <div class="flex items-start gap-2 text-sm text-gray-500 dark:text-gray-400">
                    <x-lucide-map-pin class="mt-0.5 h-3.5 w-3.5 shrink-0 text-gray-400" />
                    @if($assignment->order->address)
                      {{ $assignment->order->address->address_line_1 ?? '' }}
                      {{ $assignment->order->address->city ?? '' }}, {{ $assignment->order->address->pincode ?? '' }}
                    @else
                      Address not available
                    @endif
                  </div>
                </div>

                {{-- Items --}}
                <div class="flex flex-wrap gap-1.5">
                  @foreach($assignment->order->items->take(3) as $item)
                    <span class="inline-flex items-center rounded-lg bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                      {{ $item->product->name ?? 'Product' }} × {{ $item->quantity }}
                    </span>
                  @endforeach
                  @if($assignment->order->items->count() > 3)
                    <span class="inline-flex items-center rounded-lg bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-500">
                      +{{ $assignment->order->items->count() - 3 }} more
                    </span>
                  @endif
                </div>

                {{-- Total --}}
                <p class="text-lg font-bold text-gray-900 dark:text-white">₹{{ number_format($assignment->order->total, 2) }}</p>
              </div>

              {{-- Actions --}}
              <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                @if($assignment->status === 'assigned')
                  <form method="POST" action="{{ route('delivery.pickup', $assignment) }}">
                    @csrf
                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 hover:shadow-md sm:w-auto">
                      <x-lucide-truck class="h-4 w-4" />
                      Picked Up
                    </button>
                  </form>
                @endif

                @if($assignment->status === 'picked_up')
                  <form method="POST" action="{{ route('delivery.delivered', $assignment) }}">
                    @csrf
                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 hover:shadow-md sm:w-auto">
                      <x-lucide-check-circle class="h-4 w-4" />
                      Mark Delivered
                    </button>
                  </form>
                @endif

                @if(in_array($assignment->status, ['assigned', 'picked_up']))
                  <form method="POST" action="{{ route('delivery.failed', $assignment) }}">
                    @csrf
                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-red-200 bg-white px-5 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-50 sm:w-auto dark:border-red-900 dark:bg-transparent dark:text-red-400 dark:hover:bg-red-950">
                      <x-lucide-x-circle class="h-4 w-4" />
                      Failed
                    </button>
                  </form>
                @endif
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-16 text-center dark:border-gray-700 dark:bg-gray-900">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100 dark:bg-gray-800">
          <x-lucide-truck class="h-8 w-8 text-gray-300 dark:text-gray-600" />
        </div>
        <p class="mt-4 text-sm font-medium text-gray-400 dark:text-gray-500">No active assignments right now.</p>
        <p class="mt-1 text-xs text-gray-300 dark:text-gray-600">New orders will appear here when assigned.</p>
      </div>
    @endif
  </div>
</div>
@endsection
