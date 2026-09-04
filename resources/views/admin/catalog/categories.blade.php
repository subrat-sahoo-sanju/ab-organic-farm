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

        <div>
          <label class="adm-label">Card Image <span class="adm-text-muted font-normal">(thumbnail shown on category cards, 800×800)</span></label>
          <input type="file" name="card_image_file" accept="image/jpeg,image/png,image/webp,image/svg" @change="previewCardImage($event)" class="adm-input">
          <template x-if="form.card_image && !cardImagePreview">
            <div class="mt-2 h-16 w-16 overflow-hidden rounded-lg border border-gray-200 bg-gray-50">
              <img :src="'{{ asset('storage/') }}/' + form.card_image" class="h-full w-full object-contain">
            </div>
          </template>
          <template x-if="cardImagePreview">
            <div class="mt-2 h-16 w-16 overflow-hidden rounded-lg border border-gray-200 bg-gray-50">
              <img :src="cardImagePreview" class="h-full w-full object-contain">
            </div>
          </template>
          <p class="mt-1 text-[11px] adm-text-muted">This image appears on the category card/thumbnail. Leave empty to use the main image.</p>
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
                  <input type="text" x-model="form.banner_bg_color" class="adm-input flex-1" placeholder="#7C522A">
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
              <label class="adm-label">Additional Banner Images <span class="adm-text-muted font-normal">(hero carousel — 2400×700 each)</span></label>
              <input type="file" name="banner_images_files[]" accept="image/jpeg,image/png,image/webp" multiple @change="previewBannerImages($event)" class="adm-input">
              <div class="mt-2 flex flex-wrap gap-2">
                <template x-for="(img, i) in form.banner_images" :key="i">
                  <div class="relative h-16 w-28 overflow-hidden rounded-lg border border-gray-200 bg-gray-50">
                    <img :src="img.startsWith('http') ? img : '{{ asset('storage/') }}/' + img" class="h-full w-full object-cover">
                    <button type="button" @click="form.banner_images.splice(i, 1)" class="absolute top-0.5 right-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-white text-[10px]">&times;</button>
                  </div>
                </template>
                <template x-for="(img, i) in bannerImagesPreview" :key="'p'+i">
                  <div class="relative h-16 w-28 overflow-hidden rounded-lg border border-gray-200 bg-gray-50">
                    <img :src="img" class="h-full w-full object-cover">
                  </div>
                </template>
              </div>
              <p class="mt-1 text-[11px] adm-text-muted">Upload multiple images for a hero carousel. First image is the primary banner.</p>
              <input type="hidden" name="banner_images" :value="JSON.stringify(form.banner_images)">
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

        {{-- ═══ PAGE SECTIONS BUILDER ═══ --}}
        <div class="border-t border-gray-200 pt-4">
          <h4 class="mb-3 text-sm font-bold adm-text-primary flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
            Page Sections
          </h4>
          <p class="mb-3 text-[11px] adm-text-muted">Add and arrange content sections on this category page. Drag to reorder.</p>

          {{-- Section list --}}
          <div class="space-y-2">
            <template x-for="(sec, idx) in form.sections" :key="idx">
              <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                <div class="flex items-center gap-2">
                  {{-- Drag handle --}}
                  <button type="button" class="cursor-grab text-gray-400 hover:text-gray-600" title="Drag to reorder">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="15" cy="19" r="1"/></svg>
                  </button>

                  {{-- Type badge --}}
                  <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-bold uppercase"
                        :class="{
                          'bg-blue-100 text-blue-700': sec.type === 'welcome',
                          'bg-green-100 text-green-700': sec.type === 'featured_products',
                          'bg-amber-100 text-amber-700': sec.type === 'trust_badges',
                          'bg-purple-100 text-purple-700': sec.type === 'promo_banner',
                          'bg-rose-100 text-rose-700': sec.type === 'cross_sell',
                          'bg-cyan-100 text-cyan-700': sec.type === 'subcategory_cards',
                        }">
                    <span x-text="sectionTypeLabel(sec.type)"></span>
                  </span>

                  {{-- Title preview --}}
                  <span class="flex-1 truncate text-xs text-gray-600" x-text="sec.title || '(no title)'"></span>

                  {{-- Move buttons --}}
                  <button type="button" @click="moveSection(idx, -1)" :disabled="idx === 0" class="text-gray-400 hover:text-gray-600 disabled:opacity-30">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m18 15-6-6-6 6"/></svg>
                  </button>
                  <button type="button" @click="moveSection(idx, 1)" :disabled="idx === form.sections.length - 1" class="text-gray-400 hover:text-gray-600 disabled:opacity-30">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                  </button>

                  {{-- Visibility toggle --}}
                  <button type="button" @click="sec.visible = !sec.visible" class="text-gray-400 hover:text-gray-600" :title="sec.visible ? 'Hide section' : 'Show section'">
                    <svg x-show="sec.visible" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg x-show="!sec.visible" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                  </button>

                  {{-- Delete --}}
                  <button type="button" @click="removeSection(idx)" class="text-gray-400 hover:text-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                  </button>
                </div>

                {{-- Expanded config for this section --}}
                <div class="mt-3 space-y-2 border-t border-gray-200 pt-3">
                  <div class="grid gap-2 sm:grid-cols-2">
                    <div>
                      <label class="adm-label">Section Title</label>
                      <input type="text" x-model="sec.title" class="adm-input" :placeholder="sectionDefaultTitle(sec.type)">
                    </div>
                    <div>
                      <label class="adm-label">Subtitle</label>
                      <input type="text" x-model="sec.subtitle" class="adm-input" placeholder="Optional subtitle">
                    </div>
                  </div>

                  {{-- Type-specific config --}}
                  {{-- Welcome Intro: admin-controlled tabs + per-tab products --}}
                  <template x-if="sec.type === 'welcome'">
                    <div>
                      <div class="mb-2 flex items-center justify-between">
                        <span class="text-xs font-semibold text-charcoal/70">Tabs (each tab shows its own products)</span>
                        <button type="button" @click="addWelcomeTab(sec)" class="inline-flex items-center gap-1 text-xs font-semibold text-anv-600 hover:text-anv-700">
                          <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                          Add Tab
                        </button>
                      </div>
                      <div class="space-y-2">
                        <template x-for="(tab, ti) in (sec.config.tabs || [])" :key="ti">
                          <div class="rounded-lg border border-gray-200 bg-white p-2">
                            <div class="flex items-center gap-2">
                              <span class="flex-1 truncate text-[11px] font-bold text-charcoal/70" x-text="tab.title || '(untitled tab)'"></span>
                              <button type="button" @click="removeWelcomeTab(sec, ti)" class="text-gray-400 hover:text-red-500">&times;</button>
                            </div>
                            <div class="mt-2 grid gap-2 sm:grid-cols-2">
                              <div>
                                <label class="adm-label">Tab Title (heading)</label>
                                <input type="text" x-model="tab.title" class="adm-input" placeholder="e.g. Everyday Staples">
                              </div>
                              <div>
                                <label class="adm-label">Key <span class="adm-text-muted font-normal">(unique, letters/numbers/-)</span></label>
                                <input type="text" x-model="tab.key" class="adm-input" placeholder="e.g. staples">
                              </div>
                              <div>
                                <label class="adm-label">Icon URL <span class="adm-text-muted font-normal">(storage path, optional)</span></label>
                                <input type="text" x-model="tab.icon" class="adm-input" placeholder="e.g. categories/fruits.png">
                              </div>
                              <div>
                                <label class="adm-label">Active Icon URL <span class="adm-text-muted font-normal">(optional)</span></label>
                                <input type="text" x-model="tab.active_icon" class="adm-input" placeholder="Optional, falls back to icon">
                              </div>
                              <div>
                                <label class="adm-label">Linked Category Slug <span class="adm-text-muted font-normal">(for auto-picks / See All)</span></label>
                                <input type="text" x-model="tab.category_slug" class="adm-input" placeholder="e.g. pulses-dal">
                              </div>
                              <div>
                                <label class="adm-label">Product IDs <span class="adm-text-muted font-normal">(comma separated)</span></label>
                                <input type="text" x-model="tab.product_ids_str" class="adm-input" placeholder="e.g. 1, 3, 7 (empty = auto)">
                              </div>
                            </div>
                            <p class="mt-1 text-[10px] adm-text-muted">Leave Product IDs empty to auto-pick top sellers from the linked category (or store-wide).</p>
                          </div>
                        </template>
                        <template x-if="!(sec.config.tabs || []).length">
                          <p class="rounded border border-dashed border-gray-300 bg-gray-50 p-2 text-[11px] text-gray-500">No tabs yet. Click “Add Tab” to build this welcome section.</p>
                        </template>
                      </div>
                    </div>
                  </template>

                  {{-- Promo Banner --}}
                  <template x-if="sec.type === 'promo_banner'">
                    <div class="space-y-2">
                      <div class="grid gap-2 sm:grid-cols-2">
                        <div>
                          <label class="adm-label">CTA Button Text</label>
                          <input type="text" x-model="sec.config.cta_text" class="adm-input" placeholder="e.g. Shop Now">
                        </div>
                        <div>
                          <label class="adm-label">CTA Button URL</label>
                          <input type="text" x-model="sec.config.cta_url" class="adm-input" placeholder="e.g. /shop">
                        </div>
                      </div>
                      <div class="grid gap-2 sm:grid-cols-2">
                        <div>
                          <label class="adm-label">Background Color</label>
                          <div class="flex gap-2">
                            <input type="color" x-model="sec.config.bg_color" class="h-8 w-10 cursor-pointer rounded border border-gray-200">
                            <input type="text" x-model="sec.config.bg_color" class="adm-input flex-1" placeholder="#7C522A">
                          </div>
                        </div>
                        <div>
                          <label class="adm-label">Text Color</label>
                          <div class="flex gap-2">
                            <input type="color" x-model="sec.config.text_color" class="h-8 w-10 cursor-pointer rounded border border-gray-200">
                            <input type="text" x-model="sec.config.text_color" class="adm-input flex-1" placeholder="#ffffff">
                          </div>
                        </div>
                      </div>
                      <p class="text-[10px] adm-text-muted">Banner image upload is handled separately via the hero banner above.</p>
                    </div>
                  </template>

                  {{-- Trust Badges --}}
                  <template x-if="sec.type === 'trust_badges'">
                    <div>
                      <label class="adm-label">Trust Items (up to 4)</label>
                      <template x-for="(item, i) in sec.config.items" :key="i">
                        <div class="mb-2 flex gap-2 rounded border border-gray-200 bg-white p-2">
                          <input type="text" x-model="item.title" class="adm-input flex-1" placeholder="Title">
                          <input type="text" x-model="item.text" class="adm-input flex-1" placeholder="Description">
                          <button type="button" @click="sec.config.items.splice(i, 1)" class="text-gray-400 hover:text-red-500">&times;</button>
                        </div>
                      </template>
                      <button type="button" @click="sec.config.items.push({title:'', text:'', icon:'leaf', image:''})" x-show="sec.config.items.length < 4"
                              class="text-xs font-semibold text-anv-600 hover:text-anv-700">+ Add Badge</button>
                    </div>
                  </template>

                  {{-- Featured Products / Cross Sell --}}
                  <template x-if="sec.type === 'featured_products' || sec.type === 'cross_sell'">
                    <div>
                      <label class="adm-label">Product IDs <span class="adm-text-muted font-normal">(comma-separated, or leave empty for auto-select)</span></label>
                      <input type="text" x-model="sec.config.product_ids_str" class="adm-input" placeholder="e.g. 1,2,3,4">
                      <p class="mt-1 text-[10px] adm-text-muted">Leave empty to auto-select top-selling products from this category.</p>
                    </div>
                  </template>
                </div>
              </div>
            </template>
          </div>

          {{-- Add section button --}}
          <div class="mt-3 flex flex-wrap gap-2">
            <button type="button" @click="addSection('welcome')" class="inline-flex items-center gap-1 rounded-full border border-dashed border-gray-300 bg-white px-3 py-1.5 text-[11px] font-semibold text-gray-600 transition hover:border-anv-400 hover:text-anv-700">
              + Welcome Intro
            </button>
            <button type="button" @click="addSection('featured_products')" class="inline-flex items-center gap-1 rounded-full border border-dashed border-gray-300 bg-white px-3 py-1.5 text-[11px] font-semibold text-gray-600 transition hover:border-anv-400 hover:text-anv-700">
              + Featured Products
            </button>
            <button type="button" @click="addSection('trust_badges')" class="inline-flex items-center gap-1 rounded-full border border-dashed border-gray-300 bg-white px-3 py-1.5 text-[11px] font-semibold text-gray-600 transition hover:border-anv-400 hover:text-anv-700">
              + Trust Badges
            </button>
            <button type="button" @click="addSection('promo_banner')" class="inline-flex items-center gap-1 rounded-full border border-dashed border-gray-300 bg-white px-3 py-1.5 text-[11px] font-semibold text-gray-600 transition hover:border-anv-400 hover:text-anv-700">
              + Promo Banner
            </button>
            <button type="button" @click="addSection('cross_sell')" class="inline-flex items-center gap-1 rounded-full border border-dashed border-gray-300 bg-white px-3 py-1.5 text-[11px] font-semibold text-gray-600 transition hover:border-anv-400 hover:text-anv-700">
              + Cross Sell
            </button>
            <button type="button" @click="addSection('subcategory_cards')" class="inline-flex items-center gap-1 rounded-full border border-dashed border-gray-300 bg-white px-3 py-1.5 text-[11px] font-semibold text-gray-600 transition hover:border-anv-400 hover:text-anv-700">
              + Subcategory Cards
            </button>
          </div>

          {{-- Hidden input to submit sections as JSON --}}
          <input type="hidden" name="sections" :value="JSON.stringify(form.sections)">
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
    cardImagePreview: null,
    bannerPreview: null,
    brandLogoPreview: null,
    bannerImagesPreview: [],
    form: {
      name: '',
      parent_id: '',
      description: '',
      icon: '',
      sort_order: 0,
      is_active: true,
      is_featured: false,
      image_path: '',
      card_image: '',
      banner_heading: '',
      banner_subheading: '',
      banner_image: '',
      banner_cta_text: '',
      banner_cta_url: '',
      banner_bg_color: '#7C522A',
        brand_logo: '',
        brand_name: '',
        banner_images: [],
        sections: [],
    },
    openCreate() {
      this.editingId = null;
      this.imagePreview = null;
      this.cardImagePreview = null;
      this.bannerPreview = null;
      this.brandLogoPreview = null;
      this.bannerImagesPreview = [];
        this.form = {
        name: '', parent_id: '', description: '', icon: '', sort_order: 0,
        is_active: true, is_featured: false, image_path: '', card_image: '',
        banner_heading: '', banner_subheading: '', banner_image: '',
        banner_cta_text: '', banner_cta_url: '', banner_bg_color: '#7C522A',
        brand_logo: '', brand_name: '', banner_images: [], sections: [],
      };
      this.showModal = true;
    },
    openEdit(cat) {
      this.editingId = cat.id;
      this.imagePreview = null;
      this.cardImagePreview = null;
      this.bannerPreview = null;
      this.brandLogoPreview = null;
      this.bannerImagesPreview = [];
      let sections = [];
      try {
        sections = typeof cat.sections === 'string' ? JSON.parse(cat.sections) : (cat.sections || []);
      } catch(e) { sections = []; }
      // Ensure each section has config object and product_ids_str
      sections = sections.map(s => {
        let out = {
          ...s,
          config: s.config || {},
          visible: s.visible !== false,
          product_ids_str: (s.config?.product_ids || []).join(', '),
        };
        // Welcome tabs: expose per-tab product_ids as editable strings
        if (s.type === 'welcome' && Array.isArray(out.config.tabs)) {
          out.config.tabs = out.config.tabs.map(t => ({
            key: t.key || '',
            title: t.title || '',
            icon: t.icon || '',
            active_icon: t.active_icon || '',
            category_slug: t.category_slug || '',
            see_all: t.see_all || '',
            product_ids_str: (t.product_ids || []).join(', '),
          }));
        }
        return out;
      });
      this.form = {
        name: cat.name || '',
        parent_id: cat.parent_id || '',
        description: cat.description || '',
        icon: cat.icon || '',
        sort_order: cat.sort_order || 0,
        is_active: cat.is_active,
        is_featured: cat.is_featured,
        image_path: cat.image_path || '',
        card_image: cat.card_image || '',
        banner_heading: cat.banner_heading || '',
        banner_subheading: cat.banner_subheading || '',
        banner_image: cat.banner_image || '',
        banner_cta_text: cat.banner_cta_text || '',
        banner_cta_url: cat.banner_cta_url || '',
        banner_bg_color: cat.banner_bg_color || '#7C522A',
        brand_logo: cat.brand_logo || '',
        brand_name: cat.brand_name || '',
        banner_images: cat.banner_images || [],
        sections: sections,
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
    previewCardImage(e) {
      const f = e.target.files && e.target.files[0];
      this.cardImagePreview = f ? URL.createObjectURL(f) : null;
    },
    previewBanner(e) {
      const f = e.target.files && e.target.files[0];
      this.bannerPreview = f ? URL.createObjectURL(f) : null;
    },
    previewBrandLogo(e) {
      const f = e.target.files && e.target.files[0];
      this.brandLogoPreview = f ? URL.createObjectURL(f) : null;
    },
    previewBannerImages(e) {
      const files = e.target.files;
      if (!files) return;
      for (let i = 0; i < files.length; i++) {
        this.bannerImagesPreview.push(URL.createObjectURL(files[i]));
      }
    },
    // ─── Section Manager ───
    sectionTypes: [
      { value: 'welcome', label: 'Welcome Intro', icon: '👋' },
      { value: 'featured_products', label: 'Featured Products', icon: '⭐' },
      { value: 'trust_badges', label: 'Trust Badges', icon: '🛡️' },
      { value: 'promo_banner', label: 'Promo Banner', icon: '🎯' },
      { value: 'cross_sell', label: 'Cross Sell', icon: '🛒' },
      { value: 'subcategory_cards', label: 'Subcategory Cards', icon: '📂' },
    ],
    sectionTypeLabel(type) {
      const t = this.sectionTypes.find(s => s.value === type);
      return t ? t.icon + ' ' + t.label : type;
    },
    sectionDefaultTitle(type) {
      const defaults = {
        welcome: 'Welcome to AB Organic',
        featured_products: 'Featured Products',
        trust_badges: 'Why Choose AB Organic?',
        promo_banner: 'Special Offer',
        cross_sell: 'You May Also Like',
        subcategory_cards: 'Explore Categories',
      };
      return defaults[type] || '';
    },
    addSection(type) {
      const defaults = {
        welcome: { title: 'Welcome to AB Organic', subtitle: 'Farm-fresh certified organic products, delivered to your doorstep.', config: { tabs: [] } },
        featured_products: { title: 'Featured Products', subtitle: 'Our best sellers in this collection' },
        trust_badges: { title: 'Why Choose AB Organic?', subtitle: '', config: { items: [
          { title: '100% Certified Organic', text: 'Every product is lab-tested and certified', icon: 'leaf', image: '' },
          { title: 'Farm to Table', text: 'Directly sourced from organic farms', icon: 'sprout', image: '' },
          { title: 'Traditional Processing', text: 'Cold-pressed, stone-ground methods', icon: 'wheat', image: '' },
          { title: 'No Chemicals', text: 'Zero preservatives or artificial additives', icon: 'shield-check', image: '' },
        ]}},
        promo_banner: { title: 'Special Offer', subtitle: 'Limited time deal', config: { bg_color: '#7C522A', text_color: '#ffffff', cta_text: 'Shop Now', cta_url: '#' }},
        cross_sell: { title: 'You May Also Like', subtitle: 'Complete your organic collection' },
        subcategory_cards: { title: 'Explore Categories', subtitle: '' },
      };
      const d = defaults[type] || { title: '', subtitle: '' };
      this.form.sections.push({
        type: type,
        title: d.title || '',
        subtitle: d.subtitle || '',
        visible: true,
        config: d.config || {},
        product_ids_str: '',
      });
    },
    addWelcomeTab(sec) {
      if (!sec.config.tabs) sec.config.tabs = [];
      sec.config.tabs.push({ key: '', title: '', icon: '', active_icon: '', category_slug: '', see_all: '', product_ids_str: '' });
      sec.config.tabs = [...sec.config.tabs];
    },
    removeWelcomeTab(sec, i) {
      sec.config.tabs.splice(i, 1);
      sec.config.tabs = [...sec.config.tabs];
    },
    removeSection(idx) {
      this.form.sections.splice(idx, 1);
    },
    moveSection(idx, dir) {
      const newIdx = idx + dir;
      if (newIdx < 0 || newIdx >= this.form.sections.length) return;
      const temp = this.form.sections[idx];
      this.form.sections[idx] = this.form.sections[newIdx];
      this.form.sections[newIdx] = temp;
      // Force reactivity
      this.form.sections = [...this.form.sections];
    },
  }
}
</script>
@endsection
