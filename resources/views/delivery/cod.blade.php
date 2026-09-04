@extends('layouts.delivery')

@section('title', 'COD Collections')

@section('content')
<div class="mx-auto max-w-[1440px] space-y-6">

  {{-- Page Header --}}
  <div>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">COD Collections</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Track cash-on-delivery payments for your assigned orders.</p>
  </div>

  {{-- Stats Cards --}}
  <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Pending Amount</p>
          <p class="mt-1 text-2xl font-bold text-orange-600 dark:text-orange-400">₹{{ number_format($stats['pending_amount'] ?? 0) }}</p>
        </div>
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-100 dark:bg-orange-900/30">
          <x-lucide-clock class="h-6 w-6 text-orange-600 dark:text-orange-400" />
        </div>
      </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Collected Amount</p>
          <p class="mt-1 text-2xl font-bold text-emerald-600 dark:text-emerald-400">₹{{ number_format($stats['collected_amount'] ?? 0) }}</p>
        </div>
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 dark:bg-emerald-900/30">
          <x-lucide-check-circle class="h-6 w-6 text-emerald-600 dark:text-emerald-400" />
        </div>
      </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Pending Count</p>
          <p class="mt-1 text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $stats['pending_count'] ?? 0 }}</p>
        </div>
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 dark:bg-amber-900/30">
          <x-lucide-wallet class="h-6 w-6 text-amber-600 dark:text-amber-400" />
        </div>
      </div>
    </div>
  </div>

  {{-- COD Table --}}
  <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden dark:border-gray-800 dark:bg-gray-900">
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-800/50">
          <tr>
            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Order</th>
            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Customer</th>
            <th class="px-5 py-3.5 text-right text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Order Total</th>
            <th class="px-5 py-3.5 text-right text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">COD Amount</th>
            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Status</th>
            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Collected At</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
          @forelse($payments as $payment)
            @php
              $statusBadge = match($payment->status) {
                'collected' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400',
                'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400',
                'failed' => 'bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-400',
                default => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
              };
            @endphp
            <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
              <td class="px-5 py-4">
                <a href="{{ route('delivery.show', $payment->order->latestAssignment ?? $payment->order) }}" class="font-bold text-gray-900 transition hover:text-emerald-600 dark:text-white dark:hover:text-emerald-400">
                  #{{ $payment->order->order_number }}
                </a>
              </td>
              <td class="px-5 py-4 text-gray-600 dark:text-gray-400">{{ $payment->order->user->name ?? '—' }}</td>
              <td class="px-5 py-4 text-right text-gray-500 dark:text-gray-400">₹{{ number_format($payment->order->grand_total) }}</td>
              <td class="px-5 py-4 text-right font-bold text-orange-600 dark:text-orange-400">₹{{ number_format($payment->amount) }}</td>
              <td class="px-5 py-4">
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide {{ $statusBadge }}">
                  {{ ucfirst($payment->status) }}
                </span>
              </td>
              <td class="px-5 py-4 text-xs text-gray-400 dark:text-gray-500">{{ $payment->collected_at?->format('d M Y, h:i A') ?? '—' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-5 py-16 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 dark:bg-gray-800">
                  <x-lucide-wallet class="h-7 w-7 text-gray-300 dark:text-gray-600" />
                </div>
                <p class="mt-4 text-sm font-medium text-gray-400 dark:text-gray-500">No COD payments found.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Pagination --}}
  <div>{{ $payments->links('pagination::tailwind') }}</div>
</div>
@endsection
