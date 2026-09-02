@extends('layouts.app', ['title' => 'AB Organic Farm — Organic Food Delivered Fresh to Your Door'])

@section('content')

@include('customer.sections._tabs-js')

{{-- ========== ANVESHAN-STYLE SECTION-DRIVEN HOMEPAGE ========== --}}
<div class="bg-leaf-50">
    @foreach($homeSections as $section)
        @php
            $key = str_replace('-', '_', $section->key);
            $partial = 'customer.sections.'.$key;
            $partial = $key === 'focus_ghee' || $key === 'focus_oils' ? 'customer.sections.focus' : $partial;
        @endphp
        @if(view()->exists($partial))
            @include($partial, ['sec' => $section, 'data' => $sectionData[$key] ?? [], 'tabs' => $sectionTabs[$key] ?? []])
        @endif
    @endforeach
</div>

<style>
  .scrollbar-none { scrollbar-width: none; -ms-overflow-style: none; }
  .scrollbar-none::-webkit-scrollbar { display: none; }
  .rail-scroll { scroll-behavior: smooth; }
  .rail-scroll::-webkit-scrollbar { height: 4px; }
  .rail-scroll::-webkit-scrollbar-track { background: transparent; }
  .rail-scroll::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 9999px; }
</style>

@endsection