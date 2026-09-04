@forelse($banners as $banner)
  <div class="adm-section overflow-hidden">
    <div class="h-40 bg-forest/5 p-2">
      @if($banner->desktop_image)
        <img src="{{ asset('storage/'.$banner->desktop_image) }}" class="h-full w-full rounded-xl object-cover" alt="{{ $banner->title }}">
      @else
        <div class="flex h-full items-center justify-center text-3xl opacity-20">🎯</div>
      @endif
    </div>
    <div class="p-4 space-y-2">
      <div class="flex items-start justify-between gap-2">
        <div class="min-w-0">
          <h3 class="truncate font-semibold">{{ $banner->title }}</h3>
          @if($banner->subtitle)
            <p class="truncate text-xs adm-text-muted">{{ $banner->subtitle }}</p>
          @endif
        </div>
        @if($banner->is_active)
          <span class="shrink-0 adm-badge bg-forest/10 text-forest">Active</span>
        @else
          <span class="shrink-0 adm-badge bg-charcoal/5 text-charcoal/50">Inactive</span>
        @endif
      </div>
      <div class="flex items-center gap-3 text-[10px] adm-text-muted">
        <span class="rounded-full bg-forest/5 px-2 py-0.5 font-semibold uppercase text-forest/70">{{ $banner->placement }}</span>
        <span>Sort: {{ $banner->sort_order }}</span>
      </div>
      @if($banner->button_text)
        <p class="text-xs adm-text-muted">CTA: "{{ $banner->button_text }}" → {{ $banner->button_url }}</p>
      @endif
      <div class="flex items-center gap-2 pt-2 border-t border-sage/20">
        <button data-action="edit" data-banner="{{ $banner->toJson() }}" class="adm-action-link text-xs">Edit</button>
        <button data-action="toggle" data-id="{{ $banner->id }}" class="adm-btn-ghost text-xs font-semibold {{ $banner->is_active ? 'text-amber-600' : 'text-forest' }}">{{ $banner->is_active ? 'Deactivate' : 'Activate' }}</button>
        <button data-action="delete" data-id="{{ $banner->id }}" class="adm-btn-ghost text-xs font-semibold text-red-500">Delete</button>
      </div>
    </div>
  </div>
@empty
  <div class="col-span-full adm-empty">No banners found. Create your first banner to get started.</div>
@endforelse