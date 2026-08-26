<div
    x-data="{ show: true }"
    x-show="show"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-end="opacity-0 translate-y-2"
    class="fixed bottom-5 right-5 z-[70] flex flex-col gap-2 items-end"
    aria-live="polite"
>
    <template x-for="toast in $store.toast.items" :key="toast.id">
        <div
            x-data="{ shown: false }"
            x-init="$nextTick(() => shown = true)"
            x-show="shown"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-3 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            class="pointer-events-auto flex max-w-sm items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium shadow-lift"
            :class="toast.type === 'error' ? 'bg-rose-600 text-white' : 'bg-forest-700 text-white'"
            role="status"
        >
            <span x-show="toast.type !== 'error'" x-cloak>✓</span>
            <span x-text="toast.message"></span>
        </div>
    </template>
</div>
