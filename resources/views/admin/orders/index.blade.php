@extends('layouts.admin', ['title' => 'Orders'])

@section('content')
<div class="space-y-5">

  <div class="flex flex-wrap items-center justify-between gap-4">
    <div class="flex items-center gap-3">
      <h1 class="adm-page-title">Orders</h1>
      <span class="adm-page-count">{{ $orders->total() }} {{ Str::plural('order', $orders->total()) }}</span>
    </div>
    <a href="{{ route('admin.orders.index', array_merge(request()->query(), ['export' => 'csv'])) }}"
       class="adm-btn-outline">
      <x-lucide-download class="h-4 w-4" />
      Export CSV
    </a>
  </div>

  <form method="GET" action="{{ route('admin.orders.index') }}" class="adm-section p-4">
    <div class="adm-grid-5 gap-3">

      <div class="relative lg:col-span-2">
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
          <x-lucide-search class="h-4 w-4 text-charcoal/30 dark:text-gray-500" />
        </div>
        <input type="text" name="q" value="{{ $filters['q'] ?? '' }}"
               placeholder="Search by order number or customer name…"
               class="adm-input !pl-10 !py-2.5">
      </div>

      <div>
        <select name="status" class="adm-input">
          <option value="">All Statuses</option>
          @foreach(\App\Enums\OrderStatus::cases() as $status)
            <option value="{{ $status->value }}" {{ ($filters['status'] ?? '') === $status->value ? 'selected' : '' }}>
              {{ $status->label() }}
            </option>
          @endforeach
        </select>
      </div>

      <div>
        <select name="range" class="adm-input">
          <option value="">All Time</option>
          <option value="today" {{ ($filters['range'] ?? '') === 'today' ? 'selected' : '' }}>Today</option>
          <option value="yesterday" {{ ($filters['range'] ?? '') === 'yesterday' ? 'selected' : '' }}>Yesterday</option>
          <option value="week" {{ ($filters['range'] ?? '') === 'week' ? 'selected' : '' }}>Last 7 Days</option>
          <option value="month" {{ ($filters['range'] ?? '') === 'month' ? 'selected' : '' }}>This Month</option>
        </select>
      </div>

      <div>
        <select name="cod" class="adm-input">
          <option value="">All COD Status</option>
          <option value="pending" {{ ($filters['cod'] ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
          <option value="collected" {{ ($filters['cod'] ?? '') === 'collected' ? 'selected' : '' }}>Collected</option>
        </select>
      </div>
    </div>

    <div class="mt-3 flex items-center gap-3">
      <button type="submit" class="adm-btn-primary">
        Apply Filters
      </button>
      @if(collect($filters)->filter()->isNotEmpty())
        <a href="{{ route('admin.orders.index') }}" class="adm-btn-ghost">
          <x-lucide-x class="h-3.5 w-3.5" />
          Clear Filters
        </a>
      @endif
    </div>
  </form>

  @php
    $ordersTable = $orders->getCollection()->map(fn($o) => (object)[
      'id'             => $o->id,
      'order_number'   => $o->order_number,
      'customer'       => $o->user->name ?? '—',
      'items'          => (string) $o->items_count,
      'total'          => '₹' . number_format($o->grand_total),
      'payment_status' => ucfirst($o->payment->status ?? 'pending'),
      'order_status'   => $o->status->label(),
      'date'           => $o->placed_at?->format('d M Y, h:i A') ?? '—',
    ]);

    $orderStatusOptions = collect(\App\Enums\OrderStatus::cases())->map(fn($s) => $s->label())->values()->toArray();

    $iconEye = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>';
    $rowActions = [];
    foreach ($ordersTable as $idx => $o) {
      $viewUrl = e(route('admin.orders.show', $o->id));
      $rowActions[$idx] = <<<HTML
<a href="{$viewUrl}" class="adm-action-link">
  {$iconEye} View
</a>
HTML;
    }
  @endphp

  <x-admin.datatable
    id="orders"
    title="Orders"
    subtitle="{{ $orders->total() }} {{ Str::plural('order', $orders->total()) }} total"
    :columns="[
      ['key'=>'order_number','label'=>'Order #','sortable'=>true,'searchable'=>true],
      ['key'=>'customer','label'=>'Customer','searchable'=>true],
      ['key'=>'items','label'=>'Items','sortable'=>true,'hideOnMobile'=>true],
      ['key'=>'total','label'=>'Total','sortable'=>true],
      ['key'=>'payment_status','label'=>'Payment','sortable'=>true,'hideOnMobile'=>true],
      ['key'=>'order_status','label'=>'Status','sortable'=>true],
      ['key'=>'date','label'=>'Date','sortable'=>true,'hideOnMobile'=>true],
    ]"
    :rows="$ordersTable"
    :perPage="15"
    :exportable="true"
    :rowActionsHtml="$rowActions"
    :filters="[
      ['key'=>'order_status','label'=>'Status','options'=>$orderStatusOptions],
      ['key'=>'payment_status','label'=>'Payment','options'=>['Pending','Collected']],
    ]"
    :actionsColumn="true"
    emptyIcon="shopping-bag"
    emptyTitle="No orders found"
    emptyDescription="Orders will appear here once customers start placing them."
  />

</div>
@endsection
