@php
    $title = $section['title'] ?? 'Why Choose AB Organic?';
    $subtitle = $section['subtitle'] ?? '';
    $items = collect($section['config']['items'] ?? [])->filter(fn ($i) => !empty($i['title']))->take(4);
@endphp

@if($items->count())
<section class="w-full bg-white py-10 sm:py-14">
    <div class="mx-auto max-w-[1100px] px-4 sm:px-6 lg:px-8">
        <h2 class="text-center font-display text-2xl font-bold leading-tight text-[#2c5530] sm:text-3xl">{{ $title }}</h2>
        @if($subtitle !== '')
            <p class="mt-1 text-center text-sm text-[#666666]">{{ $subtitle }}</p>
        @endif

        <div class="mx-auto mt-10 grid max-w-[900px] grid-cols-2 gap-x-6 gap-y-8 lg:grid-cols-4">
            @foreach($items as $item)
                @php
                    $iconPath = $item['image'] ?? '';
                    $pubPath = $iconPath ? public_path('images/'.$iconPath) : '';
                @endphp
                <div class="flex flex-col items-center text-center">
                    @if($iconPath && file_exists($pubPath))
                        <img src="{{ asset('images/'.$iconPath) }}" alt="{{ $item['title'] }}" width="80" height="80"
                             class="h-[80px] w-[80px] object-contain" loading="lazy">
                    @else
                        <div class="grid h-[80px] w-[80px] place-items-center rounded-full bg-anv-50 text-anv-600">
                            <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
                        </div>
                    @endif
                    <h3 class="mt-3 text-[14px] font-bold leading-snug text-[#2c5530] sm:text-base">{{ $item['title'] }}</h3>
                    <p class="mt-1 max-w-[14rem] text-[12px] leading-relaxed text-[#666666]">{{ $item['text'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
