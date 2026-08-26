@extends('layouts.admin', ['title' => 'Inventory Transactions'])

@section('content')
<div class="space-y-4">

  <div class="flex flex-wrap items-center justify-between gap-4">
    <div>
      <h1 class="adm-page-title">Inventory Transactions</h1>
      <p class="adm-text-secondary">{{ $transactions->total() }} {{ Str::plural('transaction', $transactions->total()) }} total</p>
    </div>
    <a href="{{ route('admin.inventory.index') }}" class="adm-back">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
      Back to Inventory
    </a>
  </div>

  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Product</th>
          <th>Variant</th>
          <th>Type</th>
          <th>Quantity</th>
          <th>Reason</th>
          <th>By</th>
        </tr>
      </thead>
      <tbody>
        @forelse($transactions as $txn)
          <tr>
            <td>{{ $txn->created_at->format('d M Y, h:i A') }}</td>
            <td class="font-semibold">{{ $txn->inventory->variant->product->name ?? '—' }}</td>
            <td>{{ $txn->inventory->variant->name ?? '—' }}</td>
            <td>
              @php
                $typeColors = [
                  'purchase' => 'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400',
                  'adjustment' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400',
                  'damage' => 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400',
                  'sale' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
                  'return' => 'bg-purple-100 text-purple-700 dark:bg-purple-500/10 dark:text-purple-400',
                ];
                $type = $txn->type->value ?? $txn->type;
                $color = $typeColors[$type] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-600 dark:text-gray-300';
              @endphp
              <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $color }}">{{ ucfirst($type) }}</span>
            </td>
            <td class="font-semibold {{ $txn->quantity > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
              {{ $txn->quantity > 0 ? '+' : '' }}{{ $txn->quantity }}
            </td>
            <td class="adm-text-muted">{{ $txn->reason ?? '—' }}</td>
            <td>{{ $txn->user->name ?? '—' }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="px-4 py-12">
              <div class="adm-empty">
                <p>No transactions found</p>
                <p class="adm-text-muted mt-1">Inventory transactions will appear here once stock adjustments are made.</p>
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($transactions->hasPages())
    <div class="adm-divider">
      {{ $transactions->withQueryString()->links() }}
    </div>
  @endif

</div>
@endsection
