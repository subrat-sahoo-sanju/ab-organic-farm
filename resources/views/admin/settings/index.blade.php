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
