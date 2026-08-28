@extends('layouts.admin', ['title' => 'Settings'])

@section('content')
<div class="space-y-4" x-data="{ activeTab: '{{ array_key_first($sections) }}' }">

  <div class="flex flex-wrap gap-2">
    @foreach($sections as $key => $section)
      <button @click="activeTab = '{{ $key }}'" :class="activeTab === '{{ $key }}' ? 'adm-pill-active' : 'adm-pill'">{{ $section['title'] }}</button>
    @endforeach
  </div>

  <form action="{{ route('admin.settings.update') }}" method="POST">
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

                @if(($field['type'] ?? 'text') === 'textarea')
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

</div>
@endsection

@push('scripts')
<script>
function jsonEditor(schema, initial) {
  let rows = [];
  if (schema === 'tags' || schema === 'link_list' || schema === 'feat_items' || schema === 'promo_cards') {
    try {
      const arr = typeof initial === 'string' ? JSON.parse(initial) : initial;
      if (Array.isArray(arr)) {
        rows = arr.map(item => {
          if (typeof item === 'string') return { value: item };
          const obj = item || {};
          if (schema === 'link_list') return { label: obj.label || '', url: obj.url || '' };
          if (schema === 'feat_items') return { icon: obj.icon || 'leaf', title: obj.title || '', text: obj.text || '' };
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
    if (schema === 'promo_cards') return rows.map(r => r).filter(r => r.title || r.subtitle);
    return rows;
  };

  return {
    schema,
    rows,
    get jsonValue() { return JSON.stringify(serialize()); },
    sync() { /* keep hidden input in sync on submit */ },
    addRow() { this.rows.push(emptyRow(this.schema)); },
    removeRow(idx) { this.rows.splice(idx, 1); },
  };
}
function emptyRow(schema) {
  if (schema === 'tags') return { value: '' };
  if (schema === 'link_list') return { label: '', url: '' };
  if (schema === 'feat_items') return { icon: 'leaf', title: '', text: '' };
  if (schema === 'promo_cards') return { color: 'green', badge: '', title: '', subtitle: '', code: '', cta: '', link: '' };
  return {};
}
</script>
@endpush
