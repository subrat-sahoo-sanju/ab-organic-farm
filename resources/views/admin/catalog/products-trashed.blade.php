@extends('layouts.admin', ['title' => 'Trashed Products'])

@section('content')
<div class="space-y-4">

  <div class="flex flex-wrap items-center justify-between gap-4">
    <div>
      <h1 class="adm-page-title">Trashed Products <span class="adm-page-count">{{ $products->total() }}</span></h1>
      <p class="adm-text-secondary">{{ $products->total() }} {{ Str::plural('product', $products->total()) }} in trash</p>
    </div>
    <a href="{{ route('admin.products.index') }}" class="adm-back">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
      Back to Products
    </a>
  </div>

  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr>
          <th>Product</th>
          <th>SKU</th>
          <th>Category</th>
          <th>Price</th>
          <th>Trashed On</th>
          <th class="text-right">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($products as $product)
          <tr>
            <td class="font-semibold">{{ $product->name }}</td>
            <td class="font-mono text-xs adm-text-muted">{{ $product->sku ?? '—' }}</td>
            <td>{{ $product->category->name ?? '—' }}</td>
            <td class="font-semibold">₹{{ number_format($product->sale_price ?? $product->regular_price) }}</td>
            <td class="adm-text-muted">{{ $product->deleted_at?->format('d M Y, h:i A') ?? '—' }}</td>
            <td class="text-right">
              <div class="flex items-center justify-end gap-1.5">
                <form action="{{ route('admin.products.restore', $product) }}" method="POST">
                  @csrf
                  <button type="submit" class="adm-btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                    Restore
                  </button>
                </form>
                <form action="{{ route('admin.products.force-delete', $product) }}" method="POST"
                      onsubmit="return confirm('Permanently delete this product? This cannot be undone.')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="adm-btn-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                    Delete
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="px-4 py-12">
              <div class="adm-empty">
                <p>No trashed products</p>
                <p class="adm-text-muted mt-1">Products you delete will appear here for restoration or permanent deletion.</p>
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($products->hasPages())
    <div class="adm-divider">
      {{ $products->withQueryString()->links() }}
    </div>
  @endif

</div>
@endsection
