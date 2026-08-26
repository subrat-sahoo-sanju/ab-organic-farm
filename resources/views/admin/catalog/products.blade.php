@extends('layouts.admin', ['title' => 'Products'])

@section('content')
<div class="space-y-4">

  <div class="flex flex-wrap items-center justify-between gap-4">
    <div>
      <h1 class="adm-page-title">Products</h1>
      <span class="adm-page-count">{{ $products->total() }} {{ Str::plural('product', $products->total()) }} total</span>
    </div>
    <div class="flex items-center gap-3">
      <a href="{{ route('admin.products.trashed') }}" class="adm-btn-outline">
        <x-lucide-trash-2 class="h-4 w-4" />
        Trashed Products
      </a>
      <a href="{{ route('admin.products.create') }}" class="adm-btn-primary">
        <x-lucide-plus class="h-4 w-4" />
        Add Product
      </a>
    </div>
  </div>

  @php
    $productsTable = $products->getCollection()->map(fn($p) => (object)[
      'id'       => $p->id,
      'name'     => $p->name,
      'sku'      => $p->sku ?? '—',
      'category' => $p->category->name ?? '—',
      'price'    => '₹' . number_format($p->sale_price ?? $p->regular_price),
      'stock'    => (string) ($p->defaultVariant?->inventory?->stock ?? '—'),
      'status'   => $p->status,
      'flags'    => collect([
        ($p->is_organic ?? false) ? 'Organic' : null,
        ($p->is_featured ?? false) ? 'Featured' : null,
        ($p->is_bestseller ?? false) ? 'Bestseller' : null,
      ])->filter()->implode(', ') ?: '—',
    ]);
    $csrfToken = csrf_token();
    $iconPencil = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/></svg>';
    $iconTrash = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>';
    $rowActions = [];
    foreach ($productsTable as $idx => $p) {
      $editUrl = e(route('admin.products.edit', $p->id));
      $destroyUrl = e(route('admin.products.destroy', $p->id));
      $rowActions[$idx] = <<<HTML
<div class="flex items-center justify-end gap-1.5">
  <a href="{$editUrl}" class="adm-action-link">
    {$iconPencil} Edit
  </a>
  <form action="{$destroyUrl}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?')">
    <input type="hidden" name="_token" value="{$csrfToken}">
    <input type="hidden" name="_method" value="DELETE">
    <button type="submit" class="adm-action-link adm-action-link-muted">
      {$iconTrash}
    </button>
  </form>
</div>
HTML;
    }
  @endphp

  <x-admin.datatable
    id="products"
    title="Products"
    subtitle="{{ $products->total() }} {{ Str::plural('product', $products->total()) }} total"
    :columns="[
      ['key'=>'name','label'=>'Product','sortable'=>true,'searchable'=>true],
      ['key'=>'sku','label'=>'SKU','searchable'=>true],
      ['key'=>'category','label'=>'Category','sortable'=>true],
      ['key'=>'price','label'=>'Price','sortable'=>true],
      ['key'=>'stock','label'=>'Stock','sortable'=>true,'hideOnMobile'=>true],
      ['key'=>'status','label'=>'Status','sortable'=>true],
      ['key'=>'flags','label'=>'Flags','hideOnMobile'=>true],
    ]"
    :rows="$productsTable"
    :perPage="15"
    :exportable="true"
    :rowActionsHtml="$rowActions"
    :filters="[
      ['key'=>'status','label'=>'Status','options'=>['active','draft','archived']],
      ['key'=>'category','label'=>'Category','options'=>$categories->pluck('name')->toArray()],
      ['key'=>'flags','label'=>'Flags','options'=>['Organic','Featured','Bestseller']],
    ]"
    :actionsColumn="true"
    emptyIcon="package"
    emptyTitle="No products found"
    emptyDescription="Start by adding your first product to see it here."
  />

</div>
@endsection
