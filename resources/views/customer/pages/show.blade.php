@extends('layouts.app', ['title' => $page->title])

@section('content')
@php
    $store = setting('store.name', 'AB Organic Farm');
    $sections = $page->sections ?? [];
    $faqs = $page->faqs ?? [];
    $related = \App\Models\Page::active()
        ->where('slug', '!=', $page->slug)
        ->orderBy('sort_order')->orderBy('updated_at', 'desc')
        ->get();
@endphp

{{-- ═══ HERO ═══ --}}
<section class="relative overflow-hidden">
    <div class="absolute inset-0" style="background-image:url({{ asset('storage/bgs/footer-bg-wide.jpg') }});background-size:cover;background-position:center;"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-[#0E2B1D]/94 via-[#153F2A]/88 to-[#173F2A]/80"></div>
    <div class="relative mx-auto max-w-[1440px] px-4 py-14 sm:px-6 sm:py-20 lg:px-8">
        <nav class="mb-6 text-xs font-medium text-white/50 sm:text-sm">
            <a href="{{ route('shop.index') }}" class="hover:text-gold-300 transition-colors">Home</a>
            <span class="mx-1.5 text-white/30">/</span>
            <span class="text-gold-300">{{ $page->title }}</span>
        </nav>
        <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
            <div class="max-w-2xl">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-gold-200 ring-1 ring-white/20">
                    @svg('lucide-'.($page->icon ?: 'file-text'), 'h-3.5 w-3.5') {{ $store }}
                </span>
                <h1 class="mt-4 font-display text-3xl font-extrabold text-white sm:text-4xl lg:text-5xl">{{ $page->hero ?: $page->title }}</h1>
                @if($page->short)
                    <p class="mt-3 max-w-xl text-sm leading-relaxed text-white/70 sm:text-base">{{ $page->short }}</p>
                @endif
            </div>
            <div class="shrink-0 rounded-2xl bg-white/5 px-5 py-4 ring-1 ring-white/15 backdrop-blur">
                <div class="text-[11px] font-semibold uppercase tracking-wider text-white/50">Last updated</div>
                <div class="mt-1 flex items-center gap-2 font-display text-lg font-bold text-gold-300">
                    @svg('lucide-calendar-check','h-5 w-5') {{ $page->updated_at?->format('j F Y') ?: now()->format('j F Y') }}
                </div>
            </div>
        </div>
    </div>
</section>

<div class="mx-auto max-w-[1440px] px-4 py-10 sm:px-6 lg:px-8">
    <div class="grid gap-10 lg:grid-cols-[280px_minmax(0,1fr)]">

        {{-- ═══ STICKY TOC (desktop) ═══ --}}
        @if(count($sections))
        <aside class="hidden lg:block">
            <div class="sticky top-24 rounded-2xl border border-sage/20 bg-white p-5 shadow-sm">
                <p class="mb-3 px-2 text-[11px] font-bold uppercase tracking-wider text-charcoal/40">On this page</p>
                <nav class="space-y-1">
                    @foreach($sections as $i => $sec)
                        <a href="#sec-{{ $i }}" class="group flex items-center gap-3 rounded-xl px-2 py-2 text-sm text-charcoal/70 transition hover:bg-anv-50 hover:text-anv-700">
                            <span class="grid h-7 w-7 shrink-0 place-items-center rounded-lg bg-anv-50 text-anv-600 group-hover:bg-anv-100">
                                @svg('lucide-'.($sec['icon'] ?? 'circle-check'), 'h-4 w-4')
                            </span>
                            <span class="font-medium">{{ $sec['heading'] ?? '' }}</span>
                        </a>
                    @endforeach
                </nav>
                <a href="{{ setting('store.contact_link', '#') }}" class="mt-5 flex items-center justify-center gap-2 rounded-xl bg-anv-600 px-4 py-3 text-sm font-bold text-white transition hover:bg-anv-700">
                    @svg('lucide-headset','h-4 w-4') Contact support
                </a>
            </div>
        </aside>
        @endif

        {{-- ═══ MAIN CONTENT ═══ --}}
        <div class="min-w-0">
            @if(count($sections))
            <div class="mb-6 flex gap-2 overflow-x-auto pb-2 lg:hidden">
                @foreach($sections as $i => $sec)
                    <a href="#sec-{{ $i }}" class="shrink-0 rounded-full border border-sage/30 bg-white px-4 py-2 text-xs font-semibold text-anv-700 transition hover:border-anv-600 hover:bg-anv-50">{{ $sec['heading'] ?? '' }}</a>
                @endforeach
            </div>
            @endif

            @if($page->lede)
            <div class="mb-8 rounded-2xl border-l-4 border-gold-400 bg-anv-50/60 p-5 sm:p-6">
                <p class="text-[15px] leading-relaxed text-charcoal/80">{!! nl2br(e($page->lede)) !!}</p>
            </div>
            @endif

            @if(count($sections))
            <div class="space-y-6">
                @foreach($sections as $i => $sec)
                    <article id="sec-{{ $i }}" class="scroll-mt-24 rounded-2xl border border-sage/25 bg-white p-6 shadow-sm transition hover:shadow-md sm:p-8">
                        <div class="flex items-start gap-4">
                            <div class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-gradient-to-br from-anv-600 to-anv-800 text-white shadow-md">
                                @svg('lucide-'.($sec['icon'] ?? 'circle-check'), 'h-6 w-6')
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3">
                                    <span class="font-display text-sm font-extrabold text-gold-500">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    @if(!empty($sec['heading']))
                                        <h2 class="font-display text-xl font-bold text-charcoal">{{ $sec['heading'] }}</h2>
                                    @endif
                                </div>
                                @if(!empty($sec['body']))
                                    <p class="mt-3 leading-relaxed text-charcoal/75">{!! nl2br(e($sec['body'])) !!}</p>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            @endif

            @if(count($faqs))
            <div class="mt-10 rounded-2xl border border-sage/25 bg-anv-50/40 p-6 sm:p-8">
                <div class="mb-5 flex items-center gap-3">
                    <span class="grid h-10 w-10 place-items-center rounded-xl bg-gold-400 text-anv-900">@svg('lucide-help-circle','h-5 w-5')</span>
                    <h2 class="font-display text-xl font-bold text-charcoal">Frequently asked questions</h2>
                </div>
                <div class="grid gap-3" x-data="{ open: @js(null) }">
                    @foreach($faqs as $fi => $faq)
                        <div class="overflow-hidden rounded-xl border border-sage/25 bg-white">
                            <button type="button" @click="open = (open === {{ $fi }} ? null : {{ $fi }})" class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left">
                                <span class="font-semibold text-charcoal">{{ $faq['q'] ?? '' }}</span>
                                <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-anv-50 text-anv-700" :class="open === {{ $fi }} ? 'rotate-180' : ''">
                                    @svg('lucide-chevron-down','h-4 w-4')
                                </span>
                            </button>
                            <div x-show="open === {{ $fi }}" x-cloak class="px-5 pb-4 text-sm leading-relaxed text-charcoal/75">
                                {{ $faq['a'] ?? '' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ═══ CTA ═══ --}}
            <div class="mt-10 flex flex-col items-center justify-between gap-5 rounded-2xl bg-gradient-to-r from-anv-800 to-anv-900 p-6 sm:flex-row sm:p-8">
                <div class="text-center sm:text-left">
                    <h3 class="font-display text-xl font-bold text-white">Still have a question?</h3>
                    <p class="mt-1 text-sm text-white/60">Our farm support team is happy to help — typically within one working day.</p>
                </div>
                <div class="flex flex-wrap justify-center gap-3">
                    <a href="{{ setting('store.contact_link', '#') }}" class="inline-flex items-center gap-2 rounded-full bg-gold-400 px-6 py-3 text-sm font-bold text-anv-900 transition hover:bg-gold-300">
                        @svg('lucide-mail','h-4 w-4') Contact us
                    </a>
                    <a href="{{ route('shop.categories') }}" class="inline-flex items-center gap-2 rounded-full border border-white/25 px-6 py-3 text-sm font-bold text-white transition hover:bg-white/10">
                        @svg('lucide-shopping-bag','h-4 w-4') Shop organic
                    </a>
                </div>
            </div>

            {{-- ═══ RELATED POLICIES (live from admin) ═══ --}}
            @if($related->count())
            <div class="mt-10">
                <h3 class="mb-4 flex items-center gap-2 font-display text-lg font-bold text-charcoal">
                    @svg('lucide-files','h-5 w-5 text-anv-600') Related policies
                </h3>
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($related as $op)
                        <a href="/{{ $op->slug }}" class="group flex items-center gap-3 rounded-xl border border-sage/25 bg-white p-4 transition hover:border-anv-600 hover:shadow-md">
                            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-anv-50 text-anv-600 transition group-hover:bg-anv-600 group-hover:text-white">
                                @svg('lucide-'.($op->icon ?: 'file-text'),'h-5 w-5')
                            </span>
                            <span class="flex-1 text-sm font-semibold text-charcoal group-hover:text-anv-700">{{ $op->title }}</span>
                            @svg('lucide-arrow-right','h-4 w-4 text-charcoal/30 group-hover:text-anv-600')
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection