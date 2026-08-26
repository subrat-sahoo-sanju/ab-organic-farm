@extends('layouts.admin', ['title' => 'Reviews'])

@section('content')
<div class="space-y-4">

  <div class="flex flex-wrap items-center justify-between gap-4">
    <h2 class="adm-page-title">All Reviews <span class="adm-page-count">{{ $reviews->total() }}</span></h2>
  </div>

  @php $active = request('status'); @endphp
  <div class="flex flex-wrap gap-2">
    <a href="{{ route('admin.reviews.index') }}" class="adm-pill {{ is_null($active) ? 'adm-pill-active' : '' }}">All</a>
    <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="adm-pill {{ $active === 'pending' ? 'adm-pill-active' : '' }}">Pending</a>
    <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}" class="adm-pill {{ $active === 'approved' ? 'adm-pill-active' : '' }}">Approved</a>
    <a href="{{ route('admin.reviews.index', ['status' => 'rejected']) }}" class="adm-pill {{ $active === 'rejected' ? 'adm-pill-active' : '' }}">Rejected</a>
  </div>

  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr>
          <th>Product</th>
          <th>Order #</th>
          <th>Customer</th>
          <th>Rating</th>
          <th>Review</th>
          <th>Status</th>
          <th>Date</th>
          <th class="text-right">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($reviews as $review)
          @php
            $statusColor = match($review->status) {
              'pending' => 'bg-amber-100 text-amber-700',
              'approved' => 'bg-forest/10 text-forest',
              'rejected' => 'bg-red-100 text-red-600',
              default => 'bg-sage/10 text-charcoal/50',
            };
          @endphp
          <tr>
            <td>
              <span class="font-semibold">{{ $review->product->name ?? '—' }}</span>
            </td>
            <td>
              <a href="{{ route('admin.orders.show', $review->order) }}" class="font-semibold text-charcoal hover:text-forest transition">{{ $review->order->order_number ?? '—' }}</a>
            </td>
            <td>{{ $review->user->name ?? '—' }}</td>
            <td>
              <div class="flex items-center gap-0.5 text-amber-500">
                @for($i = 1; $i <= 5; $i++)
                  <span class="text-sm {{ $i <= $review->rating ? '' : 'opacity-20' }}">&#9733;</span>
                @endfor
              </div>
            </td>
            <td>
              <p class="max-w-xs truncate adm-text-muted">{{ $review->comment }}</p>
            </td>
            <td>
              <span class="inline-block rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase {{ $statusColor }}">{{ $review->status }}</span>
            </td>
            <td class="text-xs adm-text-muted">{{ $review->created_at?->format('d M Y') ?? '—' }}</td>
            <td class="text-right">
              <div class="flex items-center justify-end gap-2">
                @if($review->status !== 'approved')
                  <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                    @csrf
                    <button type="submit" class="adm-action-link">Approve</button>
                  </form>
                @endif
                @if($review->status !== 'rejected')
                  <form action="{{ route('admin.reviews.reject', $review) }}" method="POST">
                    @csrf
                    <button type="submit" class="adm-action-link-muted">Reject</button>
                  </form>
                @endif
                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Delete this review?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="adm-btn-danger">Delete</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="8" class="px-4 py-12"><div class="adm-empty"><p>No reviews found.</p></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div>{{ $reviews->withQueryString()->links('pagination::tailwind') }}</div>
</div>
@endsection
