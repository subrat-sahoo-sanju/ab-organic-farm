@props([
    'title' => 'Are you sure?',
    'confirmLabel' => 'Confirm',
    'cancelLabel' => 'Cancel',
    'danger' => false,
])

<div x-data="{ open: false }" {{ $attributes }}>
    <div {{ $attributes->only('wire:key') }} @click="open = true">{{ $trigger ?? '' }}</div>

    <template x-teleport="body">
        <div x-show="open" class="fixed inset-0 z-[80]" role="dialog" aria-modal="true" aria-label="{{ $title }}">
            <div x-show="open" x-transition.opacity.duration.200ms class="absolute inset-0 bg-charcoal-900/50 backdrop-blur-sm"
                 @click="open = false"></div>

            <div x-show="open"
                 x-transition:enter="transition ease-out duration-250"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="relative mx-auto mt-[18vh] w-full max-w-md rounded-card bg-white p-6 shadow-lift">
                <h3 class="font-display text-xl font-semibold text-charcoal-800">{{ $title }}</h3>
                <p class="mt-2 text-sm text-charcoal-600 leading-relaxed">{{ $slot }}</p>

                <div class="mt-6 flex justify-end gap-3">
                    <x-ui.button variant="ghost" size="sm" @click="open = false">{{ $cancelLabel }}</x-ui.button>
                    <x-ui.button :variant="$danger ? 'danger' : 'primary'" size="sm" @click="open = false; $dispatch('confirmed')">
                        {{ $confirmLabel }}
                    </x-ui.button>
                </div>
            </div>
        </div>
    </template>
</div>
