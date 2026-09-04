@extends('layouts.delivery')

@section('title', 'My Deliveries')

@section('content')
<div class="mx-auto max-w-[1440px] space-y-6">

  {{-- Page Header --}}
  <div>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Deliveries</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Track and manage all your delivery assignments.</p>
  </div>

  {{-- Filter Tabs --}}
  @php $active = request('status'); @endphp
  <div class="flex flex-wrap gap-2">
    <a href="{{ route('delivery.deliveries') }}"
       class="inline-flex items-center gap-1.5 rounded-lg px-3.5 py-2 text-xs font-semibold transition
              {{ is_null($active) ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900' : 'border border-gray-200 bg-white text-gray-500 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800' }}">
      All
    </a>
    <a href="{{ route('delivery.deliveries', ['status' => 'assigned']) }}"
       class="inline-flex items-center gap-1.5 rounded-lg px-3.5 py-2 text-xs font-semibold transition
              {{ $active === 'assigned' ? 'bg-amber-500 text-white' : 'border border-gray-200 bg-white text-gray-500 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800' }}">
      <x-lucide-clock class="h-3 w-3" />
      Assigned
    </a>
    <a href="{{ route('delivery.deliveries', ['status' => 'out_for_delivery']) }}"
       class="inline-flex items-center gap-1.5 rounded-lg px-3.5 py-2 text-xs font-semibold transition
              {{ $active === 'out_for_delivery' ? 'bg-blue-500 text-white' : 'border border-gray-200 bg-white text-gray-500 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800' }}">
      <x-lucide-truck class="h-3 w-3" />
      Out for Delivery
    </a>
    <a href="{{ route('delivery.deliveries', ['status' => 'delivered']) }}"
       class="inline-flex items-center gap-1.5 rounded-lg px-3.5 py-2 text-xs font-semibold transition
              {{ $active === 'delivered' ? 'bg-emerald-500 text-white' : 'border border-gray-200 bg-white text-gray-500 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800' }}">
      <x-lucide-check-circle class="h-3 w-3" />
      Delivered
    </a>
    <a href="{{ route('delivery.deliveries', ['status' => 'failed']) }}"
       class="inline-flex items-center gap-1.5 rounded-lg px-3.5 py-2 text-xs font-semibold transition
              {{ $active === 'failed' ? 'bg-red-500 text-white' : 'border border-gray-200 bg-white text-gray-500 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800' }}">
      <x-lucide-x-circle class="h-3 w-3" />
      Failed
    </a>
  </div>

  {{-- Deliveries Table --}}
  <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden dark:border-gray-800 dark:bg-gray-900">
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-800/50">
          <tr>
            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Order</th>
            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Customer</th>
            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Status</th>
            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Assigned</th>
            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Delivered</th>
            <th class="px-5 py-3.5 text-right text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Total</th>
            <th class="px-5 py-3.5 text-right text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Action</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
          @forelse($assignments as $assignment)
            @php
              $badgeColor = match($assignment->status->value) {
                'assigned' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400',
                'picked_up' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400',
                'out_for_delivery' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400',
                'delivered' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400',
                'failed' => 'bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-400',
                default => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
              };
            @endphp
            <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
              <td class="px-5 py-4">
                <span class="font-bold text-gray-900 dark:text-white">#{{ $assignment->order->order_number }}</span>
              </td>
              <td class="px-5 py-4 text-gray-600 dark:text-gray-400">{{ $assignment->order->user->name ?? '—' }}</td>
              <td class="px-5 py-4">
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide {{ $badgeColor }}">
                  {{ $assignment->status->label() }}
                </span>
              </td>
              <td class="px-5 py-4 text-xs text-gray-400 dark:text-gray-500">{{ $assignment->assigned_at?->format('d M Y, h:i A') ?? '—' }}</td>
              <td class="px-5 py-4 text-xs text-gray-400 dark:text-gray-500">{{ $assignment->delivered_at?->format('d M Y, h:i A') ?? '—' }}</td>
              <td class="px-5 py-4 text-right font-bold text-gray-900 dark:text-white">₹{{ number_format($assignment->order->grand_total) }}</td>
              <td class="px-5 py-4 text-right">
                <a href="{{ route('delivery.show', $assignment) }}" class="inline-flex items-center gap-1 rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 dark:hover:bg-emerald-900/50">
                  <x-lucide-eye class="h-3 w-3" />
                  View
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-5 py-16 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 dark:bg-gray-800">
                  <x-lucide-truck class="h-7 w-7 text-gray-300 dark:text-gray-600" />
                </div>
                <p class="mt-4 text-sm font-medium text-gray-400 dark:text-gray-500">No deliveries found.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Pagination --}}
  <div>{{ $assignments->links('pagination::tailwind') }}</div>
</div>
@endsection
