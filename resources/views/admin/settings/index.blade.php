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

            @if(str_starts_with($s->key, 'focus_') || $s->key === 'welcome')
              @php $tabHint = $s->key === 'welcome' ? 'All / Ghee / Oils / Atta / Combos / Deal' : ($s->key === 'focus_oils' ? 'Groundnut / Mustard / Sunflower / Olive / Coconut / Sesame' : 'Gir / Desi Cow / Buffalo / Combo'); @endphp
              <div class="sm:col-span-full" x-data="sectionList('tabs', @js($cfg['tabs'] ?? []))" @input="sync()">
                <input type="hidden" data-listsync :name="'sections[{{ $s->id }}][tabs_json]'" :value="jsonValue">
                <label class="adm-label">Menu Tabs — {{ $tabHint }} <span class="font-normal text-gray-400">(each row = one tab on the homepage)</span></label>
                <div class="space-y-2">
                  <template x-for="(row, i) in rows" :key="i">
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                      <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-5">
                        <div><label class="text-[11px] font-semibold text-gray-500">Tab Name</label><input x-model="row.title" class="adm-input !py-1.5 text-xs" placeholder="e.g. Ghee"></div>
                        <div><label class="text-[11px] font-semibold text-gray-500">Show (type)</label>
                          <select x-model="row.type" class="adm-input !py-1.5 text-xs">
                            <option value="all">All products</option>
                            <option value="deal">Sale / deal products</option>
                            <option value="category">One category</option>
                            <option value="categories">Several categories</option>
                            <option value="keyword">Product name / keyword</option>
                          </select>
                        </div>
                        <div><label class="text-[11px] font-semibold text-gray-500">Value (name / category slug)</label><input x-model="row.value" class="adm-input !py-1.5 text-xs" placeholder="e.g. ghee"></div>
                        <div><label class="text-[11px] font-semibold text-gray-500">Icon path</label><input x-model="row.inactive_icon" class="adm-input !py-1.5 text-xs" placeholder="images/nav/…svg"></div>
                        <div class="flex items-end gap-1">
                          <input x-model="row.active_icon" class="adm-input !py-1.5 text-xs" placeholder="active icon (optional)">
                          <button type="button" @click="removeRow(i)" class="rounded-md bg-red-50 px-2 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-100">Remove</button>
                        </div>
                      </div>
                      <div class="mt-2 grid grid-cols-1 gap-2 border-t border-gray-200 pt-2 sm:grid-cols-2 lg:grid-cols-4">
                        <div><label class="text-[11px] font-semibold text-gray-400">Fallback: show if empty (optional)</label>
                          <select x-model="row.fallback_type" class="adm-input !py-1.5 text-xs">
                            <option value="">No fallback</option>
                            <option value="category">One category</option>
                            <option value="categories">Several categories</option>
                            <option value="keyword">Product keyword</option>
                          </select>
                        </div>
                        <div><label class="text-[11px] font-semibold text-gray-400">Fallback value (slug)</label><input x-model="row.fallback_value" class="adm-input !py-1.5 text-xs" placeholder="e.g. ghee"></div>
                        <div><label class="text-[11px] font-semibold text-gray-400">Fallback categories</label><input x-model="row.fallback_values" class="adm-input !py-1.5 text-xs" placeholder="ghee, oil, atta (comma separated)"></div>
                      </div>
                    </div>
                  </template>
                </div>
                <div class="mt-2 flex gap-2">
                  <button type="button" @click="addRow()" class="rounded-md bg-forest-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-forest-700">+ Add Tab</button>
                  <p class="self-center text-[11px] text-gray-400">Icon path example <code>images/nav/nav-ghee.svg</code> (optional). Save to show changes live.</p>
                </div>
              </div>
            @endif

            @if($s->key === 'hero')
              <div class="sm:col-span-full rounded-lg bg-cream-50 border border-cream-200 p-3 text-sm text-charcoal-600">
                The hero (top slider) is managed from <a href="{{ route('admin.banners.index') }}" class="font-semibold text-forest-700 underline">Admin → Marketing → Banners</a>. Add or edit a banner with placement <strong>Hero</strong> to change the slides, plus their title / subtitle / button text.
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
              <div class="sm:col-span-full" x-data="sectionList('badges', @js($cfg['items'] ?? []))" @input="sync()">
                <input type="hidden" data-listsync :name="'sections[{{ $s->id }}][items]'" :value="jsonValue">
                <label class="adm-label">Trust Badges <span class="font-normal text-gray-400">(the "Why Choose Us" points)</span></label>
                <div class="space-y-2">
                  <template x-for="(row, i) in rows" :key="i">
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                      <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-4">
                        <div><label class="text-[11px] font-semibold text-gray-500">Badge Title</label><input x-model="row.title" class="adm-input !py-1.5 text-xs" placeholder="e.g. Native Sourcing"></div>
                        <div><label class="text-[11px] font-semibold text-gray-500">Icon (small icon name)</label><input x-model="row.icon" class="adm-input !py-1.5 text-xs" placeholder="e.g. leaf / shield-check / users"></div>
                        <div class="sm:col-span-2"><label class="text-[11px] font-semibold text-gray-500">Description</label><input x-model="row.text" class="adm-input !py-1.5 text-xs" placeholder="Short description shown under the title"></div>
                      </div>
                      <div class="mt-1 flex justify-end"><button type="button" @click="removeRow(i)" class="rounded-md bg-red-50 px-2 py-1 text-xs font-semibold text-red-600 hover:bg-red-100">Remove</button></div>
                    </div>
                  </template>
                </div>
                <div class="mt-2"><button type="button" @click="addRow()" class="rounded-md bg-forest-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-forest-700">+ Add Badge</button></div>
              </div>
            @endif

            @if(in_array($s->key, ['native_ingredients','quality']))
              <div class="sm:col-span-full" x-data="sectionList('carousel', @js($cfg['carousel'] ?? []))" @input="sync()">
                <input type="hidden" data-listsync :name="'sections[{{ $s->id }}][carousel_json]'" :value="jsonValue">
                <label class="adm-label">Carousel Images <span class="font-normal text-gray-400">(the rotating images in this section)</span></label>
                <div class="space-y-2">
                  <template x-for="(row, i) in rows" :key="i">
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                      <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-5">
                        <div class="sm:col-span-2"><label class="text-[11px] font-semibold text-gray-500">Image path</label><input x-model="row.image" class="adm-input !py-1.5 text-xs" placeholder="sections/native1.jpg"></div>
                        <div><label class="text-[11px] font-semibold text-gray-500">Alt text</label><input x-model="row.alt" class="adm-input !py-1.5 text-xs" placeholder="Short label"></div>
                        <div><label class="text-[11px] font-semibold text-gray-500">Link (optional)</label><input x-model="row.url" class="adm-input !py-1.5 text-xs" placeholder="/category or https://"></div>
                        <div class="flex items-end gap-1">
                          <span class="flex-1 text-[10px] text-gray-400">Live preview<br><img :src="row.image ? '/storage/' + row.image.replace(/^\/?storage\//,'') : ''" class="mt-1 h-10 w-16 rounded object-cover ring-1 ring-gray-200"></span>
                          <button type="button" @click="removeRow(i)" class="rounded-md bg-red-50 px-2 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-100">Remove</button>
                        </div>
                      </div>
                    </div>
                  </template>
                </div>
                <div class="mt-2"><button type="button" @click="addRow()" class="rounded-md bg-forest-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-forest-700">+ Add Image</button></div>
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

/* ── Visual list editors for Homepage Sections (no JSON needed) ────────── */
function blankRow(schema) {
  if (schema === 'badges')  return { icon: 'leaf', title: '', text: '' };
  if (schema === 'tabs')    return { title: '', key: '', type: 'keyword', value: '', inactive_icon: '', active_icon: '', fallback_type: '', fallback_value: '', fallback_values: '' };
  if (schema === 'carousel'|| schema === 'slides') return { image: '', alt: '', url: '' };
  return {};
}
function rowFromObj(schema, o) {
  o = o || {};
  if (schema === 'badges')  return { icon: o.icon || 'leaf', title: o.title || '', text: o.text || '' };
  if (schema === 'tabs')    return {
    title: o.title || '', key: o.key || (o.title||'').toLowerCase().replace(/\s+/g,'-'), type: o.type || 'keyword',
    value: o.value || '', inactive_icon: o.inactive_icon || '', active_icon: o.active_icon || '',
    fallback_type: ((o.fallback||{}).type) || '', fallback_value: (o.fallback||{}).value || '',
    fallback_values: Array.isArray((o.fallback||{}).values) ? (o.fallback).values.join(', ') : '',
  };
  if (schema === 'carousel'|| schema === 'slides') return { image: o.image || '', alt: o.alt || '', url: o.url || '' };
  return {};
}
function sectionList(schema, initial) {
  let rows = [];
  try {
    const arr = typeof initial === 'string' ? JSON.parse(initial) : initial;
    if (Array.isArray(arr)) rows = arr.map(i => rowFromObj(schema, i));
  } catch (e) {}
  if (!rows.length) rows = [blankRow(schema)];
  const serialize = () => rows.map(r => {
    const out = {};
    for (const k in r) if (r[k] !== '') out[k] = r[k];
    delete out.fallback_type; delete out.fallback_value; delete out.fallback_values;
    if (schema === 'tabs' && (r.fallback_type || r.fallback_value || r.fallback_values)) {
      if (r.fallback_values) {
        out.fallback = { type: r.fallback_type || 'categories', values: r.fallback_values.split(',').map(s => s.trim()).filter(Boolean) };
      } else {
        out.fallback = { type: r.fallback_type || 'category', value: r.fallback_value || '' };
      }
    }
    return out;
  }).filter(r => Object.keys(r).length);
  return {
    schema, rows,
    get jsonValue() { return JSON.stringify(serialize()); },
    addRow() { this.rows.push(blankRow(this.schema)); },
    removeRow(i) { this.rows.splice(i, 1); if (!this.rows.length) this.rows.push(blankRow(this.schema)); },
    move(i, d) { const j = i + d; if (j < 0 || j >= this.rows.length) return; const t = this.rows[i]; this.rows[i] = this.rows[j]; this.rows[j] = t; },
    sync() { const h = this.$el && this.$el.querySelector('input[type="hidden"][data-listsync]'); if (h) h.value = this.jsonValue; },
  };
}
</script>
@endpush
