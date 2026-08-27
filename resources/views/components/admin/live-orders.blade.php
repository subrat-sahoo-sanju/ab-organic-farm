@php
  $ordersUrl = route('admin.orders.index', ['range' => 'today']);
  $liveUrl = route('admin.orders.live', ['date' => now()->toDateString()]);
@endphp
{{-- Live Today's Orders ticker — auto-refreshes every 15s --}}
<div class="adm-section" x-data="liveOrders()" x-init="init()">
  <div class="mb-4 flex items-center justify-between">
    <div class="flex items-center gap-2.5">
      <span class="relative flex h-3 w-3">
        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-green-400 opacity-75"></span>
        <span class="relative inline-flex h-3 w-3 rounded-full bg-green-500"></span>
      </span>
      <h2 class="adm-section-title mb-0 border-0 pb-0">Today's Live Orders</h2>
      <span class="rounded-full bg-forest/10 px-2.5 py-0.5 text-xs font-bold text-forest dark:bg-green-900/40 dark:text-green-300">
        <span x-text="orders.length"></span> today
      </span>
    </div>
    <div class="flex items-center gap-2">
      <span class="flex items-center gap-1.5 text-[11px] font-medium text-gray-400 dark:text-gray-500">
        <span class="inline-block h-1.5 w-1.5 animate-pulse rounded-full bg-green-400"></span>
        Live
      </span>
      <a href="{{ $ordersUrl }}" class="adm-btn-ghost text-xs !py-1.5">
        View all
        <x-lucide-arrow-right class="h-3.5 w-3.5" />
      </a>
    </div>
  </div>

  <template x-if="!orders.length">
    <div class="flex flex-col items-center justify-center gap-2 py-8 text-center">
      <span class="flex h-12 w-12 items-center justify-center rounded-full bg-charcoal/5 text-charcoal/30">
        <x-lucide-inbox class="h-6 w-6" />
      </span>
      <p class="text-sm font-semibold text-charcoal/60 dark:text-gray-400">No orders placed yet today</p>
      <p class="text-xs text-gray-400 dark:text-gray-500">New orders will appear here instantly.</p>
    </div>
  </template>

  <template x-if="orders.length">
    <div class="grid gap-2.5 md:grid-cols-2 xl:grid-cols-3">
      <template x-for="o in orders" :key="o.id">
        <a :href="o.url" class="group relative flex items-center gap-3 overflow-hidden rounded-xl border border-gray-100 bg-white p-3.5 transition hover:border-forest/40 hover:shadow-md dark:border-gray-800 dark:bg-gray-900 dark:hover:border-green-700"
           :class="{ 'ring-2 ring-forest/30 dark:ring-green-600/40': o.is_new }">
          <template x-if="o.is_new">
            <span class="absolute inset-y-0 left-0 w-1 bg-forest"></span>
          </template>
          <div class="relative flex h-11 w-11 shrink-0 items-center justify-center rounded-xl text-white"
               :class="o.status_bg">
            <x-lucide-shopping-bag class="h-5 w-5" />
            <template x-if="o.is_new">
              <span class="absolute -top-1.5 -right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[9px] font-bold text-white">●</span>
            </template>
          </div>
          <div class="min-w-0 flex-1">
            <div class="flex items-center gap-2">
              <p class="truncate text-sm font-bold text-charcoal dark:text-white" x-text="o.order_number"></p>
              <template x-if="o.is_new">
                <span class="shrink-0 rounded-full bg-green-100 px-1.5 py-0.5 text-[9px] font-bold uppercase text-green-700 dark:bg-green-900/50 dark:text-green-300">New</span>
              </template>
            </div>
            <p class="truncate text-xs text-gray-500 dark:text-gray-400" x-text="o.customer + ' · ' + o.payment"></p>
            <p class="mt-0.5 text-[10px] text-gray-400 dark:text-gray-500" x-text="o.time"></p>
          </div>
          <div class="shrink-0 text-right">
            <p class="text-sm font-bold text-forest dark:text-green-400" x-text="o.amount"></p>
            <span class="rounded-full px-2 py-0.5 text-[9px] font-bold uppercase" :class="o.status_text" x-text="o.status"></span>
          </div>
        </a>
      </template>
    </div>
  </template>
</div>

<script>
function liveOrders() {
  return {
    orders: [],
    lastMaxId: 0,
    init() {
      const url = "{{ $liveUrl }}";
      this.load(url);
      setInterval(() => this.load(url), 15000);
    },
    async load(url) {
      try {
        const r = await fetch(url, { headers: { 'Accept': 'application/json' } });
        const d = await r.json();
        const currentMax = Math.max(0, ...this.orders.map(o => o.id));
        d.forEach(o => { if (o.id > currentMax) o.is_new = true; });
        this.orders = d;
      } catch (e) {}
    }
  };
}
</script>
