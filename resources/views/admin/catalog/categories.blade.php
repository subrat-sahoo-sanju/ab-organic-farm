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
                <button @click="confirmDelete({{ $category->id }}, '{{ $category->name }}')" class="adm-action-link adm-action-link-muted">Delete</button>
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
                    <button @click="confirmDelete({{ $child->id }}, '{{ $child->name }}')" class="adm-action-link adm-action-link-muted">Delete</button>
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
                        <button @click="confirmDelete({{ $grandchild->id }}, '{{ $grandchild->name }}')" class="adm-action-link adm-action-link-muted">Delete</button>
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

        {{-- ═══ BANNER SETTINGS ═══ --}}
        <div class="border-t border-gray-200 pt-4">
          <h4 class="mb-3 text-sm font-bold adm-text-primary flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
            Collection Banner
          </h4>
          <p class="mb-3 text-[11px] adm-text-muted">Controls the hero section shown at the top of this category page.</p>

          <div class="space-y-3">
            <div class="grid gap-3 sm:grid-cols-2">
              <div>
                <label class="adm-label">Banner Heading</label>
                <input type="text" name="banner_heading" x-model="form.banner_heading" class="adm-input" placeholder="e.g. Desi A2 Cow Ghee">
              </div>
              <div>
                <label class="adm-label">Banner Subheading</label>
                <input type="text" name="banner_subheading" x-model="form.banner_subheading" class="adm-input" placeholder="e.g. Traditional Bilona method">
              </div>
            </div>

            <div class="grid gap-3 sm:grid-cols-2">
              <div>
                <label class="adm-label">CTA Button Text</label>
                <input type="text" name="banner_cta_text" x-model="form.banner_cta_text" class="adm-input" placeholder="e.g. Shop Now">
              </div>
              <div>
                <label class="adm-label">CTA Button URL</label>
                <input type="url" name="banner_cta_url" x-model="form.banner_cta_url" class="adm-input" placeholder="e.g. /collections/desi-ghee">
              </div>
            </div>

            <div class="grid gap-3 sm:grid-cols-2">
              <div>
                <label class="adm-label">Banner Background Color</label>
                <div class="flex gap-2">
                  <input type="color" name="banner_bg_color" x-model="form.banner_bg_color" class="h-9 w-12 cursor-pointer rounded border border-gray-200">
                  <input type="text" x-model="form.banner_bg_color" class="adm-input flex-1" placeholder="#00584b">
                </div>
              </div>
              <div>
                <label class="adm-label">Brand Name</label>
                <input type="text" name="brand_name" x-model="form.brand_name" class="adm-input" placeholder="e.g. AB Organic Farm">
              </div>
            </div>

            <div>
              <label class="adm-label">Banner Image <span class="adm-text-muted font-normal">(recommended 2400×700)</span></label>
              <input type="file" name="banner_image_file" accept="image/jpeg,image/png,image/webp" @change="previewBanner($event)" class="adm-input">
              <template x-if="form.banner_image && !bannerPreview">
                <div class="mt-2 h-20 w-full overflow-hidden rounded-lg border border-gray-200 bg-gray-50">
                  <img :src="'{{ asset('storage/') }}/' + form.banner_image" class="h-full w-full object-cover">
                </div>
              </template>
              <template x-if="bannerPreview">
                <div class="mt-2 h-20 w-full overflow-hidden rounded-lg border border-gray-200 bg-gray-50">
                  <img :src="bannerPreview" class="h-full w-full object-cover">
                </div>
              </template>
            </div>

            <div>
              <label class="adm-label">Brand Logo <span class="adm-text-muted font-normal">(displayed on banner)</span></label>
              <input type="file" name="brand_logo_file" accept="image/jpeg,image/png,image/webp,image/svg" @change="previewBrandLogo($event)" class="adm-input">
              <template x-if="form.brand_logo && !brandLogoPreview">
                <div class="mt-2 h-10 w-24 overflow-hidden rounded border border-gray-200 bg-gray-50">
                  <img :src="'{{ asset('storage/') }}/' + form.brand_logo" class="h-full w-full object-contain">
                </div>
              </template>
              <template x-if="brandLogoPreview">
                <div class="mt-2 h-10 w-24 overflow-hidden rounded border border-gray-200 bg-gray-50">
                  <img :src="brandLogoPreview" class="h-full w-full object-contain">
                </div>
              </template>
            </div>
          </div>
        </div>
      </form>
      <div class="adm-modal-footer">
        <button type="button" @click="showModal = false" class="adm-btn-outline">Cancel</button>
        <button type="submit" form="catForm" class="adm-btn-primary" x-text="editingId ? 'Update Category' : 'Create Category'"></button>
      </div>
    </div>
  </div>

  <div x-show="deleteModal" x-cloak class="adm-modal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-lift dark:bg-gray-800" @click.away="deleteModal = false">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-rose-100 text-rose-600 dark:bg-rose-500/15 dark:text-rose-400">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
        </div>
        <div>
          <h3 class="text-base font-bold text-charcoal dark:text-white">Delete category?</h3>
          <p class="text-xs adm-text-muted">This action cannot be undone.</p>
        </div>
        <button @click="deleteModal = false" class="ml-auto text-charcoal/40 transition hover:text-charcoal dark:text-gray-400 dark:hover:text-white">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>
      </div>

      <p class="mt-4 text-sm text-charcoal/70 dark:text-gray-300">
        You are about to delete <strong class="font-semibold" x-text="deleteName"></strong>.
      </p>

      <div class="mt-3 space-y-2 rounded-xl border border-sage/15 bg-forest/5 p-3 text-xs text-charcoal/70 dark:border-gray-700 dark:bg-gray-700/30 dark:text-gray-300">
        <p class="flex items-start gap-2">
          <svg class="mt-0.5 h-3.5 w-3.5 shrink-0 text-forest" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
          <span>Sub-categories will be moved up one level and kept live.</span>
        </p>
        <p class="flex items-start gap-2">
          <svg class="mt-0.5 h-3.5 w-3.5 shrink-0 text-forest" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
          <span>Products in this category will be kept and reassigned.</span>
        </p>
      </div>

      <form :action="'{{ url('admin/categories') }}/' + deleteId" method="POST" class="mt-6 flex justify-end gap-3">
        @csrf
        @method('DELETE')
        <button type="button" @click="deleteModal = false" class="adm-btn-outline">Cancel</button>
        <button type="submit" class="adm-btn-danger">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
          Delete Category
        </button>
      </form>
    </div>
  </div>

</div>

<script>
function categoryManager() {
  return {
    showModal: false,
    deleteModal: false,
    deleteId: null,
    deleteName: '',
    editingId: null,
    imagePreview: null,
    bannerPreview: null,
    brandLogoPreview: null,
    form: {
      name: '',
      parent_id: '',
      description: '',
      icon: '',
      sort_order: 0,
      is_active: true,
      is_featured: false,
      image_path: '',
      banner_heading: '',
      banner_subheading: '',
      banner_image: '',
      banner_cta_text: '',
      banner_cta_url: '',
      banner_bg_color: '#00584b',
      brand_logo: '',
      brand_name: '',
    },
    openCreate() {
      this.editingId = null;
      this.imagePreview = null;
      this.bannerPreview = null;
      this.brandLogoPreview = null;
      this.form = {
        name: '', parent_id: '', description: '', icon: '', sort_order: 0,
        is_active: true, is_featured: false, image_path: '',
        banner_heading: '', banner_subheading: '', banner_image: '',
        banner_cta_text: '', banner_cta_url: '', banner_bg_color: '#00584b',
        brand_logo: '', brand_name: '',
      };
      this.showModal = true;
    },
    openEdit(cat) {
      this.editingId = cat.id;
      this.imagePreview = null;
      this.bannerPreview = null;
      this.brandLogoPreview = null;
      this.form = {
        name: cat.name || '',
        parent_id: cat.parent_id || '',
        description: cat.description || '',
        icon: cat.icon || '',
        sort_order: cat.sort_order || 0,
        is_active: cat.is_active,
        is_featured: cat.is_featured,
        image_path: cat.image_path || '',
        banner_heading: cat.banner_heading || '',
        banner_subheading: cat.banner_subheading || '',
        banner_image: cat.banner_image || '',
        banner_cta_text: cat.banner_cta_text || '',
        banner_cta_url: cat.banner_cta_url || '',
        banner_bg_color: cat.banner_bg_color || '#00584b',
        brand_logo: cat.brand_logo || '',
        brand_name: cat.brand_name || '',
      };
      this.showModal = true;
    },
    confirmDelete(id, name) {
      this.deleteId = id;
      this.deleteName = name;
      this.deleteModal = true;
    },
    previewImage(e) {
      const f = e.target.files && e.target.files[0];
      this.imagePreview = f ? URL.createObjectURL(f) : null;
    },
    previewBanner(e) {
      const f = e.target.files && e.target.files[0];
      this.bannerPreview = f ? URL.createObjectURL(f) : null;
    },
    previewBrandLogo(e) {
      const f = e.target.files && e.target.files[0];
      this.brandLogoPreview = f ? URL.createObjectURL(f) : null;
    },
  }
}
</script>
@endsection
