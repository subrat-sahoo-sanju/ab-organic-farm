@php
    $title = $section['title'] ?? 'Explore Categories';
    $subtitle = $section['subtitle'] ?? '';
    $children = $subcategories ?? collect();
@endphp

@if($children->count())
<section class="w-full bg-white py-10 sm:py-14">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-8 text-center">
            <h2 class="font-display text-2xl font-extrabold text-charcoal-900 sm:text-3xl">{{ $title }}</h2>
            @if($subtitle)
                <p class="mt-2 text-sm text-charcoal-600/60">{{ $subtitle }}</p>
            @endif
        </div>

        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
            @foreach($children as $child)
                <a href="{{ route('shop.category', $child->slug) }}" class="group flex flex-col items-center rounded-2xl border border-sage-100 bg-white p-5 text-center shadow-sm transition-all hover:border-anv-300 hover:shadow-lg hover:-translate-y-1">
                    <div class="grid h-16 w-16 place-items-center rounded-full bg-gradient-to-br from-anv-50 to-leaf-100 transition group-hover:from-anv-100 group-hover:to-anv-200">
                        @if($child->image_path)
                            <img src="{{ asset('storage/'.$child->image_path) }}" alt="{{ $child->name }}" class="h-10 w-10 rounded-full object-cover">
                        @elseif($child->icon)
                            <span class="text-2xl">{{ $child->icon }}</span>
                        @else
                            <span class="text-2xl opacity-30">🏷️</span>
                        @endif
                    </div>
                    <span class="mt-3 text-sm font-semibold text-charcoal-800 group-hover:text-anv-700">{{ $child->name }}</span>
                    @if($child->products_count)
                        <span class="mt-1 text-[11px] text-charcoal-500/60">{{ $child->products_count }} products</span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
