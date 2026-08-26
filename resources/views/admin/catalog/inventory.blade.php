@extends('layouts.admin', ['title' => 'Inventory'])

@section('content')
<div class="space-y-4" x-data="inventoryManager()" @open-adjust.window="openAdjust($event.detail)">

  <div class="adm-grid-5">
    <div class="adm-stat">
      <div class="adm-stat-label">Total Stock</div>
      <div class="adm-stat-value">{{ number_format($stats['total_stock']) }}</div>
    </div>
    <div class="adm-stat">
      <div class="adm-stat-label">Reserved</div>
      <div class="adm-stat-value">{{ number_format($stats['reserved']) }}</div>
    </div>
    <div class="adm-stat">
      <div class="adm-stat-label">Low Stock</div>
      <div class="adm-stat-value {{ $stats['low'] > 0 ? 'text-red-600 dark:text-red-400' : '' }}">{{ number_format($stats['low']) }}</div>
    </div>
    <div class="adm-stat">
      <div class="adm-stat-label">Out of Stock</div>
      <div class="adm-stat-value {{ $stats['out'] > 0 ? 'text-red-600 dark:text-red-400' : '' }}">{{ number_format($stats['out']) }}</div>
    </div>
  </div>

  <div class="flex flex-wrap items-center justify-between gap-4">
    <div class="flex gap-2">
      <a href="{{ route('admin.inventory.index') }}"
         class="adm-pill {{ !request('filter') ? 'adm-pill-active' : '' }}">
        All
      </a>
      <a href="{{ route('admin.inventory.index', ['filter' => 'low']) }}"
         class="adm-pill {{ request('filter') === 'low' ? 'adm-pill-active' : '' }}">
        Low Stock
      </a>
      <a href="{{ route('admin.inventory.index', ['filter' => 'out']) }}"
         class="adm-pill {{ request('filter') === 'out' ? 'adm-pill-active' : '' }}">
        Out of Stock
      </a>
    </div>
    <div class="flex gap-3">
      <form method="GET" class="flex gap-2">
        @if(request('filter'))<input type="hidden" name="filter" value="{{ request('filter') }}">@endif
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search SKU or product…"
               class="adm-input">
        <button type="submit" class="adm-btn-outline">Search</button>
      </form>
      <a href="{{ route('admin.inventory.transactions') }}"
         class="adm-action-link">
        Transactions
      </a>
    </div>
  </div>

  @php
    $inventoriesTable = $inventories->getCollection()->map(function($inv) {
      $available = $inv->stock - $inv->reserved;
      $status = $available <= 0 ? 'Out of Stock' : ($inv->stock <= $inv->low_stock_threshold ? 'Low Stock' : 'OK');
      return (object)[
        'id'        => $inv->id,
        'product'   => $inv->variant->product->name ?? '—',
        'variant'   => $inv->variant->name ?? '—',
        'sku'       => $inv->sku,
        'stock'     => (string) $inv->stock,
        'reserved'  => (string) $inv->reserved,
        'available' => (string) $available,
        'status'    => $status,
      ];
    });
    $iconPackagePlus = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16.5 9.4 7.55 4.24"/><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><path d="M3.27 6.96 12 12.01l8.73-5.05"/><path d="M12 22.08V12"/></svg>';
    $rowActions = [];
    foreach ($inventoriesTable as $idx => $inv) {
      $adjustDetail = e(json_encode([
        'id'      => $inv->id,
        'sku'     => $inv->sku,
        'stock'   => (int) $inv->stock,
        'product' => $inv->product,
        'variant' => $inv->variant,
      ]));
      $rowActions[$idx] = <<<HTML
<button data-adjust="{$adjustDetail}" onclick="window.dispatchEvent(new CustomEvent('open-adjust',{detail:JSON.parse(this.dataset.adjust)}))" class="adm-action-link">
  {$iconPackagePlus} Adjust
</button>
HTML;
    }
  @endphp

  <x-admin.datatable
    id="inventory"
    title="Inventory"
    subtitle="{{ $inventories->total() }} {{ Str::plural('item', $inventories->total()) }}"
    :columns="[
      ['key'=>'product','label'=>'Product','sortable'=>true,'searchable'=>true],
      ['key'=>'variant','label'=>'Variant','sortable'=>true],
      ['key'=>'sku','label'=>'SKU','searchable'=>true],
      ['key'=>'stock','label'=>'Stock','sortable'=>true],
      ['key'=>'reserved','label'=>'Reserved','sortable'=>true,'hideOnMobile'=>true],
      ['key'=>'available','label'=>'Available','sortable'=>true],
      ['key'=>'status','label'=>'Status','sortable'=>true],
    ]"
    :rows="$inventoriesTable"
    :perPage="15"
    :exportable="true"
    :rowActionsHtml="$rowActions"
    :actionsColumn="true"
    emptyIcon="box"
    emptyTitle="No inventory records found"
    emptyDescription="Inventory data will appear here once products are added."
  />

  <div x-show="showModal" x-cloak class="adm-modal"
       x-transition:enter="transition ease-out duration-200"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in duration-150"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0">
    <div class="adm-modal-card" @click.away="showModal = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">
      <div class="adm-modal-header">
        <h3 class="adm-modal-title">Adjust Stock</h3>
        <button @click="showModal = false" class="text-charcoal/40 hover:text-charcoal dark:text-gray-400 dark:hover:text-white text-lg">&times;</button>
      </div>
      <form :action="'{{ url('admin/inventory') }}/' + item.id + '/adjust'" method="POST" class="adm-modal-body space-y-4">
        @csrf
        <div class="adm-section p-3 text-sm">
          <span class="adm-text-muted">Product:</span> <span class="font-semibold text-charcoal dark:text-white" x-text="item.product"></span><br>
          <span class="adm-text-muted">Variant:</span> <span class="font-semibold text-charcoal dark:text-white" x-text="item.variant"></span><br>
          <span class="adm-text-muted">SKU:</span> <span class="font-mono text-xs adm-text-muted" x-text="item.sku"></span> &middot;
          <span class="adm-text-muted">Current Stock:</span> <span class="font-semibold text-charcoal dark:text-white" x-text="item.stock"></span>
        </div>
        <div>
          <label class="adm-label">Quantity *</label>
          <input type="number" name="quantity" required placeholder="Positive to add, negative to subtract"
                 class="adm-input">
          <p class="mt-1 text-[10px] adm-text-muted">Use positive values to add stock, negative to subtract.</p>
        </div>
        <div>
          <label class="adm-label">Reason *</label>
          <input type="text" name="reason" required placeholder="e.g. Stock received, Damaged goods"
                 class="adm-input">
        </div>
        <div>
          <label class="adm-label">Type *</label>
          <select name="type" required class="adm-input">
            <option value="purchase">Purchase</option>
            <option value="adjustment">Adjustment</option>
            <option value="damage">Damage</option>
          </select>
        </div>
        <div class="flex justify-end gap-3 pt-2 border-t border-sage/20 dark:border-gray-700">
          <button type="button" @click="showModal = false"
                  class="adm-btn-outline">
            Cancel
          </button>
          <button type="submit" class="adm-btn-primary">Confirm Adjustment</button>
        </div>
      </form>
    </div>
  </div>

</div>

<script>
function inventoryManager() {
  return {
    showModal: false,
    item: { id: '', sku: '', stock: '', product: '', variant: '' },
    openAdjust(data) {
      this.item = data;
      this.showModal = true;
    },
  }
}
</script>
@endsection
