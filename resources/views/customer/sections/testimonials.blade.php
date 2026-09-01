@php
    $reviews = $data ?? collect();
@endphp

@if($reviews->count())
<section class="bg-forest-50/60 py-12">
  <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
    @include('customer.sections._header', ['title' => $sec->title, 'subtitle' => $sec->subtitle, 'align' => 'center'])

    <div class="scrollbar-none relative mt-8 flex gap-4 overflow-x-auto pb-2" style="-webkit-overflow-scrolling: touch;" x-data="{ scroll: 0 }">
      @foreach($reviews as $review)
        <div class="flex w-80 flex-shrink-0 flex-col rounded-2xl bg-white p-6 shadow-[0_4px_20px_-8px_rgba(12,131,31,0.18)] ring-1 ring-cream-200">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-1 text-amber-400">
              @for($i = 1; $i <= 5; $i++)
                <x-lucide-star class="h-4 w-4 {{ $i <= ($review->rating ?? 5) ? 'fill-amber-400' : 'text-cream-200' }}" />
              @endfor
            </div>
            <span class="flex items-center gap-1 rounded-full bg-forest-50 px-2 py-1 text-[10px] font-bold text-forest-700"><x-lucide-badge-check class="h-3 w-3 text-forest-600" />Verified Buyer</span>
          </div>
          <p class="mt-3 flex-1 text-sm leading-relaxed text-charcoal-700">"{{ Str::limit($review->body, 160) }}"</p>
          <div class="mt-5 flex items-center gap-3 border-t border-cream-200 pt-4">
            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-forest-500 to-forest-700 text-sm font-extrabold uppercase text-white">{{ strtoupper(substr($review->user->name ?? 'V', 0, 1)) }}</span>
            <div>
              <p class="text-sm font-bold text-charcoal-900">{{ $review->user->name ?? 'Verified Buyer' }}</p>
              @if($review->product)<p class="text-xs text-charcoal-600/50">on {{ $review->product->name }}</p>@endif
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif