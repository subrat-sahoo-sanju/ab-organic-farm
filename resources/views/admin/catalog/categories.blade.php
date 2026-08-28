@extends('layouts.admin', ['title' => 'Categories'])

@section('content')
<div class="space-y-4" x-data="categoryManager()">

  <div class="flex flex-wrap items-center justify-between gap-4">
    <h2 class="adm-page-title">All Categories</h2>
    <button @click="openCreate()" class="adm-btn-primary">+ Add Category</button>
  </div>

  <div class="adm-table-wrap overflow-x-auto">
    <table class="adm-table">
      <thead>
        <tr>
          <th>Category</th>
          <th>Slug</th>
          <th>Products</th>
          <th>Status</th>
          <th>Featured</th>
          <th class="text-right">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($categories as $category)
          <tr>
            <td>
              <div class="flex items-center gap-3">
                <div class="h-10 w-10 shrink-0 overflow-hidden rounded-lg bg-forest/5 p-1">
                  @if($category->image_path)
                    <img src="{{ asset('storage/'.$category->image_path) }}" class="h-full w-full object-contain">
                  @else
                    <div class="flex h-full items-center justify-center text-lg opacity-30">🏷️</div>
                  @endif
                </div>
                <div>
                  <span class="font-semibold adm-text-primary">{{ $category->name }}</span>
                  @if($category->icon)<span class="ml-1.5 adm-text-muted">{{ $category->icon }}</span>@endif
                </div>
              </div>
            </td>
            <td class="font-mono text-xs adm-text-muted">{{ $category->slug }}</td>
            <td class="adm-text-secondary">{{ $category->products_count ?? $category->products()->count() }}</td>
            <td>
              <span class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase
                {{ $category->is_active ? 'adm-pill-active' : 'adm-text-muted' }}">
                {{ $category->is_active ? 'Active' : 'Draft' }}
              </span>
            </td>
            <td>
              @if($category->is_featured)
                <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-bold uppercase text-amber-700">Featured</span>
              @else
                <span class="adm-text-muted text-[10px]">—</span>
              @endif
            </td>
            <td class="text-right">
              <div class="flex items-center justify-end gap-2">
                <button @click="openEdit({{ $category->toJson() }})" class="adm-action-link">Edit</button>
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="adm-action-link adm-action-link-muted">Delete</button>
                </form>
              </div>
            </td>
          </tr>

          @if($category->children->count())
            @foreach($category->children as $child)
              <tr>
                <td>
                  <div class="flex items-center gap-3 pl-8">
                    <div class="h-10 w-10 shrink-0 overflow-hidden rounded-lg bg-forest/5 p-1">
                      @if($child->image_path)
                        <img src="{{ asset('storage/'.$child->image_path) }}" class="h-full w-full object-contain">
                      @else
                        <div class="flex h-full items-center justify-center text-lg opacity-30">🏷️</div>
                      @endif
                    </div>
                    <div>
                      <span class="font-semibold adm-text-primary">{{ $child->name }}</span>
                      @if($child->icon)<span class="ml-1.5 adm-text-muted">{{ $child->icon }}</span>@endif
                    </div>
                  </div>
                </td>
                <td class="font-mono text-xs adm-text-muted">{{ $child->slug }}</td>
                <td class="adm-text-secondary">{{ $child->products_count ?? $child->products()->count() }}</td>
                <td>
                  <span class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase
                    {{ $child->is_active ? 'adm-pill-active' : 'adm-text-muted' }}">
                    {{ $child->is_active ? 'Active' : 'Draft' }}
                  </span>
                </td>
                <td>
                  @if($child->is_featured)
                    <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-bold uppercase text-amber-700">Featured</span>
                  @else
                    <span class="adm-text-muted text-[10px]">—</span>
                  @endif
                </td>
                <td class="text-right">
                  <div class="flex items-center justify-end gap-2">
                    <button @click="openEdit({{ $child->toJson() }})" class="adm-action-link">Edit</button>
                    <form action="{{ route('admin.categories.destroy', $child) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                      @csrf @method('DELETE')
                      <button type="submit" class="adm-action-link adm-action-link-muted">Delete</button>
                    </form>
                  </div>
                </td>
              </tr>
              @if($child->children->count())
                @foreach($child->children as $grandchild)
                  <tr>
                    <td>
                      <div class="flex items-center gap-3 pl-16">
                        <div class="h-10 w-10 shrink-0 overflow-hidden rounded-lg bg-forest/5 p-1">
                          @if($grandchild->image_path)
                            <img src="{{ asset('storage/'.$grandchild->image_path) }}" class="h-full w-full object-contain">
                          @else
                            <div class="flex h-full items-center justify-center text-lg opacity-30">🏷️</div>
                          @endif
                        </div>
                        <div>
                          <span class="font-semibold adm-text-primary">{{ $grandchild->name }}</span>
                          @if($grandchild->icon)<span class="ml-1.5 adm-text-muted">{{ $grandchild->icon }}</span>@endif
                        </div>
                      </div>
                    </td>
                    <td class="font-mono text-xs adm-text-muted">{{ $grandchild->slug }}</td>
                    <td class="adm-text-secondary">{{ $grandchild->products_count ?? $grandchild->products()->count() }}</td>
                    <td>
                      <span class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase
                        {{ $grandchild->is_active ? 'adm-pill-active' : 'adm-text-muted' }}">
                        {{ $grandchild->is_active ? 'Active' : 'Draft' }}
                      </span>
                    </td>
                    <td>
                      @if($grandchild->is_featured)
                        <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-bold uppercase text-amber-700">Featured</span>
                      @else
                        <span class="adm-text-muted text-[10px]">—</span>
                      @endif
                    </td>
                    <td class="text-right">
                      <div class="flex items-center justify-end gap-2">
                        <button @click="openEdit({{ $grandchild->toJson() }})" class="adm-action-link">Edit</button>
                        <form action="{{ route('admin.categories.destroy', $grandchild) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                          @csrf @method('DELETE')
                          <button type="submit" class="adm-action-link adm-action-link-muted">Delete</button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @endforeach
              @endif
            @endforeach
          @endif
        @empty
          <tr><td colspan="6" class="adm-empty">No categories found. Create your first category to get started.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div x-show="showModal" x-cloak class="adm-modal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    <div class="adm-modal-card" @click.away="showModal = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
      <div class="adm-modal-header">
        <h3 class="adm-modal-title" x-text="editingId ? 'Edit Category' : 'Create Category'"></h3>
        <button @click="showModal = false" class="adm-btn-ghost text-lg">&times;</button>
      </div>
      <form id="catForm" :action="editingId ? '{{ url('admin/categories') }}/' + editingId : '{{ route('admin.categories.store') }}'" method="POST" enctype="multipart/form-data" class="adm-modal-body space-y-4">
        @csrf
        <template x-if="editingId"><input type="hidden" name="_method" value="PATCH"></template>

        <div>
          <label class="adm-label">Name *</label>
          <input type="text" name="name" x-model="form.name" class="adm-input" required>
        </div>

        <div>
          <label class="adm-label">Parent Category</label>
          <select name="parent_id" x-model="form.parent_id" class="adm-input">
            <option value="">None (Top Level)</option>
            @foreach($allCategories as $cat)
              <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="adm-label">Description</label>
          <textarea name="description" x-model="form.description" class="adm-input" rows="3"></textarea>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <label class="adm-label">Icon</label>
            <input type="text" name="icon" x-model="form.icon" class="adm-input" placeholder="e.g. 🌿">
          </div>
          <div>
            <label class="adm-label">Sort Order</label>
            <input type="number" name="sort_order" x-model="form.sort_order" class="adm-input" min="0">
          </div>
        </div>

        <div>
          <label class="adm-label">Image</label>
          <input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/svg" @change="previewImage($event)" class="adm-input">
          <template x-if="form.image_path && !imagePreview">
            <div class="mt-2 h-16 w-16 overflow-hidden rounded-lg border border-gray-200 bg-gray-50">
              <img :src="'{{ asset('storage/') }}/' + form.image_path" class="h-full w-full object-contain">
            </div>
          </template>
          <template x-if="imagePreview">
            <div class="mt-2 h-16 w-16 overflow-hidden rounded-lg border border-gray-200 bg-gray-50">
              <img :src="imagePreview" class="h-full w-full object-contain">
            </div>
          </template>
          <p class="mt-1 text-[11px] adm-text-muted">Images are automatically resized to 800×800.</p>
        </div>

        <div class="flex gap-6">
          <label class="flex items-center gap-2 text-sm adm-text-primary">
            <input type="checkbox" name="is_active" value="1" x-model="form.is_active" class="accent-forest">
            Active
          </label>
          <label class="flex items-center gap-2 text-sm adm-text-primary">
            <input type="checkbox" name="is_featured" value="1" x-model="form.is_featured" class="accent-forest">
            Featured
          </label>
        </div>
      </form>
      <div class="adm-modal-footer">
        <button type="button" @click="showModal = false" class="adm-btn-outline">Cancel</button>
        <button type="submit" form="catForm" class="adm-btn-primary" x-text="editingId ? 'Update Category' : 'Create Category'"></button>
      </div>
    </div>
  </div>

</div>

<script>
function categoryManager() {
  return {
    showModal: false,
    editingId: null,
    imagePreview: null,
    form: {
      name: '',
      parent_id: '',
      description: '',
      icon: '',
      sort_order: 0,
      is_active: true,
      is_featured: false,
      image_path: '',
    },
    openCreate() {
      this.editingId = null;
      this.imagePreview = null;
      this.form = { name: '', parent_id: '', description: '', icon: '', sort_order: 0, is_active: true, is_featured: false, image_path: '' };
      this.showModal = true;
    },
    openEdit(cat) {
      this.editingId = cat.id;
      this.imagePreview = null;
      this.form = {
        name: cat.name || '',
        parent_id: cat.parent_id || '',
        description: cat.description || '',
        icon: cat.icon || '',
        sort_order: cat.sort_order || 0,
        is_active: cat.is_active,
        is_featured: cat.is_featured,
        image_path: cat.image_path || '',
      };
      this.showModal = true;
    },
    previewImage(e) {
      const f = e.target.files && e.target.files[0];
      this.imagePreview = f ? URL.createObjectURL(f) : null;
    },
  }
}
</script>
@endsection
