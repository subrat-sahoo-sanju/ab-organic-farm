@extends('layouts.admin', ['title' => 'Delivery Staff'])

@section('content')
<div class="space-y-4" x-data="{ showModal: false }">

  <div class="flex flex-wrap items-center justify-between gap-4">
    <h2 class="adm-page-title">Delivery Staff <span class="adm-page-count">{{ $persons->count() }}</span></h2>
    <button @click="showModal = true" class="adm-btn-primary">+ Add Delivery Person</button>
  </div>

  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Vehicle</th>
          <th>Available</th>
          <th class="text-right">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($persons as $person)
          <tr>
            <td class="font-semibold">{{ $person->user->name }}</td>
            <td class="text-xs adm-text-muted">{{ $person->user->email }}</td>
            <td class="text-xs adm-text-muted">{{ $person->user->phone ?? '—' }}</td>
            <td class="text-xs adm-text-muted">{{ $person->vehicle_number ?? '—' }}</td>
            <td>
              <span class="inline-block rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase {{ $person->is_available ? 'bg-forest/10 text-forest' : 'bg-red-100 text-red-600' }}">
                {{ $person->is_available ? 'Available' : 'Unavailable' }}
              </span>
            </td>
            <td class="text-right">
              <form action="{{ route('admin.delivery-persons.toggle', $person) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="font-semibold text-xs {{ $person->is_available ? 'adm-btn-danger' : 'adm-btn-primary' }} hover:underline transition">
                  {{ $person->is_available ? 'Mark Unavailable' : 'Mark Available' }}
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="px-4 py-12"><div class="adm-empty"><p>No delivery staff found.</p></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div x-show="showModal" x-cloak class="adm-modal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    <div class="adm-modal-card" @click.away="showModal = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
      <div class="adm-modal-header">
        <h3 class="adm-modal-title">Add Delivery Person</h3>
        <button @click="showModal = false" class="text-charcoal/40 hover:text-charcoal dark:text-gray-400 dark:hover:text-white text-lg">&times;</button>
      </div>
      <form action="{{ route('admin.delivery-persons.store') }}" method="POST" class="adm-modal-body space-y-4">
        @csrf
        <div>
          <label class="adm-label">Name *</label>
          <input type="text" name="name" required class="adm-input">
        </div>
        <div>
          <label class="adm-label">Email *</label>
          <input type="email" name="email" required class="adm-input">
        </div>
        <div>
          <label class="adm-label">Phone *</label>
          <input type="text" name="phone" required maxlength="10" class="adm-input">
        </div>
        <div class="adm-grid-2">
          <div>
            <label class="adm-label">Vehicle Type</label>
            <input type="text" name="vehicle_type" class="adm-input" placeholder="e.g. Bike, Scooter">
          </div>
          <div>
            <label class="adm-label">License Plate</label>
            <input type="text" name="license_plate" class="adm-input" placeholder="e.g. OD01AB1234">
          </div>
        </div>
        <div>
          <label class="adm-label">Delivery Areas</label>
          <input type="text" name="delivery_areas" class="adm-input" placeholder="Comma-separated, e.g. Area1, Area2">
          <p class="mt-1 text-[10px] adm-text-muted">Comma-separated list of areas this person covers.</p>
        </div>
        <div class="flex justify-end gap-3 pt-2 border-t border-sage/20 dark:border-gray-700">
          <button type="button" @click="showModal = false" class="adm-btn-outline">Cancel</button>
          <button type="submit" class="adm-btn-primary">Create</button>
        </div>
      </form>
    </div>
  </div>

</div>
@endsection
