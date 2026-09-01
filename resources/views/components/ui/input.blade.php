@props([
    'label' => null,
    'name' => null,
    'error' => null,
    'hint' => null,
    'type' => 'text',
])

<div class="w-full">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-semibold text-charcoal-700 mb-1.5">
            {{ $label }}
            @if($attributes->has('required'))
                <span class="text-clay-500">*</span>
            @endif
        </label>
    @endif

    @php
        $attrs = $attributes->merge([
            'type' => $type,
            'class' => 'w-full h-11 px-3.5 rounded-xl border bg-white text-sm placeholder:text-charcoal-600/40 transition-colors focus:outline-none focus:ring-2 focus:ring-forest-500/30 focus:border-forest-500 '.($error ? 'border-rose-400' : 'border-cream-200 hover:border-forest-300'),
        ]);
        if ($name) {
            $attrs = $attrs->merge(['name' => $name]);
        }
    @endphp

    <input {{ $attrs }}>

    @if($hint && !$error)
        <p class="mt-1.5 text-xs text-charcoal-600/70">{{ $hint }}</p>
    @endif

    @if($error)
        <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $error }}</p>
    @endif
</div>
