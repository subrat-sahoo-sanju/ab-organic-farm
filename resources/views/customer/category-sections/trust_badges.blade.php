@php
    $title = $section['title'] ?? 'Why Choose AB Organic?';
    $subtitle = $section['subtitle'] ?? '';
    $items = collect($section['config']['items'] ?? [])->filter(fn ($i) => !empty($i['title']))->take(4);
@endphp

@if($items->count())
<section class="w-full bg-[#fafcfa] py-12 sm:py-16">
    <div class="mx-auto max-w-[1100px] px-4 sm:px-6 lg:px-8">
        <div class="mb-10 text-center">
            <h2 class="font-display text-2xl font-bold leading-tight text-[#2c5530] sm:text-3xl">{{ $title }}</h2>
            @if($subtitle !== '')
                <p class="mt-2 text-sm text-[#666666]">{{ $subtitle }}</p>
            @endif
        </div>

        <div class="mx-auto grid max-w-[900px] grid-cols-2 gap-6 sm:gap-8 lg:grid-cols-4">
            @foreach($items as $item)
                @php
                    $iconPath = $item['image'] ?? '';
                    $pubPath = $iconPath ? public_path('images/'.$iconPath) : '';
                @endphp
                <div class="flex flex-col items-center rounded-2xl bg-white p-5 text-center shadow-sm transition hover:shadow-md hover:-translate-y-0.5">
                    @if($iconPath && file_exists($pubPath))
                        <img src="{{ asset('images/'.$iconPath) }}" alt="{{ $item['title'] }}" width="72" height="72"
                             class="h-[72px] w-[72px] object-contain" loading="lazy">
                    @else
                        <div class="grid h-[72px] w-[72px] place-items-center rounded-full bg-anv-50 text-anv-600">
                            <svg class="h-9 w-9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
                        </div>
                    @endif
                    <h3 class="mt-3 text-[14px] font-bold leading-snug text-[#2c5530] sm:text-base">{{ $item['title'] }}</h3>
                    <p class="mt-1.5 max-w-[14rem] text-[12px] leading-relaxed text-[#666666]">{{ $item['text'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
