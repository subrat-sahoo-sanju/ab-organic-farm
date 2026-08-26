@extends('layouts.admin', ['title' => 'Brands'])

@section('content')
<div class="space-y-4" x-data="{ showModal: false, editing: null }">

  <div class="flex flex-wrap items-center justify-between gap-4">
    <h2 class="adm-page-title">All Brands <span class="adm-page-count">{{ $brands->count() }}</span></h2>
    <button @click="editing = null; showModal = true" class="adm-btn-primary">+ Add Brand</button>
  </div>

  <div class="adm-grid-3">
    @forelse($brands as $brand)
      <div class="adm-section p-4">
        <div class="flex items-center gap-4">
          <div class="h-14 w-14 shrink-0 overflow-hidden rounded-xl bg-charcoal/5 flex items-center justify-center">
            @if($brand->logo_path)
              <img src="{{ asset('storage/'.$brand->logo_path) }}" alt="{{ $brand->name }}" class="h-full w-full object-contain p-1">
            @else
              <span class="text-xl font-bold text-charcoal/20">{{ strtoupper(substr($brand->name, 0, 2)) }}</span>
            @endif
          </div>
          <div class="min-w-0 flex-1">
            <p class="font-semibold adm-text-primary truncate">{{ $brand->name }}</p>
            <p class="text-xs adm-text-muted">{{ $brand->products_count }} products · {{ $brand->slug }}</p>
          </div>
          @if($brand->is_active)
            <span class="shrink-0 adm-badge bg-forest/10 text-forest">Active</span>
          @else
            <span class="shrink-0 adm-badge bg-charcoal/5 text-charcoal/50">Inactive</span>
          @endif
        </div>
        <div class="mt-3 flex items-center gap-3 border-t border-sage/20 pt-3">
          <button @click="editing = {{ $brand->toJson() }}; showModal = true" class="adm-action-link">Edit</button>
          <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" onsubmit="return confirm('Delete this brand?')">
            @csrf @method('DELETE')
            <button type="submit" class="adm-action-link adm-action-link-muted">Delete</button>
          </form>
        </div>
      </div>
    @empty
      <div class="col-span-full adm-empty">No brands found. Add your first brand to get started.</div>
    @endforelse
  </div>

  <div x-show="showModal" x-cloak class="adm-modal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    <div class="adm-modal-card" @click.away="showModal = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
      <div class="adm-modal-header">
        <h3 class="adm-modal-title" x-text="editing ? 'Edit Brand' : 'Create Brand'"></h3>
        <button @click="showModal = false" class="adm-btn-ghost text-lg">&times;</button>
      </div>
      <form :action="editing ? '{{ url('admin/brands') }}/' + editing.id : '{{ route('admin.brands.store') }}'" method="POST" enctype="multipart/form-data" class="adm-modal-body space-y-4">
        @csrf
        <template x-if="editing"><input type="hidden" name="_method" value="PUT"></template>
        <div>
          <label class="adm-label">Name *</label>
          <input type="text" name="name" :value="editing?.name" required class="adm-input">
        </div>
        <div>
          <label class="adm-label">Logo</label>
          <input type="file" name="logo" accept="image/jpeg,image/png,image/webp,image/svg" class="adm-input">
          <template x-if="editing?.logo_path">
            <p class="mt-1 text-xs adm-text-muted">Current: <span x-text="editing?.logo_path"></span></p>
          </template>
        </div>
        <div class="flex gap-6">
          <label class="flex items-center gap-2 text-sm adm-text-primary">
            <input type="checkbox" name="is_active" value="1" :checked="editing ? editing.is_active : true" class="accent-forest">
            Active
          </label>
        </div>
        <div class="adm-modal-footer">
          <button type="button" @click="showModal = false" class="adm-btn-outline">Cancel</button>
          <button type="submit" class="adm-btn-primary" x-text="editing ? 'Update Brand' : 'Create Brand'"></button>
        </div>
      </form>
    </div>
  </div>

</div>
@endsection
