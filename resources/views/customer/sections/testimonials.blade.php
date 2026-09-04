@php
    $reviews = $data ?? collect();
@endphp

@if($reviews->count())
<section class="w-full border-t border-sage-100 bg-gradient-to-b from-white to-leaf-50 py-12">
    <div class="mx-auto w-full max-w-[1440px] px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="font-display text-[24px] font-bold text-[#242424] sm:text-[26px]">{{ $sec->title }}</h2>
            <p class="mt-1 text-sm text-charcoal-600/60">{{ $sec->subtitle }}</p>
            <div class="mt-3 inline-flex items-center gap-1 text-gold-500">
                @for($i=1;$i<=5;$i++)
                    <svg viewBox="0 0 20 20" fill="#FFC107" class="h-5 w-5"><path d="M10 1.5l2.6 5.3 5.9.9-4.2 4.1 1 5.8L10 15.3 4.7 17.6l1-5.8L1.5 7.7l5.9-.9z"/></svg>
                @endfor
                <span class="ml-1 text-xs font-bold text-charcoal-600">Rated {{ number_format($reviews->avg('rating'), 1) }}/5 by our customers</span>
            </div>
        </div>

        <div class="anv-rail mt-9 pb-2" x-data>
            @foreach($reviews as $review)
                <article class="anv-rail-item w-[300px] sm:w-[360px]">
                    <div class="anv-card flex h-full flex-col p-6 shadow-sm transition duration-300 hover:shadow-xl hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1 text-gold-500">
                                @for($i=1;$i<=5;$i++)
                                    <svg viewBox="0 0 20 20" fill="{{ $i <= ($review->rating ?? 5) ? '#FFC107' : '#E1E8E3' }}" class="h-4 w-4"><path d="M10 1.5l2.6 5.3 5.9.9-4.2 4.1 1 5.8L10 15.3 4.7 17.6l1-5.8L1.5 7.7l5.9-.9z"/></svg>
                                @endfor
                            </div>
                            <span class="rounded-full bg-leaf-50 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-anv-600">Verified</span>
                        </div>
                        <p class="mt-3 flex-1 text-[13px] leading-relaxed text-charcoal-700">"{{ Str::limit($review->body, 200) }}"</p>
                        <div class="mt-5 flex items-center gap-3 border-t border-sage-100 pt-4">
                            <span class="grid h-11 w-11 place-items-center rounded-full bg-gradient-to-br from-anv-600 to-anv-500 text-sm font-extrabold uppercase text-white shadow-sm ring-2 ring-sage-100">{{ strtoupper(substr($review->user->name ?? 'V', 0, 1)) }}</span>
                            <div>
                                <p class="text-sm font-bold text-charcoal-900">{{ $review->user->name ?? 'Verified Buyer' }}</p>
                                @if($review->product)<p class="text-xs text-charcoal-600/50">on {{ $review->product->name }}</p>@endif
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@else
@include('customer.sections._empty-state', ['sec' => $sec])
@endif
