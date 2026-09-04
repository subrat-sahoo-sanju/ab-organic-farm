@php
    $logos = $data ?? collect();
    if ($logos instanceof \Illuminate\Support\Collection && $logos->count() === 1 && ! empty(is_object($logos->first()) ? ($logos->first()->image ?? null) : $logos->first())) {
        $logos = collect([$logos->first() instanceof \stdClass ? $logos->first()->image : $logos->first()]);
    } else {
        $logos = $logos->map(fn ($l) => is_object($l) && ! empty($l->image) ? $l->image : $l)->filter(fn ($l) => is_string($l) && $l !== '');
    }
@endphp

@if($logos->count())
<section class="w-full border-y border-sage-100 bg-white py-6">
    <div class="mx-auto w-full max-w-[1440px] px-4 sm:px-6 lg:px-8">
        <div class="anv-marquee-track overflow-hidden">
            <div class="anv-marquee">
                @foreach([0, 1] as $dup)
                    @foreach($logos as $img)
                        <img src="{{ str_starts_with((string)$img, 'http') ? $img : asset('storage/'.$img) }}" alt="" loading="lazy"
                             class="mx-8 h-14 w-auto max-w-[150px] object-contain opacity-70 transition hover:opacity-100">
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>
</section>
@else
@include('customer.sections._empty-state', ['sec' => $sec])
@endif
