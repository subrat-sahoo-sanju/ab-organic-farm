@php
    $items = collect($sec->config['items'] ?? [])->filter(fn ($i) => ! empty($i['title']))->take(4);
    $defaultIcons = [
        ['image' => 'why-native.svg',       'fallback' => 'leaf'],
        ['image' => 'why-traditional.svg',  'fallback' => 'cog'],
        ['image' => 'why-quality.svg',      'fallback' => 'badge-check'],
        ['image' => 'why-rural.svg',        'fallback' => 'heart-handshake'],
    ];
@endphp

@if($items->count())
<section class="w-full py-12 sm:py-16">
    <div class="mx-auto w-full max-w-[1300px] px-4 sm:px-6 lg:px-8">
        <h2 class="text-center font-display text-[26px] font-bold text-anv-800 sm:text-3xl">{{ $sec->title ?: 'Why Choose Anveshan?' }}</h2>
        <p class="mt-1 text-center text-sm text-charcoal-600/60">{{ $sec->subtitle }}</p>

        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($items as $idx => $item)
                @php
                    $icon = $defaultIcons[$idx] ?? ['image' => 'why-native.svg', 'fallback' => 'leaf'];
                    $iconPath = $item['image'] ?? $icon['image'];
                @endphp
                <div class="flex flex-col items-center text-center">
                    <span class="grid h-[90px] w-[90px] place-items-center rounded-full bg-leaf-50 ring-1 ring-anv-100 shadow-sm">
                        @if($iconPath && file_exists(public_path('images/'.$iconPath)))
                            <img src="{{ asset('images/'.$iconPath) }}" alt="{{ $item['title'] }}" width="90" height="90" class="h-[90px] w-[90px] object-contain" loading="lazy">
                        @elseif($item['icon'] ?? null)
                            @php echo app(\BladeUI\Icons\Factory::class)->svg('lucide-'.($item['icon'] ?? 'leaf'), 'h-10 w-10 text-anv-600')->toHtml(); @endphp
                        @else
                            @php echo app(\BladeUI\Icons\Factory::class)->svg('lucide-'.$icon['fallback'], 'h-10 w-10 text-anv-600')->toHtml(); @endphp
                        @endif
                    </span>
                    <h3 class="mt-4 text-base font-bold text-charcoal-900">{{ $item['title'] }}</h3>
                    <p class="mt-1 max-w-[15rem] text-sm leading-relaxed text-charcoal-600/65">{{ $item['text'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
