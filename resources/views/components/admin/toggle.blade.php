@props([
    'name',
    'checked' => false,
    'label' => null,
    'disabled' => false,
])

<label class="inline-flex items-center gap-2.5 {{ $disabled ? 'cursor-not-allowed opacity-50' : 'cursor-pointer' }}" x-data="{ on: @js((bool) $checked) }">
    <button type="button" role="switch" :aria-checked="on.toString()" aria-label="{{ $label ?? $name }}"
            x-on:click="{{ $disabled ? '' : 'on = !on' }}"
            :class="on ? 'bg-forest dark:bg-green-600' : 'bg-charcoal/20 dark:bg-gray-600'"
            class="relative inline-flex h-6 w-11 shrink-0 items-center rounded-full transition-colors duration-200 ease-in-out focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-forest disabled:pointer-events-none">
        <input type="hidden" name="{{ $name }}" :value="on ? '1' : '0'" x-on:change="">
        <span x-bind:class="on ? 'translate-x-5' : 'translate-x-0.5'"
              class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out dark:bg-gray-100">
        </span>
    </button>
    @if($label)
        <span class="text-sm font-medium text-charcoal/70 dark:text-gray-300">{{ $label }}</span>
    @endif
</label>
