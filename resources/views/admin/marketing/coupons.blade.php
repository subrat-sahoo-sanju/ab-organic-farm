@extends('layouts.admin', ['title' => 'Coupons'])

@section('content')
<div class="space-y-4" x-data="{ showModal: false }">

  <div class="flex flex-wrap items-center justify-between gap-4">
    <h2 class="adm-page-title">All Coupons <span class="adm-page-count">{{ $coupons->total() }}</span></h2>
    <button @click="showModal = true" class="adm-btn-primary">+ Add Coupon</button>
  </div>

  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr>
          <th>Code</th>
          <th>Discount</th>
          <th>Min Cart</th>
          <th>Valid From</th>
          <th>Valid Until</th>
          <th>Uses</th>
          <th>Status</th>
          <th class="text-right">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($coupons as $coupon)
          <tr>
            <td>
              <span class="adm-text-accent font-mono text-xs font-bold">{{ $coupon->code }}</span>
            </td>
            <td>
              @if($coupon->discount_type === 'percentage')
                <span class="font-semibold">{{ $coupon->discount_value }}% off</span>
              @else
                <span class="font-semibold">₹{{ number_format($coupon->discount_value) }} off</span>
              @endif
            </td>
            <td class="adm-text-secondary">{{ $coupon->min_cart_value ? '₹'.number_format($coupon->min_cart_value) : '—' }}</td>
            <td class="adm-text-muted text-xs">{{ $coupon->valid_from?->format('d M Y') ?? '—' }}</td>
            <td class="adm-text-muted text-xs">{{ $coupon->valid_until?->format('d M Y') ?? '—' }}</td>
            <td>
              <span class="adm-text-secondary">{{ $coupon->usages_count }}{{ $coupon->uses_total ? ' / '.$coupon->uses_total : '' }}</span>
            </td>
            <td>
              @if($coupon->is_active)
                <span class="adm-badge bg-forest/10 text-forest">Active</span>
              @else
                <span class="adm-badge bg-sage/10 text-charcoal/50">Inactive</span>
              @endif
            </td>
            <td class="text-right">
              <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Delete this coupon?')">
                @csrf @method('DELETE')
                <button type="submit" class="adm-btn-ghost text-red-500 text-xs">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="8" class="adm-empty">No coupons found. Create your first coupon to get started.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div>{{ $coupons->links('pagination::tailwind') }}</div>

  <div x-show="showModal" x-cloak class="adm-modal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    <div class="adm-modal-card" @click.away="showModal = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
      <div class="adm-modal-header">
        <h3 class="adm-modal-title">Create Coupon</h3>
        <button @click="showModal = false" class="adm-btn-ghost text-lg">&times;</button>
      </div>
      <form action="{{ route('admin.coupons.store') }}" method="POST" class="adm-modal-body space-y-4">
        @csrf
        <div>
          <label class="adm-label">Code *</label>
          <input type="text" name="code" required class="adm-input font-mono" placeholder="e.g. SAVE20">
        </div>
        <div>
          <label class="adm-label">Description</label>
          <input type="text" name="description" class="adm-input">
        </div>
        <div>
          <label class="adm-label">Discount Type *</label>
          <div class="flex gap-6">
            <label class="flex items-center gap-2 text-sm">
              <input type="radio" name="discount_type" value="percentage" checked class="accent-forest">
              Percentage
            </label>
            <label class="flex items-center gap-2 text-sm">
              <input type="radio" name="discount_type" value="fixed" class="accent-forest">
              Fixed Amount
            </label>
          </div>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <label class="adm-label">Discount Value *</label>
            <input type="number" name="discount_value" required min="0" step="0.01" class="adm-input">
          </div>
          <div>
            <label class="adm-label">Min Cart Value</label>
            <input type="number" name="min_cart_value" min="0" step="0.01" class="adm-input">
          </div>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <label class="adm-label">Max Discount Amount</label>
            <input type="number" name="max_discount_amount" min="0" step="0.01" class="adm-input">
          </div>
          <div>
            <label class="adm-label">Total Uses</label>
            <input type="number" name="uses_total" min="0" class="adm-input">
          </div>
        </div>
        <div>
          <label class="adm-label">Uses Per User</label>
          <input type="number" name="uses_per_user" min="0" class="adm-input">
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <label class="adm-label">Valid From *</label>
            <input type="date" name="valid_from" required class="adm-input">
          </div>
          <div>
            <label class="adm-label">Valid Until *</label>
            <input type="date" name="valid_until" required class="adm-input">
          </div>
        </div>
        <div>
          <label class="adm-label">Product IDs</label>
          <input type="text" name="product_ids" class="adm-input" placeholder="Comma-separated, e.g. 1,2,3">
        </div>
        <div>
          <label class="adm-label">Category IDs</label>
          <input type="text" name="category_ids" class="adm-input" placeholder="Comma-separated, e.g. 1,2,3">
        </div>
        <div class="flex gap-6">
          <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" checked class="accent-forest">
            Active
          </label>
        </div>
        <div class="adm-modal-footer">
          <button type="button" @click="showModal = false" class="adm-btn-outline">Cancel</button>
          <button type="submit" class="adm-btn-primary">Create Coupon</button>
        </div>
      </form>
    </div>
  </div>

</div>
@endsection
