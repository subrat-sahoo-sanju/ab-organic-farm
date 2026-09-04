@extends('layouts.admin', ['title' => ($isEdit ? 'Edit' : 'New').' Page'])

@section('content')
@php
    $existing = $page;
    $sections = $existing->sections ?? [];
    $faqs = $existing->faqs ?? [];
@endphp
<div class="space-y-5" x-data="pageForm(@js($sections), @js($faqs))">
  <div class="flex flex-wrap items-center justify-between gap-4">
    <div>
      <a href="{{ route('admin.pages.index') }}" class="adm-back">← Back to pages</a>
      <h2 class="adm-page-title mt-2">{{ $isEdit ? 'Edit Page' : 'New Page' }}</h2>
      <p class="adm-kicker">Compose the page content. Everything you add here is shown live on the storefront.</p>
    </div>
    <div class="flex items-center gap-2">
      @if($isEdit)
        <a href="/{{ $existing->slug }}" target="_blank" class="adm-btn-outline inline-flex items-center gap-2">
          <x-lucide-external-link class="h-4 w-4" /> View live
        </a>
      @endif
    </div>
  </div>

  <form method="POST" action="{{ $isEdit ? route('admin.pages.update', $existing) : route('admin.pages.store') }}" class="space-y-5">
    @csrf
    @if($isEdit) @method('PATCH') @endif

    {{-- ═══ Core details ═══ --}}
    <div class="adm-pane">
      <div class="adm-pane-head">
        <span class="adm-pane-icon"><x-lucide-settings-2 class="h-4 w-4" /></span>
        <h3 class="font-semibold">Core details</h3>
      </div>
      <div class="p-5">
        <div class="adm-grid-2">
          <div>
            <label class="adm-label">Title *</label>
            <input type="text" name="title" required value="{{ old('title', $existing->title ?? '') }}" class="adm-input" placeholder="e.g. Privacy Policy" @change="liveTitle = $event.target.value">
          </div>
          <div>
            <label class="adm-label">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $existing->slug ?? '') }}" class="adm-input font-mono" placeholder="auto-generated from title">
          </div>
        </div>
        <div class="mt-4">
          <label class="adm-label">Hero headline</label>
          <input type="text" name="hero" value="{{ old('hero', $existing->hero ?? '') }}" class="adm-input" placeholder="e.g. Your data, handled with care">
        </div>
        <div class="adm-grid-2 mt-4">
          <div>
            <label class="adm-label">Short subtitle</label>
            <input type="text" name="short" value="{{ old('short', $existing->short ?? '') }}" class="adm-input" placeholder="One-line summary shown under the title">
          </div>
          <div>
            <label class="adm-label">Icon</label>
            <select name="icon" class="adm-input">
              @foreach($icons as $val => $label)
                <option value="{{ $val }}" {{ ($existing->icon ?? 'file-text') === $val ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="mt-4">
          <label class="adm-label">Intro paragraph (lede)</label>
          <textarea name="lede" rows="3" class="adm-input" placeholder="A warm, clear opening line for the page.">{{ old('lede', $existing->lede ?? '') }}</textarea>
        </div>
        <div class="adm-grid-2 mt-4">
          <div>
            <label class="adm-label">Sort order</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $existing->sort_order ?? 0) }}" class="adm-input w-32">
          </div>
          <div class="flex items-end gap-3 pb-1">
            <label class="flex items-center gap-2 text-sm font-semibold">
              <input type="checkbox" name="is_active" value="1" class="h-4 w-4 accent-forest" {{ ($existing->is_active ?? true) ? 'checked' : '' }}>
              <span>Live on store</span>
            </label>
          </div>
        </div>
      </div>
    </div>

    {{-- ═══ Sections ═══ --}}
    <div class="adm-pane">
      <div class="adm-pane-head">
        <span class="adm-pane-icon"><x-lucide-list-checks class="h-4 w-4" /></span>
        <h3 class="font-semibold">Content sections</h3>
        <button type="button" @click="addSection()" class="ml-auto adm-btn-primary inline-flex items-center gap-1 !px-3 !py-1.5 text-sm">
          <x-lucide-plus class="h-4 w-4" /> Add section
        </button>
      </div>
      <div class="p-5 space-y-3" x-ref="sections">
        <template x-for="(s, i) in sections" :key="'s'+i">
          <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-700">
            <div class="flex items-center justify-between gap-2">
              <span class="text-xs font-bold uppercase tracking-wider text-gray-400" x-text="'Section ' + (i+1)"></span>
              <button type="button" @click="sections.splice(i, 1)" class="adm-action-link text-red-500"><x-lucide-trash-2 class="h-4 w-4" /></button>
            </div>
            <div class="mt-3 grid gap-3 sm:grid-cols-[1fr_180px]">
              <input type="text" name="section_heading[]" x-model="s.heading" class="adm-input" placeholder="Heading">
              <input type="text" name="section_icon[]" x-model="s.icon" class="adm-input font-mono" placeholder="icon name">
            </div>
            <textarea name="section_body[]" x-model="s.body" rows="2" class="adm-input mt-3" placeholder="Body text"></textarea>
            <p class="mt-1 text-[11px] text-gray-400">Icon: a lucide icon name e.g. <code>shield-check</code>, <code>leaf</code>, <code>truck</code>.</p>
          </div>
        </template>
        <p x-show="sections.length === 0" class="adm-empty !py-6">No sections yet. Click “Add section”.</p>
      </div>
    </div>

    {{-- ═══ FAQs ═══ --}}
    <div class="adm-pane">
      <div class="adm-pane-head">
        <span class="adm-pane-icon"><x-lucide-help-circle class="h-4 w-4" /></span>
        <h3 class="font-semibold">FAQs</h3>
        <button type="button" @click="addFaq()" class="ml-auto adm-btn-primary inline-flex items-center gap-1 !px-3 !py-1.5 text-sm">
          <x-lucide-plus class="h-4 w-4" /> Add FAQ
        </button>
      </div>
      <div class="p-5 space-y-3">
        <template x-for="(f, i) in faqs" :key="'f'+i">
          <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-700">
            <div class="flex items-center justify-between gap-2">
              <span class="text-xs font-bold uppercase tracking-wider text-gray-400" x-text="'FAQ ' + (i+1)"></span>
              <button type="button" @click="faqs.splice(i, 1)" class="adm-action-link text-red-500"><x-lucide-trash-2 class="h-4 w-4" /></button>
            </div>
            <input type="text" name="faq_q[]" x-model="f.q" class="adm-input mt-3" placeholder="Question">
            <textarea name="faq_a[]" x-model="f.a" rows="2" class="adm-input mt-3" placeholder="Answer"></textarea>
          </div>
        </template>
        <p x-show="faqs.length === 0" class="adm-empty !py-6">No FAQs yet. Click “Add FAQ”.</p>
      </div>
    </div>

    <div class="flex items-center gap-3">
      <button type="submit" class="adm-btn-primary inline-flex items-center gap-2">
        <x-lucide-save class="h-4 w-4" /> {{ $isEdit ? 'Save changes' : 'Create page' }}
      </button>
      <a href="{{ route('admin.pages.index') }}" class="adm-btn-ghost">Cancel</a>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
function pageForm(sections, faqs) {
  return {
    sections,
    faqs,
    addSection() { this.sections.push({ heading: '', icon: 'circle-check', body: '' }); },
    addFaq() { this.faqs.push({ q: '', a: '' }); },
  };
}
</script>
@endpush