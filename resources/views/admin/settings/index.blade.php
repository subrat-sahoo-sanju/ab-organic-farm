@extends('layouts.admin', ['title' => 'Settings'])

@section('content')
<div class="space-y-4" x-data="{ activeTab: '{{ array_key_first($sections) }}' }">

  <div class="flex flex-wrap gap-2">
    @foreach($sections as $key => $section)
      <button @click="activeTab = '{{ $key }}'" :class="activeTab === '{{ $key }}' ? 'adm-pill-active' : 'adm-pill'">{{ $section['title'] }}</button>
    @endforeach
    <button @click="activeTab = 'sections'" :class="activeTab === 'sections' ? 'adm-pill-active' : 'adm-pill'">Homepage Sections</button>
  </div>

  <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    <div class="adm-section p-6 space-y-6">
      @foreach($sections as $key => $section)
        <div x-show="activeTab === '{{ $key }}'" x-cloak>
          <h3 class="adm-section-title">{{ $section['title'] }}</h3>
          <div class="adm-divider"></div>
          <p class="adm-text-secondary text-sm mb-4">These values appear on the customer-facing site and update live when you save.</p>
          <div class="space-y-4">
            @foreach($section['keys'] as $field)
              @php $value = $current[$field['key']] ?? ''; @endphp
              <div>
                <label class="adm-label">{{ $field['label'] }}</label>

                @if(($field['type'] ?? 'text') === 'image')
                  @php $fileField = str_replace('.', '__', $field['key']); @endphp
                  <div class="space-y-2">
                    @if($value)
                      <div class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-3">
                        <img src="{{ asset('storage/'.$value) }}" alt="Current {{ $field['label'] }}" class="h-12 w-auto max-w-[200px] object-contain rounded ring-1 ring-gray-100">
                        <span class="text-xs adm-text-secondary break-all">{{ $value }}</span>
                      </div>
                    @endif
                    <input type="hidden" name="{{ $fileField }}_existing" value="{{ $value }}">
                    <input type="file" name="{{ $fileField }}" accept="image/jpeg,image/png,image/webp,image/svg+xml" class="adm-input file:mr-3 file:rounded-md file:border-0 file:bg-forest file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white hover:file:bg-forest-dark">
                    <p class="text-xs adm-text-secondary">Upload a new logo and every page of the storefront updates automatically. Leave empty to keep the current one.</p>
                  </div>

                @elseif(($field['type'] ?? 'text') === 'textarea')
                  <textarea name="{{ $field['key'] }}" rows="3" class="adm-input">{{ $value }}</textarea>

                @elseif(($field['type'] ?? 'text') === 'number')
                  <input type="number" name="{{ $field['key'] }}" value="{{ $value }}" min="0" class="adm-input">

                @elseif(($field['type'] ?? 'text') === 'boolean')
                  <label class="relative inline-flex cursor-pointer items-center gap-2">
                    <input type="hidden" name="{{ $field['key'] }}" value="0">
                    <input type="checkbox" name="{{ $field['key'] }}" value="1" {{ $value ? 'checked' : '' }} class="accent-forest">
                    <span class="text-xs adm-text-secondary">Enable</span>
                  </label>

                @elseif(($field['type'] ?? 'text') === 'json')
                  <div
                    x-data="jsonEditor('{{ $field['json_schema'] }}', @js($value))"
                    x-init="$nextTick(() => sync())"
                    x-effect="sync()"
                    @input="sync()"
                  >
                    <input type="hidden" :name="'{{ $field['key'] }}'" :value="jsonValue">
                    <div class="space-y-2">
                      <template x-for="(row, idx) in rows" :key="idx">
                        <div class="rounded-lg border border-gray-200 bg-white p-3">
                          <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 space-y-2">
                              <template x-if="schema === 'tags'">
                                <input type="text" x-model="row.value" class="adm-input" placeholder="Tag text">
                              </template>

                              <template x-if="schema === 'link_list'">
                                <div class="space-y-2">
                                  <input type="text" x-model="row.label" class="adm-input" placeholder="Link label">
                                  <input type="text" x-model="row.url" class="adm-input" placeholder="Link URL (/categories/rice-grains-flour)">
                                </div>
                              </template>

                              <template x-if="schema === 'rewards'">
                                <div class="space-y-2">
                                  <input type="text" x-model="row.title" class="adm-input" placeholder="Earn item title (e.g. Sign up)">
                                  <input type="text" x-model="row.points" class="adm-input" placeholder="Points (e.g. 50 pts)">
                                </div>
                              </template>

                              <template x-if="schema === 'trust_pills'">
                                <div class="space-y-2">
                                  <select x-model="row.icon" class="adm-input">
                                    <option value="shield-check">Shield Check</option>
                                    <option value="flask-conical">Flask / Lab</option>
                                    <option value="truck">Truck</option>
                                    <option value="leaf">Leaf</option>
                                    <option value="sparkles">Sparkles</option>
                                    <option value="heart">Heart</option>
                                  </select>
                                  <input type="text" x-model="row.text" class="adm-input" placeholder="Pill text (e.g. 100% Certified Organic)">
                                </div>
                              </template>

                              <template x-if="schema === 'nav_menu'">
                                <div class="space-y-2">
                                  <input type="text" x-model="row.label" class="adm-input" placeholder="Label (e.g. A2 Ghee)">
                                  <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                                    <input type="text" x-model="row.icon" class="adm-input" placeholder="Icon (e.g. nav-ghee / nav-oils / nav-atta)">
                                    <input type="text" x-model="row.url" class="adm-input" placeholder="URL (/categories/oils-ghee)">
                                  </div>
                                  <label class="flex items-center gap-2 text-xs text-gray-500">
                                    <input type="checkbox" x-model="row.highlight" class="accent-forest"> Highlight (rainbow text, e.g. Hot Deals)
                                  </label>
                                  <details class="rounded-md border border-gray-100 bg-gray-50 p-2">
                                    <summary class="cursor-pointer text-xs font-semibold text-gray-500">Submenu items (optional)</summary>
                                    <template x-for="(child, ci) in (row.children || [])" :key="ci">
                                      <div class="mt-2 flex items-center gap-2">
                                        <div class="flex-1 space-y-1">
                                          <input type="text" x-model="child.label" class="adm-input !py-1.5 text-xs" placeholder="Child label (e.g. Superfoods)">
                                          <input type="text" x-model="child.url" class="adm-input !py-1.5 text-xs" placeholder="Child URL">
                                        </div>
                                        <button type="button" @click="row.children.splice(ci, 1)" class="px-1 text-red-500">&times;</button>
                                      </div>
                                    </template>
                                    <button type="button" @click="addChild(row)" class="mt-2 text-xs font-semibold text-forest-700 hover:underline">+ Add submenu item</button>
                                  </details>
                                </div>
                              </template>

                              <template x-if="schema === 'nav_items'">
                                <div class="space-y-2">
                                  <input type="text" x-model="row.label" class="adm-input" placeholder="Label (Home / Deal / Combos / Account)">
                                  <input type="text" x-model="row.icon" class="adm-input" placeholder="Icon (home / leaf / badge-percent / shopping-bag / user)">
                                  <input type="text" x-model="row.url" class="adm-input" placeholder="URL">
                                </div>
                              </template>

                              <template x-if="schema === 'feat_items'">
                                <div class="space-y-2">
                                  <select x-model="row.icon" class="adm-input">
                                    <option value="leaf">Leaf</option>
                                    <option value="truck">Truck</option>
                                    <option value="hand_coins">Hand Coins</option>
                                    <option value="shield_check">Shield Check</option>
                                    <option value="sprout">Sprout</option>
                                    <option value="sparkles">Sparkles</option>
                                    <option value="recycle">Recycle</option>
                                    <option value="heart">Heart</option>
                                  </select>
                                  <input type="text" x-model="row.title" class="adm-input" placeholder="Box title">
                                  <input type="text" x-model="row.text" class="adm-input" placeholder="Box short text">
                                </div>
                              </template>

                              <template x-if="schema === 'promo_cards'">
                                <div class="space-y-2">
                                  <select x-model="row.color" class="adm-input">
                                    <option value="orange">Orange</option>
                                    <option value="green">Green</option>
                                    <option value="blue">Blue</option>
                                    <option value="forest">Forest</option>
                                  </select>
                                  <input type="text" x-model="row.badge" class="adm-input" placeholder="Badge (e.g. Limited Time)">
                                  <input type="text" x-model="row.title" class="adm-input" placeholder="Title (e.g. Flat 20% Off)">
                                  <input type="text" x-model="row.subtitle" class="adm-input" placeholder="Subtitle text">
                                  <input type="text" x-model="row.code" class="adm-input" placeholder="Coupon code (optional)">
                                  <input type="text" x-model="row.cta" class="adm-input" placeholder="Button text (e.g. Order Now)">
                                  <input type="text" x-model="row.link" class="adm-input" placeholder="Button link">
                                </div>
                              </template>
                            </div>
                            <button type="button" @click="removeRow(idx)" class="px-2 text-red-500 hover:text-red-700" title="Remove">&times;</button>
                          </div>
                        </div>
                      </template>
                    </div>
                    <button type="button" @click="addRow()" class="mt-2 rounded-md border border-dashed border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-500 hover:border-forest hover:text-forest">
                      + Add {{ $field['json_schema'] === 'tags' ? 'Tag' : 'Item' }}
                    </button>
                  </div>

                @else
                  <input type="text" name="{{ $field['key'] }}" value="{{ $value }}" class="adm-input">
                @endif
              </div>
            @endforeach
          </div>
        </div>
      @endforeach

      <div class="adm-divider"></div>
      <div class="flex justify-end">
        <button type="submit" class="adm-btn-primary">Save Settings</button>
      </div>
    </div>
  </form>

  {{-- ═══ HOMEPAGE SECTIONS MANAGER ═══ --}}
  <form action="{{ route('admin.settings.sections') }}" method="POST" enctype="multipart/form-data" x-show="activeTab === 'sections'" x-cloak>
    @csrf
    <div class="adm-section p-6 space-y-4">
      <h3 class="adm-section-title">Homepage Sections</h3>
      <div class="adm-divider"></div>
      <p class="adm-text-secondary text-sm mb-4">Toggle sections, reorder them, and edit their titles/subtitles. Product-count and focus-tab settings make every section dynamic.</p>

      @foreach($homepageSections as $idx => $s)
        @php $cfg = $s->config ?? []; @endphp
        <div class="rounded-xl border border-gray-200 bg-white p-4">
          <div class="flex flex-wrap items-start justify-between gap-3">
            <div class="flex items-center gap-3">
              <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-forest-50 text-xs font-extrabold text-forest-700">{{ $loop->iteration }}</span>
              <div>
                <p class="text-sm font-bold text-gray-800">{{ $s->key }} <span class="ml-1 rounded bg-cream-100 px-1.5 py-0.5 text-[10px] font-semibold text-charcoal-500">{{ $s->key }}</span></p>
                <label class="relative mt-1 inline-flex cursor-pointer items-center gap-2">
                  <input type="hidden" name="sections[{{ $s->id }}][visible]" value="0">
                  <input type="checkbox" name="sections[{{ $s->id }}][visible]" value="1" {{ $s->is_visible ? 'checked' : '' }} class="accent-forest">
                  <span class="text-xs font-medium {{ $s->is_visible ? 'text-forest-700' : 'text-gray-400' }}">{{ $s->is_visible ? 'Visible' : 'Hidden' }}</span>
                </label>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <label class="text-xs font-medium text-gray-500">Order</label>
              <input type="number" name="sections[{{ $s->id }}][sort_order]" value="{{ $s->sort_order }}" min="0" step="1" class="w-20 adm-input !py-1.5">
            </div>
          </div>

          <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2">
            <div>
              <label class="adm-label">Title</label>
              <input type="text" name="sections[{{ $s->id }}][title]" value="{{ $s->title }}" class="adm-input">
            </div>
            <div>
              <label class="adm-label">Subtitle</label>
              <input type="text" name="sections[{{ $s->id }}][subtitle]" value="{{ $s->subtitle }}" class="adm-input">
            </div>
          </div>

          <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <div>
              <label class="adm-label">Products Count</label>
              <input type="number" name="sections[{{ $s->id }}][product_count]" value="{{ $cfg['product_count'] ?? '' }}" min="1" max="24" class="adm-input">
            </div>

            @if(str_starts_with($s->key, 'focus_'))
              <div class="sm:col-span-3">
                <label class="adm-label">Focus Menu Tabs (JSON — {{ $s->key === 'focus_oils' ? 'Groundnut / Mustard / Sunflower / Olive / Coconut / Sesame' : 'Ghee' }} icon tabs)</label>
                <textarea name="sections[{{ $s->id }}][tabs_json]" rows="9" class="adm-input !font-mono !text-xs">{{ json_encode($cfg['tabs'] ?? [], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) }}</textarea>
                <p class="text-[11px] text-gray-400">
                  Same format as the Welcome menu: <code>[{"title":"Groundnut","key":"groundnut","type":"keyword","value":"groundnut","fallback":{"type":"category","value":"oils-ghee"},"active_icon":"images/nav/nav-groundnut-active.svg","inactive_icon":"images/nav/nav-groundnut.svg"}].</code> Blank = auto (oils/ghee keywords).
                </p>
              </div>
            @endif

            @if($s->key === 'welcome')
              <div class="sm:col-span-3">
                <label class="adm-label">Welcome Menu Tabs (JSON — All / Ghee / Oils / Atta / Combos / Deal / Superfoods)</label>
                <textarea name="sections[{{ $s->id }}][tabs_json]" rows="9" class="adm-input !font-mono !text-xs">{{ json_encode($cfg['tabs'] ?? '', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) }}</textarea>
                <p class="text-[11px] text-gray-400">
                  Format: <code>[{"title":"Ghee","key":"ghee","type":"keyword","value":"ghee","active_icon":"images/nav/nav-ghee-active.svg","inactive_icon":"images/nav/nav-ghee.svg"}].</code><br>
                  <code>type</code>: <code>all</code> | <code>deal</code> (sale items) | <code>category</code> (<code>value</code> = category slug) | <code>categories</code> (<code>values</code> = slug list) | <code>keyword</code> (<code>value</code> = name text). Optional <code>fallback</code> = same object, used when a tab has no products. Icon paths are relative to <code>public/</code>.
                </p>
              </div>
            @endif

            @if($s->key === 'app_download')
              <div>
                <label class="adm-label">Android Store URL</label>
                <input type="url" name="sections[{{ $s->id }}][android_url]" value="{{ $cfg['android_url'] ?? '' }}" class="adm-input">
              </div>
              <div>
                <label class="adm-label">iOS Store URL</label>
                <input type="url" name="sections[{{ $s->id }}][ios_url]" value="{{ $cfg['ios_url'] ?? '' }}" class="adm-input">
              </div>
            @endif

            @if($s->key === 'trust_badges')
              <div class="sm:col-span-2">
                <label class="adm-label">Trust Badges (JSON: [{"icon":"leaf","title":"...","text":"..."}])</label>
                <textarea name="sections[{{ $s->id }}][items]" rows="4" class="adm-input !font-mono !text-xs">{{ json_encode($cfg['items'] ?? [], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) }}</textarea>
              </div>
            @endif

            @if(in_array($s->key, ['native_ingredients','quality']))
              <div class="sm:col-span-2">
                <label class="adm-label">Carousel Images (JSON: [{"image":"sections/native1.jpg","url":"","alt":""}])</label>
                <textarea name="sections[{{ $s->id }}][carousel_json]" rows="5" class="adm-input !font-mono !text-xs">{{ json_encode($cfg['carousel'] ?? [], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) }}</textarea>
                <p class="text-[11px] text-gray-400">Portrait images, one per entry. Paths are relative to <code>storage/app/public/</code>.</p>
              </div>
            @endif
          </div>

          @if(in_array($s->key, ['native_ingredients','quality','logo_slider','trust_badges','app_download','focus_oils','focus_ghee']))
            <div class="mt-3 border-t border-gray-100 pt-3">
              <p class="text-xs font-bold uppercase tracking-wide text-gray-400 mb-2">Section Images</p>
              <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                @php $imgs = $cfg['images'] ?? []; @endphp
                <div>
                  <label class="adm-label">Desktop Image</label>
                  @if(!empty($imgs['desktop']))
                    <img src="{{ asset('storage/'.$imgs['desktop']) }}" class="mb-1 h-16 w-full rounded-lg object-cover">
                  @endif
                  <input type="file" name="sections[{{ $s->id }}][image_desktop]" class="adm-input !py-1.5 text-xs" accept="image/*">
                  <input type="hidden" name="sections[{{ $s->id }}][image_desktop_existing]" value="{{ $imgs['desktop'] ?? '' }}">
                </div>
                <div>
                  <label class="adm-label">Mobile Image</label>
                  @if(!empty($imgs['mobile']))
                    <img src="{{ asset('storage/'.$imgs['mobile']) }}" class="mb-1 h-16 w-full rounded-lg object-cover">
                  @endif
                  <input type="file" name="sections[{{ $s->id }}][image_mobile]" class="adm-input !py-1.5 text-xs" accept="image/*">
                  <input type="hidden" name="sections[{{ $s->id }}][image_mobile_existing]" value="{{ $imgs['mobile'] ?? '' }}">
                </div>
                <div>
                  <label class="adm-label">Alt / Custom</label>
                  <input type="text" name="sections[{{ $s->id }}][image_alt]" value="{{ $imgs['alt'] ?? '' }}" placeholder="Alt text" class="adm-input text-xs">
                </div>
              </div>
              <p class="mt-1 text-[11px] text-gray-400">Upload new images to replace the defaults. Leave blank to keep current.</p>
            </div>
          @endif
        </div>
      @endforeach

      <div class="adm-divider"></div>
      <div class="flex justify-end">
        <button type="submit" class="adm-btn-primary">Save Sections</button>
      </div>
    </div>
  </form>

</div>
@endsection

@push('scripts')
<script>
function jsonEditor(schema, initial) {
  let rows = [];
  if (schema === 'tags' || schema === 'link_list' || schema === 'feat_items' || schema === 'promo_cards' || schema === 'rewards' || schema === 'nav_items' || schema === 'nav_menu' || schema === 'trust_pills') {
    try {
      const arr = typeof initial === 'string' ? JSON.parse(initial) : initial;
      if (Array.isArray(arr)) {
        rows = arr.map(item => {
          if (typeof item === 'string') return { value: item };
          const obj = item || {};
          if (schema === 'link_list') return { label: obj.label || '', url: obj.url || '' };
          if (schema === 'feat_items') return { icon: obj.icon || 'leaf', title: obj.title || '', text: obj.text || '' };
          if (schema === 'rewards') return { title: obj.title || '', points: obj.points || '' };
          if (schema === 'trust_pills') return { text: obj.text || '', icon: obj.icon || 'shield-check' };
          if (schema === 'nav_items') return { label: obj.label || '', icon: obj.icon || '', url: obj.url || '' };
          if (schema === 'nav_menu') return {
            label: obj.label || '', icon: obj.icon || '', url: obj.url || '',
            highlight: !!obj.highlight,
            children: Array.isArray(obj.children) ? obj.children.map(c => ({ label: c.label || '', url: c.url || '' })) : [],
          };
          if (schema === 'promo_cards') return {
            color: obj.color || 'green', badge: obj.badge || '', title: obj.title || '',
            subtitle: obj.subtitle || '', code: obj.code || '', cta: obj.cta || '', link: obj.link || '',
          };
          return { value: obj };
        });
      }
    } catch (e) {}
  }
  if (rows.length === 0) rows = [emptyRow(schema)];

  const serialize = () => {
    if (schema === 'tags') return rows.map(r => r.value || '').filter(v => v !== '');
    if (schema === 'link_list') return rows.map(r => ({ label: r.label, url: r.url })).filter(r => r.label || r.url);
    if (schema === 'feat_items') return rows.map(r => ({ icon: r.icon, title: r.title, text: r.text })).filter(r => r.title || r.text);
    if (schema === 'rewards') return rows.map(r => ({ title: r.title, points: r.points })).filter(r => r.title || r.points);
    if (schema === 'trust_pills') return rows.map(r => ({ text: r.text, icon: r.icon })).filter(r => r.text);
    if (schema === 'nav_items') return rows.map(r => ({ label: r.label, icon: r.icon, url: r.url })).filter(r => r.label || r.url);
    if (schema === 'nav_menu') return rows.map(r => ({
      label: r.label, icon: r.icon, url: r.url, highlight: r.highlight,
      children: (r.children || []).filter(c => c.label || c.url),
    })).filter(r => r.label || r.url || (r.children && r.children.length));
    if (schema === 'promo_cards') return rows.map(r => r).filter(r => r.title || r.subtitle);
    return rows;
  };

  return {
    schema,
    rows,
    get jsonValue() { return JSON.stringify(serialize()); },
    sync() {
      const h = this.$el && this.$el.querySelector('input[type="hidden"]');
      if (h) h.value = this.jsonValue;
    },
    addRow() { this.rows.push(emptyRow(this.schema)); },
    removeRow(idx) { this.rows.splice(idx, 1); },
    addChild(row) { if (!row.children) row.children = []; row.children.push({ label: '', url: '' }); },
  };
}
function emptyRow(schema) {
  if (schema === 'tags') return { value: '' };
  if (schema === 'link_list') return { label: '', url: '' };
  if (schema === 'feat_items') return { icon: 'leaf', title: '', text: '' };
  if (schema === 'rewards') return { title: '', points: '' };
  if (schema === 'trust_pills') return { text: '', icon: 'shield-check' };
  if (schema === 'nav_items') return { label: '', icon: '', url: '' };
  if (schema === 'nav_menu') return { label: '', icon: '', url: '', highlight: false, children: [] };
  if (schema === 'promo_cards') return { color: 'green', badge: '', title: '', subtitle: '', code: '', cta: '', link: '' };
  return {};
}
</script>
@endpush
