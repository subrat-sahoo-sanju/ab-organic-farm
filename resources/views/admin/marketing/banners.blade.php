@extends('layouts.admin', ['title' => 'Banners'])

@section('content')
<div class="space-y-4" x-data="bannerManager()">

  <div class="flex flex-wrap items-center justify-between gap-4">
    <h2 class="adm-page-title">All Banners <span class="adm-page-count">{{ $banners->count() }}</span></h2>
    <button @click="openCreate()" class="adm-btn-primary">+ Add Banner</button>
  </div>

  @if(session('success'))
    <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
  @endif

  <div class="adm-grid-3">
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
            <button @click="openEdit({{ $banner->toJson() }})" class="adm-action-link text-xs">Edit</button>
            <form action="{{ route('admin.banners.toggle', $banner) }}" method="POST">
              @csrf
              <button type="submit" class="adm-btn-ghost text-xs font-semibold {{ $banner->is_active ? 'text-amber-600' : 'text-forest' }}">
                {{ $banner->is_active ? 'Deactivate' : 'Activate' }}
              </button>
            </form>
            <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" onsubmit="return confirm('Delete this banner?')">
              @csrf @method('DELETE')
              <button type="submit" class="adm-btn-ghost text-xs font-semibold text-red-500">Delete</button>
            </form>
          </div>
        </div>
      </div>
    @empty
      <div class="col-span-full adm-empty">No banners found. Create your first banner to get started.</div>
    @endforelse
  </div>

  {{-- Modal --}}
  <div x-show="showModal" x-cloak class="adm-modal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    <div class="adm-modal-card" @click.away="showModal = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
      <div class="adm-modal-header">
        <h3 class="adm-modal-title" x-text="editingId ? 'Edit Banner' : 'Create Banner'"></h3>
        <button @click="showModal = false" class="adm-btn-ghost text-lg">&times;</button>
      </div>
      <form :action="editingId ? '{{ url('admin/banners') }}/' + editingId : '{{ route('admin.banners.store') }}'" method="POST" enctype="multipart/form-data" class="adm-modal-body space-y-4">
        @csrf
        <template x-if="editingId"><input type="hidden" name="_method" value="PATCH"></template>

        <div>
          <label class="adm-label">Title *</label>
          <input type="text" name="title" x-model="form.title" required class="adm-input">
          @error('title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>
        <div>
          <label class="adm-label">Subtitle</label>
          <input type="text" name="subtitle" x-model="form.subtitle" class="adm-input">
          @error('subtitle') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <label class="adm-label">Placement *</label>
            <select name="placement" x-model="form.placement" @change="applyRecommended()" required class="adm-input">
              <option value="hero">Hero</option>
              <option value="strip">Strip</option>
              <option value="category_top">Category Top</option>
              <option value="promotional">Promotional</option>
            </select>
            @error('placement') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
          </div>
          <div>
            <label class="adm-label">Sort Order</label>
            <input type="number" name="sort_order" x-model="form.sort_order" min="0" class="adm-input">
            @error('sort_order') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
          </div>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <label class="adm-label">Image Width (px)</label>
            <input type="number" name="width" x-model="form.width" min="1" :max="MAX" @input="checkDims()"
              :class="errWidth ? 'adm-input !border-red-400' : 'adm-input'" class="adm-input">
            <p x-show="errWidth" x-text="errWidth" class="mt-1 text-xs font-medium text-red-500"></p>
          </div>
          <div>
            <label class="adm-label">Image Height (px)</label>
            <input type="number" name="height" x-model="form.height" min="1" :max="MAX" @input="checkDims()"
              :class="errHeight ? 'adm-input !border-red-400' : 'adm-input'" class="adm-input">
            <p x-show="errHeight" x-text="errHeight" class="mt-1 text-xs font-medium text-red-500"></p>
          </div>
        </div>
        <div x-show="dimsMsg"
          :class="dimsOk ? 'border-green-200 bg-green-50 text-green-700' : 'border-red-200 bg-red-50 text-red-600'"
          class="-mt-2 rounded-lg border px-3 py-2 text-[12px] leading-relaxed" x-html="dimsMsg"></div>
        <div>
          <label class="adm-label">Button Text</label>
          <input type="text" name="button_text" x-model="form.button_text" placeholder="Shop Now" class="adm-input">
          @error('button_text') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>
        <div>
          <label class="adm-label">Button URL</label>
          <input type="text" name="button_url" x-model="form.button_url" placeholder="/shop or https://example.com" class="adm-input">
          @error('button_url') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>
        <div>
          <label class="adm-label" x-text="editingId ? 'Desktop Image (leave empty to keep current)' : 'Desktop Image *'"></label>
          <input type="file" name="desktop_image" accept="image/jpeg,image/png,image/webp,image/svg+xml" :required="!editingId" @change="previewFile($event, 'new')" class="adm-input">
          <template x-if="editingId && form.desktop_image && !newPreview">
            <div class="mt-2 h-16 w-32 overflow-hidden rounded-lg border border-gray-200 bg-gray-50">
              <img :src="'{{ asset('storage/') }}/' + form.desktop_image" class="h-full w-full object-cover">
            </div>
          </template>
          <template x-if="newPreview">
            <div class="mt-2 overflow-hidden rounded-lg border border-gray-200 bg-gray-50 p-2">
              <div class="relative" :style="(form.width && form.height) ? 'aspect-ratio: ' + form.width + ' / ' + form.height : ''">
                <img :src="newPreview" class="h-full w-full object-contain rounded-md">
              </div>
              <p class="mt-1.5 text-[11px] adm-text-muted">
                Selected file: <span x-text="newPreviewDims ? newPreviewDims[0] + ' × ' + newPreviewDims[1] + 'px' : '…'"></span>
                <template x-if="newPreview && newPreviewDims">
                  <span>
                    ·
                    <template x-if="!form.width || !form.height">
                      <strong class="text-green-600">full image will be kept · auto-adjusts on the site</strong>
                    </template>
                    <template x-else-if="newPreviewDims[0] !== +form.width || newPreviewDims[1] !== +form.height">
                      <strong class="text-amber-600">will be auto-cropped to <span x-text="form.width + '×' + form.height"></span></strong>
                    </template>
                    <template x-else>
                      <strong class="text-green-600">already the exact size</strong>
                    </template>
                  </span>
                </template>
              </p>
            </div>
          </template>
          @error('desktop_image') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>
        <div class="flex gap-6">
          <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" x-model="form.is_active" class="accent-forest">
            Active
          </label>
          <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="show_text" value="1" x-model="form.show_text" class="accent-forest">
            Show Text on Banner
          </label>
        </div>
        <p class="text-[11px] adm-text-muted -mt-2">When text is hidden, clicking the image redirects to the Button URL.</p>
        <div class="adm-modal-footer">
          <button type="button" @click="showModal = false" class="adm-btn-outline">Cancel</button>
          <button type="submit" class="adm-btn-primary" x-text="editingId ? 'Update Banner' : 'Create Banner'"></button>
        </div>
      </form>
    </div>
  </div>

</div>

<script>
function bannerManager() {
  const recommended = {
    hero: [1600, 500],
    strip: [1200, 150],
    category_top: [1200, 220],
    promotional: [1200, 400],
  };
  return {
    showModal: false,
    editingId: null,
    newPreview: '',
    newPreviewDims: null,
    form: {
      title: '',
      subtitle: '',
      placement: 'hero',
      width: '',
      height: '',
      sort_order: 0,
      button_text: '',
      button_url: '',
      desktop_image: '',
      is_active: true,
      show_text: true,
    },
    applyRecommended() {
      const r = recommended[this.form.placement] || recommended.promotional;
      // Blank by default so the full uploaded image is kept (auto-fit on the site).
      // Only crop when the admin enters explicit Width & Height.
      this.form.width = '';
      this.form.height = '';
      this.checkDims();
    },
    MAX: 4000,
    errWidth: '',
    errHeight: '',
    dimsMsg: '',
    dimsOk: true,
    checkDims() {
      const MAX = this.MAX;
      const hasW = this.form.width !== '' && this.form.width !== null;
      const hasH = this.form.height !== '' && this.form.height !== null;
      const w = hasW ? Number(this.form.width) : null;
      const h = hasH ? Number(this.form.height) : null;
      const validNum = (v) => Number.isInteger(v) && v >= 1;
      const tooBig = (v) => validNum(v) && v > MAX;
      const isNum = (v) => hasW && v !== null && v !== '' && !Number.isNaN(v);

      let wMsg = '', hMsg = '', wOk = true, hOk = true, dimsOk = true;

      if (hasW) {
        if (!isNum(w) || !validNum(w)) { wMsg = 'Enter a whole number ≥ 1'; wOk = false; }
        else if (tooBig(w)) { wMsg = 'Too large — maximum ' + MAX + 'px'; wOk = false; }
      }
      if (hasH) {
        if (!isNum(h) || !validNum(h)) { hMsg = 'Enter a whole number ≥ 1'; hOk = false; }
        else if (tooBig(h)) { hMsg = 'Too large — maximum ' + MAX + 'px'; hOk = false; }
      }

      if (hasW !== hasH) {
        dimsMsg = 'Enter <strong>both</strong> Width and Height for an exact crop, or leave <strong>both blank</strong> to keep the full image.';
        dimsOk = false;
      } else if (!hasW && !hasH) {
        dimsMsg = 'Keeping the <strong>full image</strong> — the site auto-adjusts the banner to your image, nothing is cropped.';
      } else if (wOk && hOk) {
        const r = recommended[this.form.placement] || recommended.promotional;
        if (w === r[0] && h === r[1]) {
          dimsMsg = '<strong class="text-green-600">Perfect</strong> — that is the ideal size for this placement.';
        } else {
          dimsMsg = 'Will be <strong>cropped to ' + w + '×' + h + 'px</strong> · recommended <strong>' + r[0] + '×' + r[1] + 'px</strong> for ' + this.form.placement + '.';
        }
      } else {
        dimsOk = false;
      }

      this.errWidth = wMsg;
      this.errHeight = hMsg;
      this.dimsOk = dimsOk && wOk && hOk;
      this.dimsMsg = dimsMsg;
    },
    previewFile(ev) {
      const f = ev.target.files && ev.target.files[0];
      if (!f) { this.newPreview = ''; this.newPreviewDims = null; return; }
      const reader = new FileReader();
      reader.onload = (e) => {
        this.newPreview = e.target.result;
        const img = new Image();
        img.onload = () => { this.newPreviewDims = [img.naturalWidth, img.naturalHeight]; };
        img.src = e.target.result;
      };
      reader.readAsDataURL(f);
    },
    openCreate() {
      this.editingId = null;
      this.newPreview = '';
      this.newPreviewDims = null;
      this.form = { title: '', subtitle: '', placement: 'hero', width: '', height: '', sort_order: 0, button_text: '', button_url: '', desktop_image: '', is_active: true, show_text: true };
      this.checkDims();
      this.showModal = true;
    },
    openEdit(banner) {
      this.editingId = banner.id;
      this.newPreview = '';
      this.newPreviewDims = null;
      this.form = {
        title: banner.title || '',
        subtitle: banner.subtitle || '',
        placement: banner.placement || 'hero',
        width: banner.width || '',
        height: banner.height || '',
        sort_order: banner.sort_order || 0,
        button_text: banner.button_text || '',
        button_url: banner.button_url || '',
        desktop_image: banner.desktop_image || '',
        is_active: banner.is_active,
        show_text: banner.show_text !== false,
      };
      this.checkDims();
      this.showModal = true;
    }
  }
}
</script>
@endsection
