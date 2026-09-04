@extends('layouts.admin', ['title' => 'Banners'])

@section('content')
<div class="space-y-4" x-data="bannerManager()" @click="onGridClick($event)">

  <div class="flex flex-wrap items-center justify-between gap-4">
    <h2 class="adm-page-title">All Banners <span id="banner-count" class="adm-page-count">{{ $banners->count() }}</span></h2>
    <button @click="openCreate()" class="adm-btn-primary">+ Add Banner</button>
  </div>

  @if(session('success'))
    <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
  @endif

  <div id="banners-grid" class="adm-grid-3">
    @include('admin.marketing.banners._grid', ['banners' => $banners])
  </div>

  {{-- Modal --}}
  <div x-show="showModal" x-cloak class="adm-modal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    <div class="adm-modal-card" @click.away="showModal = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
      <div class="adm-modal-header">
        <h3 class="adm-modal-title" x-text="editingId ? 'Edit Banner' : 'Create Banner'"></h3>
        <button @click="showModal = false" class="adm-btn-ghost text-lg">&times;</button>
      </div>
      <form :action="editingId ? '{{ url('admin/banners') }}/' + editingId : '{{ route('admin.banners.store') }}'" method="POST" enctype="multipart/form-data" @submit.prevent="save($event)" class="adm-modal-body space-y-4">
        @csrf
        <template x-if="editingId"><input type="hidden" name="_method" value="PATCH"></template>

        <div>
          <label class="adm-label">Title *</label>
          <input type="text" name="title" x-model="form.title" required class="adm-input">
          <template x-if="errors.title"><p class="mt-1 text-xs text-red-500" x-text="errors.title"></p></template>
        </div>
        <div>
          <label class="adm-label">Subtitle</label>
          <input type="text" name="subtitle" x-model="form.subtitle" class="adm-input">
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <label class="adm-label">Placement *</label>
            <select name="placement" x-model="form.placement" @change="computeSizeMsg()" class="adm-input">
              <option value="hero">Hero</option>
              <option value="strip">Strip</option>
              <option value="category_top">Category Top</option>
              <option value="promotional">Promotional</option>
            </select>
          </div>
          <div>
            <label class="adm-label">Sort Order</label>
            <input type="number" name="sort_order" x-model="form.sort_order" min="0" class="adm-input">
          </div>
        </div>
        <div>
          <label class="adm-label">Button Text</label>
          <input type="text" name="button_text" x-model="form.button_text" placeholder="Shop Now" class="adm-input">
        </div>
        <div>
          <label class="adm-label">Button URL</label>
          <input type="text" name="button_url" x-model="form.button_url" placeholder="/shop or https://example.com" class="adm-input">
        </div>
        <div>
          <label class="adm-label">
            Desktop Image *
            <span class="font-normal" x-text="'· Required ' + sizeText + ' for ' + placementLabel"></span>
          </label>
          <input type="file" name="desktop_image" accept="image/jpeg,image/png,image/webp,image/svg+xml" :required="!editingId" @change="previewFile($event)" class="adm-input">
          <template x-if="editingId && form.desktop_image && !newPreview">
            <div class="mt-2 h-16 w-32 overflow-hidden rounded-lg border border-gray-200 bg-gray-50">
              <img :src="'{{ asset('storage/') }}/' + form.desktop_image" class="h-full w-full object-cover">
            </div>
          </template>
          <template x-if="newPreview">
            <div class="mt-2 overflow-hidden rounded-lg border border-gray-200 bg-gray-50 p-2">
              <div class="relative aspect-video">
                <img :src="newPreview" class="h-full w-full object-contain rounded-md">
              </div>
              <p x-show="sizeMsg" class="mt-1.5 text-[11px] leading-relaxed"
                :class="sizeOk ? 'text-green-600' : 'text-amber-600'" x-html="sizeMsg"></p>
              <strong x-show="!sizeOk" class="mt-1 block text-[11px] font-semibold text-amber-600">The selected area will be auto-cropped — the image will not be stretched or blurred.</strong>
            </div>
          </template>
          <template x-if="errors.desktop_image"><p class="mt-1 text-xs text-red-500" x-text="errors.desktop_image"></p></template>
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
          <button type="submit" class="adm-btn-primary" x-text="editingId ? 'Update Banner' : 'Create Banner'" :disabled="busy"></button>
        </div>
      </form>
    </div>
  </div>

  {{-- Toast --}}
  <div id="banner-toast" class="fixed bottom-6 right-6 z-[100]"></div>

</div>

<script>
function bannerManager() {
  const PLACEMENT_SIZES = {
    hero:          [1600, 500],
    strip:         [1200, 150],
    category_top:  [1200, 220],
    promotional:   [1200, 400],
  };
  const PLACEMENT_LABEL = {
    hero: 'Hero',
    strip: 'Strip',
    category_top: 'Category Top',
    promotional: 'Promotional',
  };
  const csrf = '{{ csrf_token() }}';

  return {
    showModal: false,
    busy: false,
    editingId: null,
    newPreview: '',
    newPreviewDims: null,
    sizeMsg: '',
    sizeOk: true,
    errors: {},
    form: {
      title: '',
      subtitle: '',
      placement: 'hero',
      sort_order: 0,
      button_text: '',
      button_url: '',
      desktop_image: '',
      is_active: true,
      show_text: true,
    },
    get sizeText() {
      const [w, h] = PLACEMENT_SIZES[this.form.placement] || PLACEMENT_SIZES.promotional;
      return w + '×' + h;
    },
    get placementLabel() {
      return PLACEMENT_LABEL[this.form.placement] || 'Hero';
    },
    computeSizeMsg() {
      if (!this.newPreviewDims) {
        this.sizeMsg = '';
        this.sizeOk = true;
        return;
      }
      const [reqW, reqH] = PLACEMENT_SIZES[this.form.placement] || PLACEMENT_SIZES.promotional;
      const [aw, ah] = this.newPreviewDims;
      if (aw === reqW && ah === reqH) {
        this.sizeMsg = 'Your image is <strong>' + aw + '×' + ah + '</strong> — exactly the required ' + reqW + '×' + reqH + '. Perfect!';
        this.sizeOk = true;
      } else if (aw >= reqW && ah >= reqH) {
        this.sizeMsg = 'Your image is <strong>' + aw + '×' + ah + '</strong> — big enough for ' + reqW + '×' + reqH + '. Will be auto-cropped to fit perfectly.';
        this.sizeOk = true;
      } else {
        this.sizeMsg = 'Your image is <strong>' + aw + '×' + ah + '</strong> — smaller than required ' + reqW + '×' + reqH + '. Please use a larger, higher-quality image.';
        this.sizeOk = false;
      }
    },
    previewFile(ev) {
      const f = ev.target.files && ev.target.files[0];
      if (!f) {
        this.newPreview = '';
        this.newPreviewDims = null;
        this.sizeMsg = '';
        this.sizeOk = true;
        return;
      }
      if (f.size > 4096 * 1024) {
        this.newPreview = '';
        this.newPreviewDims = null;
        this.sizeOk = false;
        this.sizeMsg = 'Your image is <strong>' + (Math.round(f.size / 1048576 * 10) / 10) + ' MB</strong> — over the 4 MB upload limit. Please use a smaller image.';
        return;
      }
      const reader = new FileReader();
      reader.onload = (e) => {
        this.newPreview = e.target.result;
        const img = new Image();
        img.onload = () => {
          this.newPreviewDims = [img.naturalWidth, img.naturalHeight];
          this.computeSizeMsg();
        };
        img.src = e.target.result;
      };
      reader.readAsDataURL(f);
    },
    resetForm() {
      this.editingId = null;
      this.errors = {};
      this.newPreview = '';
      this.newPreviewDims = null;
      this.sizeMsg = '';
      this.sizeOk = true;
      this.form = { title: '', subtitle: '', placement: 'hero', sort_order: 0, button_text: '', button_url: '', desktop_image: '', is_active: true, show_text: true };
    },
    openCreate() {
      this.resetForm();
      this.showModal = true;
    },
    openEdit(banner) {
      this.editingId = banner.id;
      this.errors = {};
      this.newPreview = '';
      this.newPreviewDims = null;
      this.sizeMsg = '';
      this.sizeOk = true;
      this.form = {
        title: banner.title || '',
        subtitle: banner.subtitle || '',
        placement: banner.placement || 'hero',
        sort_order: banner.sort_order || 0,
        button_text: banner.button_text || '',
        button_url: banner.button_url || '',
        desktop_image: banner.desktop_image || '',
        is_active: banner.is_active,
        show_text: banner.show_text !== false,
      };
      this.showModal = true;
    },
    onGridClick(ev) {
      const btn = ev.target.closest('[data-action]');
      if (!btn) return;
      const act = btn.getAttribute('data-action');
      if (act === 'edit') {
        this.openEdit(JSON.parse(btn.getAttribute('data-banner')));
        return;
      }
      const id = btn.getAttribute('data-id');
      if (act === 'toggle') { this.toggleBanner(id); }
      else if (act === 'delete') { this.removeBanner(id); }
    },
    renderGrid(d) {
      const grid = document.getElementById('banners-grid');
      if (grid && d.grid) grid.innerHTML = d.grid;
      const count = document.getElementById('banner-count');
      if (count && d.count !== undefined) count.textContent = d.count;
    },
    toast(msg, isErr) {
      const box = document.getElementById('banner-toast');
      if (!box) return;
      const el = document.createElement('div');
      el.className = 'mb-2 rounded-xl border px-4 py-3 text-sm shadow-lg transition ' + (isErr ? 'border-red-200 bg-red-50 text-red-700' : 'border-green-200 bg-green-50 text-green-700');
      el.textContent = msg;
      box.appendChild(el);
      setTimeout(() => { el.classList.add('opacity-0'); setTimeout(() => el.remove(), 300); }, 3200);
    },
    async save(ev) {
      const form = ev.target;
      this.busy = true;
      this.errors = {};
      try {
        const res = await fetch(form.action, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, body: new FormData(form) });
        const data = await res.json().catch(() => ({ ok: false, message: 'Something went wrong. Please try again.' }));
        if (data.ok) {
          this.renderGrid(data);
          this.toast(data.message);
          this.resetForm();
          this.showModal = false;
        } else if (data.errors) {
          this.errors = Object.fromEntries(Object.entries(data.errors).map(([k, v]) => [k, v[0] || 'Invalid value.']));
          this.toast(Object.values(this.errors)[0] || 'Please fix the highlighted fields.', true);
        } else {
          this.toast(data.message || 'Could not save.', true);
        }
      } catch (e) {
        this.toast('Network error. Please retry.', true);
      } finally {
        this.busy = false;
      }
    },
    async toggleBanner(id) {
      try {
        const res = await fetch('/admin/banners/' + id + '/toggle', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' } });
        const data = await res.json().catch(() => ({ ok: false, message: 'Something went wrong.' }));
        if (data.ok) { this.renderGrid(data); this.toast(data.message); }
        else { this.toast(data.message, true); }
      } catch (e) { this.toast('Network error.', true); }
    },
    async removeBanner(id) {
      if (!confirm('Delete this banner?')) return;
      try {
        const res = await fetch('/admin/banners/' + id, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' } });
        const data = await res.json().catch(() => ({ ok: false, message: 'Something went wrong.' }));
        if (data.ok) { this.renderGrid(data); this.toast(data.message); }
        else { this.toast(data.message, true); }
      } catch (e) { this.toast('Network error.', true); }
    },
  };
}
</script>
@endsection