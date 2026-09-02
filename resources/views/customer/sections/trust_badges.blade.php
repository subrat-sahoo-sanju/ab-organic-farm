@php
    $items = collect($sec->config['items'] ?? [])->filter(fn ($i) => ! empty($i['title']))->take(4);
    $heading = $sec->title ?: setting('store.name').' — Why Choose Us?';
    $subtitle = (string) ($sec->subtitle ?? '');
@endphp

@if($items->count())
<section class="w-full bg-white">
    <div class="mx-auto w-full max-w-[1200px] px-5 py-12 sm:px-10 sm:py-16">
        <h2 class="text-center font-display text-[24px] font-bold leading-tight text-[#2c5530] sm:text-[32px]">{{ $heading }}</h2>
        @if($subtitle !== '')
            <p class="mt-1 text-center text-sm text-[#666666]">{{ $subtitle }}</p>
        @endif

        <div class="mx-auto mt-10 grid max-w-[1100px] grid-cols-2 gap-x-6 gap-y-10 lg:grid-cols-4">
            @foreach($items as $item)
                @php
                    $iconPath = $item['image'] ?? ($defaults[$loop->index] ?? '');
                    $pubPath = str_starts_with((string) $iconPath, 'images/')
                        ? public_path($iconPath)
                        : public_path('images/'.$iconPath);
                @endphp
                <div class="flex flex-col items-center text-center">
                    @if($iconPath && file_exists($pubPath))
                        <img src="{{ str_starts_with((string) $iconPath, 'images/') ? asset($iconPath) : asset('images/'.$iconPath) }}"
                             alt="{{ $item['title'] }}" width="90" height="90"
                             class="h-[90px] w-[90px] object-contain" loading="lazy">
                    @else
                        <span class="grid h-[90px] w-[90px] place-items-center text-[#2c5530]">
                            @php echo app(\BladeUI\Icons\Factory::class)->svg('lucide-'.($item['icon'] ?? 'leaf'), 'h-12 w-12')->toHtml(); @endphp
                        </span>
                    @endif
                    <h3 class="mt-4 text-[15px] font-bold leading-snug text-[#2c5530] sm:text-base">{{ $item['title'] }}</h3>
                    <p class="mt-1 max-w-[16rem] text-[13px] leading-relaxed text-[#666666]">{{ $item['text'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif