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
    $filters = request()->all();
    $activeStatus = $filters['status'] ?? null;
    $activeFlag = $filters['flag'] ?? null;
    $activeCategory = $filters['category'] ?? null;
    $activeQ = $filters['q'] ?? null;
    $hasActiveFilters = collect($filters)->reject(fn ($v) => $v === null || $v === '')->isNotEmpty();
  @endphp

  <div class="adm-table-wrap">
    <div class="p-4 sm:p-5">
      <div class="flex flex-col gap-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
          <div class="flex items-center gap-2.5">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-forest/10 dark:bg-forest/20">
              <x-lucide-filter class="h-4 w-4 text-forest dark:text-green-400" />
            </div>
            <div>
              <h2 class="adm-section-title !mb-0 !pb-0 !border-0">Filters</h2>
              <p class="text-xs adm-text-muted">Refine the product list below</p>
            </div>
          </div>
          @if($hasActiveFilters)
            <a href="{{ route('admin.products.index') }}" class="adm-btn-outline">
              <x-lucide-x class="h-4 w-4" />
              Clear filters
            </a>
          @endif
        </div>

        <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-col gap-3 lg:flex-row lg:items-end lg:gap-3">
          <div class="lg:w-56">
            <label class="adm-label">Search</label>
            <div class="relative">
              <x-lucide-search class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-forest/40 dark:text-green-400/50" />
              <input type="text" name="q" value="{{ $activeQ }}" placeholder="Search by name or SKU..."
                class="adm-input !pl-9" />
            </div>
          </div>
          <div class="lg:w-52">
            <label class="adm-label">Category</label>
            <select name="category" class="adm-input">
              <option value="">All Categories</option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected((string)$activeCategory === (string)$category->id)>{{ $category->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="lg:w-40">
            <label class="adm-label">Status</label>
            <select name="status" class="adm-input">
              <option value="">All Status</option>
              <option value="active" @selected($activeStatus === 'active')>Active</option>
              <option value="draft" @selected($activeStatus === 'draft')>Draft</option>
              <option value="out_of_stock" @selected($activeStatus === 'out_of_stock')>Out of Stock</option>
            </select>
          </div>
          <div class="lg:w-40">
            <label class="adm-label">Flag</label>
            <select name="flag" class="adm-input">
              <option value="">All Flags</option>
              <option value="featured" @selected($activeFlag === 'featured')>Featured</option>
              <option value="best_seller" @selected($activeFlag === 'best_seller')>Best Seller</option>
              <option value="new_arrival" @selected($activeFlag === 'new_arrival')>New Arrival</option>
            </select>
          </div>
          <div class="shrink-0">
            <button type="submit"
              class="inline-flex h-10 w-full items-center justify-center gap-2 rounded-xl bg-forest px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-forest-700 active:scale-95 lg:w-10 lg:px-0"
              title="Apply filters">
              <x-lucide-filter class="h-4 w-4" />
              <span class="lg:hidden">Apply Filters</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="adm-table-wrap">
    <div class="overflow-x-auto">
      <table class="adm-table">
        <thead>
          <tr>
            <th>Product</th>
            <th>SKU</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Status</th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($products as $product)
            @php
              $productPrice = number_format($product->sale_price ?? $product->regular_price);
              $productStock = $product->defaultVariant?->inventory?->stock;
              $productFlags = collect([
                $product->is_organic ? 'Organic' : null,
                $product->is_featured ? 'Featured' : null,
                $product->is_best_seller ? 'Best Seller' : null,
                $product->is_new_arrival ? 'New Arrival' : null,
              ])->filter()->implode(', ') ?: '—';
              $statusColor = match($product->status) {
                'active' => 'bg-forest/10 text-forest',
                'draft' => 'bg-sage/10 text-charcoal/50',
                'out_of_stock' => 'bg-red-100 text-red-600',
                default => 'bg-sage/10 text-charcoal/50',
              };
              $stockColor = ($productStock !== null && $productStock == 0)
                ? 'text-red-600'
                : 'text-charcoal/70 dark:text-gray-300';
            @endphp
            <tr>
              <td>
                <span class="font-semibold">{{ $product->name }}</span>
                @if($productFlags && $productFlags !== '—')
                  <span class="block text-[11px] adm-text-muted">{{ $productFlags }}</span>
                @endif
              </td>
              <td class="font-mono text-xs adm-text-muted">{{ $product->sku ?? '—' }}</td>
              <td>{{ $product->category->name ?? '—' }}</td>
              <td class="font-semibold">₹{{ $productPrice }}</td>
              <td class="{{ $stockColor }}">{{ $productStock ?? '—' }}</td>
              <td>
                <span class="inline-block rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase {{ $statusColor }}">{{ $product->status }}</span>
              </td>
              <td class="text-right">
                <div class="flex items-center justify-end gap-1.5">
                  <a href="{{ route('admin.products.edit', $product) }}" class="adm-action-link">
                    <x-lucide-pencil class="h-3 w-3" />
                    Edit
                  </a>
                  <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this product?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="adm-action-link adm-action-link-muted" title="Delete">
                      <x-lucide-trash-2 class="h-3 w-3" />
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-4 py-12">
                <div class="adm-empty">
                  <p>No products found</p>
                  <p class="adm-text-muted mt-1">Start by adding your first product to see it here.</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($products->hasPages())
    <div>
      {{ $products->withQueryString()->links('pagination::tailwind') }}
    </div>
  @endif

</div>
@endsection
