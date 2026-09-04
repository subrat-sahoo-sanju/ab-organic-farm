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

    <div class="adm-section p-6 space-y-6" x-show="activeTab !== 'sections'" x-cloak>
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
  <form action="{{ route('admin.settings.sections') }}" method="POST" enctype="multipart/form-data" x-show="activeTab === 'sections'" x-cloak novalidate>
    @csrf
    <div class="space-y-5">
      {{-- Pane header --}}
      <div class="adm-pane">
        <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-5">
          <div class="flex items-center gap-3">
            <div class="adm-pane-icon"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg></div>
            <div>
              <h3 class="adm-section-title !mb-0.5">Homepage Sections</h3>
              <p class="adm-text-secondary text-sm">Arrange your homepage — show or hide sections, set titles, and edit content visually. Your changes go live the moment you save.</p>
            </div>
          </div>
        </div>
      </div>

      @php
        $sectionMeta = [
          'hero' => ['Homepage Hero', 'Big rotating banner at the very top.', 'icon-mega'],
          'welcome' => ['Welcome Menu', 'Icon menu + product grid that greets shoppers.', 'icon-menu'],
          'trust_badges' => ['Why Choose Us', 'The trust points displayed under the menu.', 'icon-shield'],
          'native_ingredients' => ['Native Ingredients', 'Rotating carousel of native produce.', 'icon-leaf'],
          'quality' => ['Only Perfect', 'Rotating carousel for quality messaging.', 'icon-star'],
          'combos' => ['Healthy Combos', 'Horizontal rail of combo packs.', 'icon-cart'],
          'superfoods' => ['Superfoods', 'Horizontal rail of superfoods.', 'icon-spark'],
          'testimonials' => ['Customer Reviews', 'What customers say.', 'icon-heart'],
          'focus_oils' => ['Focus: Oils', 'Icon-menu product section for oils.', 'icon-drop'],
          'focus_ghee' => ['Focus: Ghee', 'Icon-menu product section for ghee.', 'icon-flame'],
          'recently_viewed' => ['Recently Viewed', 'Personalized, shows once visitor has history.', 'icon-clock'],
          'app_download' => ['Download App', 'App store download banner.', 'icon-phone'],
          'logo_slider' => ['Trusted By', 'Brand logo strip.', 'icon-flag'],
          'promotional_banners' => ['Promotions & Deals', 'Promotional banner strip.', 'icon-tag'],
          'best_sellers' => ['Best Sellers', 'Best-selling rail.', 'icon-trophy'],
          'trending' => ['Trending Now', 'Trending rail.', 'icon-fire'],
          'new_arrivals' => ['New Arrivals', 'New arrivals rail.', 'icon-box'],
        ];
      @endphp

      @foreach($homepageSections as $idx => $s)
        @php
          $cfg = $s->config ?? [];
          $open = $loop->first ? true : false;
          $meta = $sectionMeta[$s->key] ?? [Str::headline($s->key), 'Homepage section.', 'icon-leaf'];
        @endphp
        <div class="adm-pane" x-data="{ open: {{ $open ? 'true' : 'false' }} }">
          {{-- Card head --}}
          <div class="adm-pane-head">
            <div class="flex min-w-0 items-center gap-3">
              <div class="adm-pane-icon">
                @include('admin.settings._sec-icon', ['icon' => $meta[2], 'cls' => 'h-6 w-6'])
              </div>
              <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                  <span class="truncate text-[15px] font-bold text-gray-800 dark:text-white">{{ $meta[0] }}</span>
                  <span class="adm-chip {{ $s->is_visible ? 'adm-chip-live' : 'adm-chip-draft' }}">
                    <span class="adm-status-dot {{ $s->is_visible ? 'bg-emerald-500' : 'bg-indigo-400' }}"></span>
                    {{ $s->is_visible ? 'Live' : 'Hidden' }}
                  </span>
                </div>
                <p class="font-heading truncate text-xs text-gray-400">{{ $meta[1] }}</p>
              </div>
            </div>

            <div class="flex shrink-0 items-center gap-2">
              {{-- Reorder --}}
              @php
                $prevKey = $homepageSections->get($loop->index - 1)->id ?? null;
                $nextKey = $homepageSections->get($loop->index + 1)->id ?? null;
              @endphp
              <button type="button" title="Move up" onclick="swapSectionOrder({{ $s->id }}, {{ $prevKey ?? 'null' }}, 'sort_order')" class="grid h-8 w-8 place-items-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 hover:text-forest-600 dark:hover:bg-gray-700 {{ $prevKey === null ? 'opacity-30 pointer-events-none' : '' }}"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg></button>
              <button type="button" title="Move down" onclick="swapSectionOrder({{ $s->id }}, {{ $nextKey ?? 'null' }}, 'sort_order')" class="grid h-8 w-8 place-items-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 hover:text-forest-600 dark:hover:bg-gray-700 {{ $nextKey === null ? 'opacity-30 pointer-events-none' : '' }}"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg></button>

              {{-- Expand toggle --}}
              <button type="button" @click="open = !open" class="grid h-8 w-8 place-items-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 hover:text-forest-600 dark:hover:bg-gray-700">
                <svg x-show="!open" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                <svg x-show="open" x-cloak class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
              </button>
            </div>
          </div>

          {{-- Card body --}}
          <div x-show="open" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            {{-- Visibility + order --}}
            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 bg-gray-50/60 px-6 py-3 dark:border-gray-800 dark:bg-gray-900/40">
              <div class="flex items-center gap-3">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Show this section on the homepage</span>
                <label class="relative inline-flex cursor-pointer items-center">
                  <input type="hidden" name="sections[{{ $s->id }}][visible]" value="0">
                  <input type="checkbox" name="sections[{{ $s->id }}][visible]" value="1" {{ $s->is_visible ? 'checked' : '' }} class="peer sr-only" @change="$el.nextElementSibling.classList.toggle('on', $el.checked)">
                  <span class="adm-toggle {{ $s->is_visible ? 'on' : '' }}"><span class="adm-toggle-dot"></span></span>
                </label>
              </div>
              <div class="flex items-center gap-2">
                <label class="text-xs font-medium text-gray-500">Position</label>
                <div class="position-pane flex items-center gap-1 rounded-lg border border-gray-200 bg-white px-2 py-1 dark:border-gray-700 dark:bg-gray-800">
                  <span class="position-num px-0.5 text-xs font-bold text-forest-600">No. {{ $loop->iteration }}</span>
                  <input type="hidden" name="sections[{{ $s->id }}][sort_order]" value="{{ $s->sort_order }}">
                </div>
              </div>
            </div>

            <div class="space-y-6 px-6 py-6">
              {{-- Title / subtitle / products --}}
              <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="md:col-span-1">
                  <label class="adm-field">Section Title</label>
                  <input type="text" name="sections[{{ $s->id }}][title]" value="{{ $s->title }}" class="adm-input" placeholder="Section heading">
                </div>
                <div class="md:col-span-1">
                  <label class="adm-field">Subtitle</label>
                  <input type="text" name="sections[{{ $s->id }}][subtitle]" value="{{ $s->subtitle }}" class="adm-input" placeholder="Short line under the title">
                </div>
                <div class="md:col-span-1">
                  <label class="adm-field">Products Count</label>
                  <input type="number" name="sections[{{ $s->id }}][product_count]" value="{{ $cfg['product_count'] ?? '' }}" min="1" max="24" class="adm-input" placeholder="e.g. 8">
                </div>
              </div>

              {{-- Section-specific editors --}}
              @if(str_starts_with($s->key, 'focus_') || $s->key === 'welcome')
                @php $tabHint = $s->key === 'welcome' ? 'All / Ghee / Oils / Atta / Combos / Deal' : ($s->key === 'focus_oils' ? 'Groundnut / Mustard / Sunflower / Olive / Coconut / Sesame' : 'Gir / Desi Cow / Buffalo / Combo'); @endphp
                <div class="rounded-2xl border border-gray-100 bg-gray-50/70 p-5 dark:border-gray-800 dark:bg-gray-900/40" x-data="sectionList('tabs', @js($cfg['tabs'] ?? []))" @input="sync()">
                  <input type="hidden" data-listsync :name="'sections[{{ $s->id }}][tabs_json]'" :value="jsonValue">
                  <div class="mb-4 flex items-center gap-2">
                    <span class="adm-rownum">🧩</span>
                    <div>
                      <p class="text-sm font-bold text-gray-800 dark:text-white">Menu Tabs</p>
                      <p class="text-xs text-gray-400">Each row is one tab visitors tap in the menu (<code class="text-forest-600">{{ $tabHint }}</code>).</p>
                    </div>
                  </div>
                  <div class="space-y-3">
                    <template x-for="(row, i) in rows" :key="i">
                      <div class="adm-list-card">
                        <div class="mb-3 flex items-center justify-between">
                          <div class="flex items-center gap-2">
                            <span class="adm-rownum"><span x-text="i + 1"></span></span>
                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">Tab <span x-text="i + 1"></span></span>
                          </div>
                          <button type="button" @click="removeRow(i)" class="inline-flex items-center gap-1 rounded-lg bg-red-50 px-2.5 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-100 dark:bg-red-500/10 dark:hover:bg-red-500/20">Remove</button>
                        </div>
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-5">
                          <div>
                            <label class="adm-field">Tab Name</label>
                            <input x-model="row.title" class="adm-input" placeholder="e.g. Ghee">
                          </div>
                          <div>
                            <label class="adm-field">Key (URL slug)</label>
                            <input x-model="row.key" class="adm-input" placeholder="e.g. ghee" @input="row.key = row.key.toLowerCase().replace(/[^a-z0-9-]/g, '-')">
                          </div>
                          <div>
                            <label class="adm-field">Show (type)</label>
                            <select x-model="row.type" @input="row.url = genTabUrl(row)" class="adm-input">
                              <option value="all">All products</option>
                              <option value="deal">Sale / deal products</option>
                              <option value="category">One category</option>
                              <option value="categories">Several categories</option>
                              <option value="keyword">Product name / keyword</option>
                            </select>
                          </div>
                          <div>
                            <label class="adm-field">Value</label>
                            <input x-model="row.value" @input="row.url = genTabUrl(row)" class="adm-input" placeholder="Name or category slug e.g. ghee">
                          </div>
                          <div>
                            <label class="adm-field">Icon (inactive)</label>
                            <input x-model="row.inactive_icon" class="adm-input" placeholder="images/nav/nav-ghee.svg">
                          </div>
                          <div>
                            <label class="adm-field">Icon (active)</label>
                            <input x-model="row.active_icon" class="adm-input" placeholder="images/nav/nav-ghee-active.svg">
                          </div>
                          <div class="lg:col-span-2">
                            <label class="adm-field">AJAX URL (auto if empty)</label>
                            <input x-model="row.url" class="adm-input" placeholder="/api/menu/ghee?type=category&value=ghee" readonly>
                            <p class="text-xs text-gray-400 mt-1">Leave empty to auto-generate from type + value</p>
                          </div>
                        </div>
                        <details class="mt-3 rounded-lg border border-gray-100 bg-white/60 p-3 dark:border-gray-800 dark:bg-gray-900/30">
                          <summary class="cursor-pointer text-xs font-semibold text-gray-500 hover:text-forest-600">Fallback (what to show if this tab is empty)</summary>
                          <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-3">
                            <div>
                              <label class="adm-field">Fallback type</label>
                              <select x-model="row.fallback_type" class="adm-input">
                                <option value="">No fallback</option>
                                <option value="category">One category</option>
                                <option value="categories">Several categories</option>
                                <option value="keyword">Product keyword</option>
                              </select>
                            </div>
                            <div>
                              <label class="adm-field">Fallback value</label>
                              <input x-model="row.fallback_value" class="adm-input" placeholder="e.g. ghee">
                            </div>
                            <div>
                              <label class="adm-field">Fallback categories</label>
                              <input x-model="row.fallback_values" class="adm-input" placeholder="ghee, oil, atta">
                            </div>
                          </div>
                        </details>
                      </div>
                    </template>
                  </div>
                  <button type="button" @click="addRow()" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-forest-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-forest-700">
                    + Add Tab
                  </button>
                </div>
              @endif

              @if($s->key === 'hero')
                <div class="flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-5 dark:border-amber-500/30 dark:bg-amber-500/10">
                  <span class="grid h-9 w-9 flex-none place-items-center rounded-xl bg-amber-200/70 text-lg">🖼️</span>
                  <div>
                    <p class="text-sm font-bold text-amber-800 dark:text-amber-200">Hero banner is managed from Banners</p>
                    <p class="mt-0.5 text-sm text-amber-700/90 dark:text-amber-200/80">Add or edit a banner with placement <strong>Hero</strong> in <a href="{{ route('admin.banners.index') }}" class="font-semibold underline">Admin → Marketing → Banners</a> to change the slides, titles and button text.</p>
                  </div>
                </div>
              @endif

              @if($s->key === 'app_download')
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                  <div>
                    <label class="adm-field">Android Store URL</label>
                    <input type="url" name="sections[{{ $s->id }}][android_url]" value="{{ $cfg['android_url'] ?? '' }}" class="adm-input" placeholder="https://play.google.com/…">
                  </div>
                  <div>
                    <label class="adm-field">iOS (App Store) URL</label>
                    <input type="url" name="sections[{{ $s->id }}][ios_url]" value="{{ $cfg['ios_url'] ?? '' }}" class="adm-input" placeholder="https://apps.apple.com/…">
                  </div>
                </div>
              @endif

              @if($s->key === 'trust_badges')
                <div class="rounded-2xl border border-gray-100 bg-gray-50/70 p-5 dark:border-gray-800 dark:bg-gray-900/40" x-data="sectionList('badges', @js($cfg['items'] ?? []))" @input="sync()">
                  <input type="hidden" data-listsync :name="'sections[{{ $s->id }}][items]'" :value="jsonValue">
                  <div class="mb-4 flex items-center gap-2">
                    <span class="adm-rownum">💚</span>
                    <div>
                      <p class="text-sm font-bold text-gray-800 dark:text-white">Trust Badges</p>
                      <p class="text-xs text-gray-400">The "Why Choose Us" points shown together.</p>
                    </div>
                  </div>
                  <div class="space-y-3">
                    <template x-for="(row, i) in rows" :key="i">
                      <div class="adm-list-card">
                        <div class="mb-3 flex items-center justify-between">
                          <div class="flex items-center gap-2">
                            <span class="adm-rownum"><span x-text="i + 1"></span></span>
                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">Badge <span x-text="i + 1"></span></span>
                          </div>
                          <button type="button" @click="removeRow(i)" class="inline-flex items-center gap-1 rounded-lg bg-red-50 px-2.5 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-100 dark:bg-red-500/10 dark:hover:bg-red-500/20">Remove</button>
                        </div>
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                          <div>
                            <label class="adm-field">Badge Title</label>
                            <input x-model="row.title" class="adm-input" placeholder="e.g. Native Sourcing">
                          </div>
                          <div>
                            <label class="adm-field">Icon name</label>
                            <input x-model="row.icon" class="adm-input" placeholder="e.g. leaf / shield-check / users">
                          </div>
                          <div class="sm:col-span-2 lg:col-span-1">
                            <label class="adm-field">Short Description</label>
                            <input x-model="row.text" class="adm-input" placeholder="Shown under the title">
                          </div>
                        </div>
                      </div>
                    </template>
                  </div>
                  <button type="button" @click="addRow()" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-forest-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-forest-700">+ Add Badge</button>
                </div>
              @endif

              @if(in_array($s->key, ['native_ingredients','quality']))
                <div class="rounded-2xl border border-gray-100 bg-gray-50/70 p-5 dark:border-gray-800 dark:bg-gray-900/40" x-data="sectionList('carousel', @js($cfg['carousel'] ?? []))" @input="sync()">
                  <input type="hidden" data-listsync :name="'sections[{{ $s->id }}][carousel_json]'" :value="jsonValue">
                  <div class="mb-4 flex items-center gap-2">
                    <span class="adm-rownum">🎠</span>
                    <div>
                      <p class="text-sm font-bold text-gray-800 dark:text-white">Carousel Images</p>
                      <p class="text-xs text-gray-400">The rotating images in this section — each one a slide.</p>
                    </div>
                  </div>
                  <div class="space-y-3">
                    <template x-for="(row, i) in rows" :key="i">
                      <div class="adm-list-card">
                        <div class="mb-3 flex items-center justify-between">
                          <div class="flex items-center gap-2">
                            <span class="adm-rownum"><span x-text="i + 1"></span></span>
                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">Slide <span x-text="i + 1"></span></span>
                          </div>
                          <button type="button" @click="removeRow(i)" class="inline-flex items-center gap-1 rounded-lg bg-red-50 px-2.5 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-100 dark:bg-red-500/10 dark:hover:bg-red-500/20">Remove</button>
                        </div>
                        <div class="flex flex-col gap-3 sm:flex-row">
                          <div class="w-full sm:w-36">
                            <label class="adm-field">Live preview</label>
                            <div class="grid h-20 w-full place-items-center rounded-xl border border-dashed border-gray-300 bg-gray-50 text-gray-300 dark:border-gray-700 dark:bg-gray-900">
                              <img :src="row.image ? '/storage/' + row.image.replace(/^\/?storage\//,'') : ''" x-on:error="$el.style.display='none'" class="h-full w-full rounded-xl object-cover" alt="">
                              <span x-show="!row.image" class="absolute text-[10px]">no image</span>
                            </div>
                          </div>
                          <div class="flex-1 grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                              <label class="adm-field">Image path</label>
                              <input x-model="row.image" class="adm-input" placeholder="sections/native1.jpg">
                            </div>
                            <div>
                              <label class="adm-field">Alt text</label>
                              <input x-model="row.alt" class="adm-input" placeholder="Short label">
                            </div>
                            <div>
                              <label class="adm-field">Link (optional)</label>
                              <input x-model="row.url" class="adm-input" placeholder="/category or https://">
                            </div>
                          </div>
                        </div>
                      </div>
                    </template>
                  </div>
                  <button type="button" @click="addRow()" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-forest-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-forest-700">+ Add Image</button>
                </div>
              @endif

              {{-- Logo Slider: multi-logo strip (Trusted By marquee) --}}
              @if($s->key === 'logo_slider')
                @php
                    $sectionLogos = collect($cfg['logos'] ?? [])->filter(fn ($p) => is_string($p) && $p !== '')->values()->all();
                @endphp
                <div class="rounded-2xl border border-dashed border-forest-200 bg-forest-50/60 p-5 dark:border-forest-700 dark:bg-forest-900/20">
                  <div class="mb-4 flex items-center gap-2">
                    <span class="adm-rownum">⭐</span>
                    <div>
                      <p class="text-sm font-bold text-gray-800 dark:text-white">Brand Partner Logos</p>
                      <p class="text-xs text-gray-400">Upload the AB Organic / partner brand logos shown in the "Trusted by" strip. Add several, remove any, then Save Sections. Recommended size ~ 400×120.</p>
                    </div>
                  </div>
                  <div id="logo-slider-grid" class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                    @foreach($sectionLogos as $li => $logoPath)
                      <div class="logo-item relative overflow-hidden rounded-lg border border-gray-200 bg-white p-2">
                        <img src="{{ asset('storage/'.$logoPath) }}" alt="Brand logo" class="h-20 w-full object-contain">
                        <button type="button" data-remove="1" data-path="{{ $logoPath }}" data-section="{{ $s->id }}"
                                class="absolute right-1 top-1 rounded-full bg-red-600 px-1.5 text-xs font-bold text-white hover:bg-red-700">✕</button>
                        <input type="hidden" name="sections[{{ $s->id }}][logos_existing][]" value="{{ $logoPath }}">
                      </div>
                    @endforeach
                    <div class="flex min-h-[100px] items-center justify-center rounded-lg border border-dashed border-gray-300 bg-white/60 p-2">
                      <label class="cursor-pointer text-center text-xs font-medium text-gray-500">
                        <span class="block text-lg">＋</span> Add Logos
                        <input type="file" name="sections[{{ $s->id }}][logos][]" accept="image/*" multiple class="hidden">
                      </label>
                    </div>
                  </div>
                  <input type="hidden" name="sections[{{ $s->id }}][removed_logos]" id="removed_logos_{{ $s->id }}">
                  <p class="mt-2 text-[11px] text-gray-400">Empty list → falls back to your registered Brands' logos automatically.</p>
                </div>
              @endif

              {{-- Section-level images --}}
              @if(in_array($s->key, ['native_ingredients','quality','logo_slider','trust_badges','app_download','focus_oils','focus_ghee']))
                @php $imgs = $cfg['images'] ?? []; @endphp
                <div class="rounded-2xl border border-gray-100 bg-gray-50/70 p-5 dark:border-gray-800 dark:bg-gray-900/40">
                  <div class="mb-4 flex items-center gap-2">
                    <span class="adm-rownum">📷</span>
                    <div>
                      <p class="text-sm font-bold text-gray-800 dark:text-white">Section Images</p>
                      <p class="text-xs text-gray-400">Upload a picture to replace the default. Leave blank to keep the current one.</p>
                    </div>
                  </div>
                  <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="adm-imgbox">
                      @if(!empty($imgs['desktop']))
                        <img src="{{ asset('storage/'.$imgs['desktop']) }}" alt="Desktop preview" class="!h-28">
                      @endif
                      <label class="text-sm font-medium text-gray-500">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/></svg>
                        Desktop image
                        <span class="ml-auto text-[11px] text-gray-400">click to upload</span>
                      </label>
                      <input type="file" name="sections[{{ $s->id }}][image_desktop]" accept="image/*">
                      <input type="hidden" name="sections[{{ $s->id }}][image_desktop_existing]" value="{{ $imgs['desktop'] ?? '' }}">
                    </div>
                    <div class="adm-imgbox">
                      @if(!empty($imgs['mobile']))
                        <img src="{{ asset('storage/'.$imgs['mobile']) }}" alt="Mobile preview" class="!h-28">
                      @endif
                      <label class="text-sm font-medium text-gray-500">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        Mobile image
                      </label>
                      <input type="file" name="sections[{{ $s->id }}][image_mobile]" accept="image/*">
                      <input type="hidden" name="sections[{{ $s->id }}][image_mobile_existing]" value="{{ $imgs['mobile'] ?? '' }}">
                    </div>
                    <div>
                      <label class="adm-field">Alt / Custom</label>
                      <input type="text" name="sections[{{ $s->id }}][image_alt]" value="{{ $imgs['alt'] ?? '' }}" placeholder="Alt text" class="adm-input">
                    </div>
                  </div>
                </div>
              @endif
            </div>
          </div>
        </div>
      @endforeach

      <div class="sticky bottom-4 z-10 flex justify-end">
        <button type="submit" class="adm-btn-primary flex items-center gap-2 shadow-lg">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          Save Sections
        </button>
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
  const genTabUrl = (row) => {
    if (!row.type || !row.value) return '';
    const base = '/api/welcome-tab/products';
    const params = new URLSearchParams();
    params.set('type', row.type);
    if (row.type === 'category' || row.type === 'categories') {
      params.set('value', row.value);
    } else if (row.type === 'keyword') {
      params.set('value', row.value);
    }
    return base + '?' + params.toString();
  };
  return {
    schema, rows,
    get jsonValue() { return JSON.stringify(serialize()); },
    addRow() { this.rows.push(blankRow(this.schema)); },
    removeRow(i) { this.rows.splice(i, 1); if (!this.rows.length) this.rows.push(blankRow(this.schema)); },
    move(i, d) { const j = i + d; if (j < 0 || j >= this.rows.length) return; const t = this.rows[i]; this.rows[i] = this.rows[j]; this.rows[j] = t; },
    sync() { const h = this.$el && this.$el.querySelector('input[type="hidden"][data-listsync]'); if (h) h.value = this.jsonValue; },
    genTabUrl,
  };
}

/* ── Reorder homepage section cards (swap two sort_order values) ─── */
function swapSectionOrder(aId, bId, name) {
  if (!bId) return;
  const sel = (id) => document.querySelector('input[name="sections[' + id + '][' + name + ']"]');
  const a = sel(aId), b = sel(bId);
  if (!a || !b) return;
  const tmp = a.value; a.value = b.value; b.value = tmp;

  // Physically swap the two cards so the visual order matches.
  const now = (id) => document.querySelector('input[name="sections[' + id + '][visible]"]');
  const cardA = now(aId) ? now(aId).closest('.adm-pane') : null;
  const cardB = now(bId) ? now(bId).closest('.adm-pane') : null;
  if (cardA && cardB && cardA !== cardB) {
    if (cardA.nextElementSibling === cardB) { cardA.before(cardB); }
    else { cardB.after(cardA); }
  }

  // Re-caption the "No. N" labels to match the new DOM order.
  const labels = document.querySelectorAll('.position-pane');
  [...labels].forEach((el, i) => {
    const t = el.querySelector('.position-num');
    if (t) t.textContent = 'No. ' + (i + 1);
  });
}

// Logo Slider remove button: mark a logo for removal on Save.
document.addEventListener('click', (e) => {
  const btn = e.target.closest('[data-remove]');
  if (!btn) return;
  const path = btn.getAttribute('data-path') || '';
  const item = btn.closest('.logo-item');
  if (item) item.remove();
  const holder = document.getElementById('removed_logos_' + (btn.getAttribute('data-section') || ''));
  if (holder && path) {
    const kept = holder.value ? holder.value.split(',') : [];
    kept.push(path);
    holder.value = kept.join(',');
  }
});
</script>
@endpush
