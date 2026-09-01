@php
    $icons = ['leaf' => 'leaf', 'truck' => 'truck', 'hand_coins' => 'hand-coins', 'shield_check' => 'shield-check', 'sprout' => 'sprout', 'sparkles' => 'sparkles', 'recycle' => 'recycle', 'heart' => 'heart', 'sun' => 'sun', 'droplets' => 'droplets'];
    $items = collect($sec->config['items'] ?? [])->filter(fn ($i) => ! empty($i['title']))->take(4);
@endphp

@if($items->count())
<section class="border-y border-cream-200/70 bg-white">
  <div class="mx-auto grid w-full max-w-7xl grid-cols-2 gap-x-4 gap-y-6 px-4 py-6 sm:px-6 lg:grid-cols-4 lg:px-8">
    @foreach($items as $item)
      <div class="flex items-center gap-3 lg:justify-start">
        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-forest-50 text-forest-600 ring-1 ring-forest-100">
          @php echo app(\BladeUI\Icons\Factory::class)->svg('lucide-'.($icons[$item['icon'] ?? 'leaf'] ?? 'leaf'), 'h-5 w-5')->toHtml(); @endphp
        </span>
        <div>
          <p class="text-sm font-bold leading-tight text-charcoal-900">{{ $item['title'] }}</p>
          <p class="mt-0.5 line-clamp-2 text-[11px] leading-snug text-charcoal-600/50">{{ $item['text'] ?? '' }}</p>
        </div>
      </div>
    @endforeach
  </div>
</section>
@endif