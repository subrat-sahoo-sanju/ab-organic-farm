@props([
    'id' => 'modal',
    'title' => '',
    'size' => 'md',
])

@php
$sizes = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-lg',
    'lg' => 'max-w-2xl',
];
$width = $sizes[$size] ?? $sizes['md'];
@endphp

<div x-data="{ open: false }" @open-{{ $id }}.window="open = true" @close-{{ $id }}.window="open = false" x-cloak>
    <template x-teleport="body">
        <div x-show="open" class="fixed inset-0 z-[80]" role="dialog" aria-modal="true" aria-label="{{ $title }}">
            {{-- Overlay --}}
            <div x-show="open" x-transition.opacity.duration.200ms class="absolute inset-0 bg-charcoal-900/50 backdrop-blur-sm dark:bg-black/60" @click="open = false; $dispatch('close-{{ $id }}')"></div>

            {{-- Panel --}}
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-250"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative mx-auto mt-[12vh] w-full {{ $width }} rounded-xl border border-sage/20 bg-white shadow-xl dark:border-gray-700 dark:bg-gray-800">

                {{-- Header --}}
                <div class="flex items-center justify-between border-b border-sage/20 px-6 py-4 dark:border-gray-700">
                    <h3 class="text-base font-semibold text-charcoal dark:text-white">{{ $title }}</h3>
                    <button @click="open = false; $dispatch('close-{{ $id }}')" class="rounded-lg p-1.5 text-charcoal/40 transition hover:bg-sage/10 hover:text-charcoal dark:text-gray-500 dark:hover:bg-gray-700 dark:hover:text-white">
                        <x-lucide-x class="h-4 w-4" />
                    </button>
                </div>

                {{-- Body --}}
                <div class="px-6 py-5">
                    {{ $slot }}
                </div>

                {{-- Footer --}}
                @isset($footer)
                    <div class="flex items-center justify-end gap-3 border-t border-sage/20 px-6 py-4 dark:border-gray-700">
                        {{ $footer }}
                    </div>
                @endisset
            </div>
        </div>
    </template>
</div>
