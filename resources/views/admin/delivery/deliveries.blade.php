@extends('layouts.admin', ['title' => 'Delivery Management'])

@section('content')
<div class="space-y-4" x-data="{ reassignId: null, assignOrder: null }">

  <div class="flex items-center justify-between">
    <div class="flex items-center gap-2">
      <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-[11px] font-bold text-emerald-700 dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
        <span class="relative flex h-2 w-2">
          <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-500 opacity-75"></span>
          <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
        </span>
        LIVE · auto-refreshes
      </span>
    </div>
  </div>

  <div class="adm-grid-5" x-data="livePanel({ url: '/admin/delivery/live', interval: 6000 })">
    <div class="adm-stat" :class="pulse('pending_orders')">
      <div class="adm-stat-label">Pending Orders</div>
      <div class="adm-stat-value" x-text="stats['pending_orders'] === undefined ? '{{ $stats['pending_orders'] }}' : stats['pending_orders']">{{ $stats['pending_orders'] }}</div>
    </div>
    <div class="adm-stat" :class="pulse('assigned')">
      <div class="adm-stat-label">Assigned</div>
      <div class="adm-stat-value" x-text="stats['assigned'] === undefined ? '{{ $stats['assigned'] }}' : stats['assigned']">{{ $stats['assigned'] }}</div>
    </div>
    <div class="adm-stat" :class="pulse('out')">
      <div class="adm-stat-label">Out for Delivery</div>
      <div class="adm-stat-value" x-text="stats['out'] === undefined ? '{{ $stats['out'] }}' : stats['out']">{{ $stats['out'] }}</div>
    </div>
    <div class="adm-stat" :class="pulse('delivered_today')">
      <div class="adm-stat-label">Delivered Today</div>
      <div class="adm-stat-value" x-text="stats['delivered_today'] === undefined ? '{{ $stats['delivered_today'] }}' : stats['delivered_today']">{{ $stats['delivered_today'] }}</div>
    </div>
    <div class="adm-stat" :class="pulse('failed')">
      <div class="adm-stat-label">Failed</div>
      <div class="adm-stat-value" x-text="stats['failed'] === undefined ? '{{ $stats['failed'] }}' : stats['failed']">{{ $stats['failed'] }}</div>
    </div>
  </div>

  <div class="flex flex-wrap gap-2">
    @php $active = request('status'); @endphp
    <a href="{{ route('admin.delivery.index') }}" class="adm-pill {{ is_null($active) ? 'adm-pill-active' : '' }}">All</a>
    <a href="{{ route('admin.delivery.index', ['status' => 'assigned']) }}" class="adm-pill {{ $active === 'assigned' ? 'adm-pill-active' : '' }}">Assigned</a>
    <a href="{{ route('admin.delivery.index', ['status' => 'out_for_delivery']) }}" class="adm-pill {{ $active === 'out_for_delivery' ? 'adm-pill-active' : '' }}">Out</a>
    <a href="{{ route('admin.delivery.index', ['status' => 'delivered']) }}" class="adm-pill {{ $active === 'delivered' ? 'adm-pill-active' : '' }}">Delivered Today</a>
    <a href="{{ route('admin.delivery.index', ['status' => 'failed']) }}" class="adm-pill {{ $active === 'failed' ? 'adm-pill-active' : '' }}">Failed</a>
  </div>

  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr>
          <th>Order #</th>
          <th>Customer</th>
          <th>Delivery Person</th>
          <th>Assigned At</th>
          <th>Status</th>
          <th>Delivered At</th>
          <th class="text-right">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($assignments as $assignment)
          @php
            $badgeColor = match($assignment->status->value) {
              'assigned' => 'bg-cyan-100 text-cyan-700',
              'picked_up' => 'bg-blue-100 text-blue-700',
              'out_for_delivery' => 'bg-orange-100 text-orange-700',
              'delivered' => 'bg-green-100 text-green-700',
              'failed' => 'bg-red-100 text-red-600',
              default => 'bg-sage/10 text-charcoal/60',
            };
          @endphp
          <tr>
            <td>
              <a href="{{ route('admin.orders.show', $assignment->order) }}" class="font-semibold text-charcoal hover:text-forest transition">{{ $assignment->order->order_number }}</a>
            </td>
            <td>{{ $assignment->order->user->name ?? '—' }}</td>
            <td>{{ $assignment->deliveryPerson->user->name ?? '—' }}</td>
            <td class="text-xs adm-text-muted">{{ $assignment->assigned_at?->format('d M Y, h:i A') ?? '—' }}</td>
            <td>
              <span class="inline-block rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase {{ $badgeColor }}">{{ $assignment->status->label() }}</span>
            </td>
            <td class="text-xs adm-text-muted">{{ $assignment->delivered_at?->format('d M Y, h:i A') ?? '—' }}</td>
            <td class="text-right">
              <div class="flex items-center justify-end gap-3">
                <button @click="reassignId = {{ $assignment->id }}" class="adm-action-link">Reassign</button>
                <a href="{{ route('admin.orders.show', $assignment->order) }}" class="adm-action-link-muted">View Order</a>
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" class="px-4 py-12"><div class="adm-empty"><p>No delivery assignments found.</p></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div>{{ $assignments->links('pagination::tailwind') }}</div>

  <div x-show="reassignId" x-cloak class="adm-modal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    <div class="fixed inset-0 bg-black/50" @click="reassignId = null"></div>
    <div class="relative z-10 w-full max-w-md rounded-2xl border border-sage/20 bg-white shadow-2xl dark:border-gray-700 dark:bg-gray-800" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
      <div class="adm-modal-header">
        <h3 class="adm-modal-title">Reassign Delivery</h3>
        <button @click="reassignId = null" class="text-charcoal/40 hover:text-charcoal dark:text-gray-400 dark:hover:text-white text-lg">&times;</button>
      </div>
      <form :action="'{{ url('admin/assignments') }}/' + reassignId + '/reassign'" method="POST" class="adm-modal-body space-y-4">
        @csrf
        <div>
          <label class="adm-label">Select Delivery Person</label>
          <select name="delivery_person_id" required class="adm-input">
            <option value="">Choose…</option>
            @foreach($persons as $person)
              <option value="{{ $person->id }}">{{ $person->user->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="flex justify-end gap-3 pt-2 border-t border-sage/20 dark:border-gray-700">
          <button type="button" @click="reassignId = null" class="adm-btn-outline">Cancel</button>
          <button type="submit" class="adm-btn-primary">Reassign</button>
        </div>
      </form>
    </div>
  </div>

</div>
@endsection
