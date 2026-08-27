@php
  $notifFront = route('admin.notifications.index');
  $notifFresh = route('admin.notifications.fresh');
  $notifRead = url('admin/notifications');
  $notifReadAll = route('admin.notifications.read-all');
@endphp
{{-- Live Notifications Bell + Dropdown + Popups + Sound --}}
<div x-data="notificationManager()"
     x-init="init('{{ $notifFront }}', '{{ $notifFresh }}', '{{ $notifRead }}', '{{ $notifReadAll }}')"
     @keydown.escape.window="open = false"
     class="relative">

  <button @click="toggle()"
          class="relative rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white"
          title="Notifications">
    <span class="relative inline-flex">
      <x-lucide-bell class="h-5 w-5" />
      <template x-if="unreadCount > 0">
        <span class="absolute -top-2.5 -right-2.5 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white ring-2 ring-white animate-ping-slow dark:ring-gray-900">
          <span x-text="unreadCount > 99 ? '99+' : unreadCount"></span>
        </span>
      </template>
      <template x-if="unreadCount > 0">
        <span class="absolute -top-2.5 -right-2.5 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white dark:ring-gray-900">
          <span x-text="unreadCount > 99 ? '99+' : unreadCount"></span>
        </span>
      </template>
    </span>
  </button>

  {{-- New Order Popup (bottom-right toast) --}}
  <template x-for="pop in popups" :key="pop.id">
    <div class="fixed bottom-5 right-5 z-[60] w-80 max-w-[calc(100vw-2rem)] animate-notif-in" @click="goTo(pop.url)">
      <div class="flex items-start gap-3 rounded-2xl border border-forest/20 bg-white p-4 shadow-xl shadow-forest/10 cursor-pointer dark:border-green-800 dark:bg-gray-900"
           role="alert">
        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full" :class="pop.badgeClass">
          <x-lucide-shopping-bag class="h-5 w-5" />
        </span>
        <div class="min-w-0 flex-1">
          <p class="text-xs font-bold uppercase tracking-wide" :class="pop.textClass" x-text="pop.tag"></p>
          <p class="mt-0.5 truncate font-semibold text-charcoal dark:text-white" x-text="pop.title"></p>
          <p class="text-xs text-gray-500 dark:text-gray-400" x-text="pop.message"></p>
          <p class="mt-1 text-[10px] font-medium text-forest dark:text-green-400">Click to view order →</p>
        </div>
        <button @click.stop="dismissPopup(pop.id)"
                class="shrink-0 rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-gray-800 dark:hover:text-white">
          <x-lucide-x class="h-3.5 w-3.5" />
        </button>
      </div>
    </div>
  </template>

  <template x-for="pop in statusPopups" :key="'s'+pop.id">
    <div class="fixed bottom-5 right-5 z-[60] w-80 max-w-[calc(100vw-2rem)] animate-notif-in" :style="`margin-bottom: ${statusPopups.indexOf(pop) * 90}px`" @click="goTo(pop.url)">
      <div class="flex items-start gap-3 rounded-2xl border border-sky-200 bg-white p-4 shadow-xl shadow-sky-500/10 cursor-pointer dark:border-sky-800 dark:bg-gray-900"
           role="alert">
        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-sky-50 text-sky-600 dark:bg-sky-900/40 dark:text-sky-300">
          <x-lucide-refresh-cw class="h-5 w-5" />
        </span>
        <div class="min-w-0 flex-1">
          <p class="text-xs font-bold uppercase tracking-wide text-sky-600 dark:text-sky-300" x-text="'Status changed'"></p>
          <p class="mt-0.5 truncate font-semibold text-charcoal dark:text-white" x-text="pop.title"></p>
          <p class="text-xs text-gray-500 dark:text-gray-400" x-text="pop.message"></p>
          <p class="mt-1 text-[10px] font-medium text-sky-600 dark:text-sky-300">Click to view →</p>
        </div>
        <button @click.stop="dismissStatusPopup(pop.id)"
                class="shrink-0 rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-gray-800 dark:hover:text-white">
          <x-lucide-x class="h-3.5 w-3.5" />
        </button>
      </div>
    </div>
  </template>

  {{-- Dropdown Panel --}}
  <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
       x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
       x-transition:leave-end="opacity-0 scale-95"
       @click.away="open = false"
       class="absolute right-0 z-50 mt-2 w-[22rem] max-w-[calc(100vw-1rem)] overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl dark:border-gray-700 dark:bg-gray-900">
    <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3 dark:border-gray-800">
      <div class="flex items-center gap-2">
        <x-lucide-bell-ring class="h-4 w-4 text-forest dark:text-green-400" />
        <span class="text-sm font-bold text-charcoal dark:text-white">Notifications</span>
        <template x-if="unreadCount > 0">
          <span class="rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-bold text-red-600 dark:bg-red-900/40 dark:text-red-400" x-text="unreadCount + ' new'"></span>
        </template>
      </div>
      <button @click="markAllRead()" x-show="unreadCount > 0"
              class="text-xs font-semibold text-forest hover:text-forest-700 dark:text-green-400 dark:hover:text-green-300">
        Mark all read
      </button>
    </div>

    <div class="max-h-96 overflow-y-auto">
      <template x-if="items.length === 0">
        <div class="flex flex-col items-center justify-center gap-2 py-12 text-center">
          <span class="flex h-12 w-12 items-center justify-center rounded-full bg-forest/10 text-forest dark:bg-green-900/40 dark:text-green-300">
            <x-lucide-bell-off class="h-6 w-6" />
          </span>
          <p class="text-sm font-semibold text-charcoal/70 dark:text-gray-300">No notifications yet</p>
          <p class="px-8 text-xs text-gray-400 dark:text-gray-500">New orders will appear here in real time.</p>
        </div>
      </template>

      <template x-for="n in items" :key="n.id">
        <a @click.prevent="markRead(n)"
           :href="n.url || '#'"
           class="group flex items-start gap-3 border-b border-gray-50 px-4 py-3 transition hover:bg-gray-50 dark:border-gray-800/60 dark:hover:bg-gray-800/60">
          <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-full"
                :class="n.read ? 'bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-500' : n.badgeClass">
            <span x-html="n.codicon"></span>
          </span>
          <div class="min-w-0 flex-1">
            <div class="flex items-start justify-between gap-2">
              <p class="text-sm font-semibold text-charcoal dark:text-white" :class="{ 'pr-1': !n.read }">
                <span x-text="n.title"></span>
                <template x-if="!n.read"><span class="ml-1 inline-block h-2 w-2 shrink-0 rounded-full bg-red-500"></span></template>
              </p>
              <span class="shrink-0 text-[10px] text-gray-400 dark:text-gray-500" x-text="n.created_at"></span>
            </div>
            <p class="mt-0.5 line-clamp-2 text-xs text-gray-500 dark:text-gray-400" x-text="n.message"></p>
          </div>
        </a>
      </template>
    </div>

    <div class="border-t border-gray-100 p-2 dark:border-gray-800">
      <a href="{{ route('admin.orders.index') }}" @click="open = false"
         class="flex items-center justify-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold text-forest transition hover:bg-forest/5 dark:text-green-400 dark:hover:bg-green-900/20">
        <x-lucide-shopping-bag class="h-4 w-4" />
        View All Orders
      </a>
    </div>
  </div>
</div>

<script>
function notificationManager() {
  return {
    open: false,
    items: [],
    unreadCount: 0,
    popups: [],
    statusPopups: [],
    pollTimer: null,
    frontUrl: '',
    freshUrl: '',
    readBase: '',
    readAllUrl: '',

    init(front, fresh, readBase, readAll) {
      this.frontUrl = front;
      this.freshUrl = fresh;
      this.readBase = readBase;
      this.readAllUrl = readAll;
      this.load();
      this.pollTimer = setInterval(() => this.poll(), 5000);
      // Refresh bell unread count faster
      setInterval(() => this.refreshBell(), 10000);
    },
    toggle() { this.open = !this.open; },
    async load() {
      try {
        const r = await fetch(this.frontUrl, { headers: { 'Accept': 'application/json' } });
        const d = await r.json();
        this.items = d.items.map(i => this.decorate(i));
        this.unreadCount = d.unread_count;
      } catch (e) {}
    },
    async refreshBell() {
      try {
        const r = await fetch(this.frontUrl, { headers: { 'Accept': 'application/json' } });
        const d = await r.json();
        this.unreadCount = d.unread_count;
      } catch (e) {}
    },
    async poll() {
      try {
        const r = await fetch(this.freshUrl, { headers: { 'Accept': 'application/json' } });
        const d = await r.json();
        if (d.count > 0) {
          const newItems = d.items.map(i => this.decorate(i));
          // Avoid dupes
          const existing = new Set(this.items.map(i => i.id));
          const added = newItems.filter(n => !existing.has(n.id));
          if (added.length > 0) {
            this.items = [...added, ...this.items].slice(0, 50);
            this.unreadCount += added.length;
            this.alert(added);
            this.playChime(added.some(n => n.type === 'order'));
          }
        }
      } catch (e) {}
    },
    decorate(n) {
      const colorMap = {
        forest: { badge: 'bg-forest-50 text-forest dark:bg-forest/20 dark:text-green-300', text: 'text-forest dark:text-green-400' },
        sky: { badge: 'bg-sky-50 text-sky-600 dark:bg-sky-900/40 dark:text-sky-300', text: 'text-sky-600 dark:text-sky-300' },
        red: { badge: 'bg-red-50 text-red-600 dark:bg-red-900/40 dark:text-red-400', text: 'text-red-600 dark:text-red-400' },
        amber: { badge: 'bg-amber-50 text-amber-600 dark:bg-amber-900/40 dark:text-amber-400', text: 'text-amber-600 dark:text-amber-400' },
      };
      const c = colorMap[n.color] || colorMap.forest;
      const iconMap = {
        'shopping-bag': '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
        'refresh-cw': '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>',
        'alert-triangle': '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>',
        'package': '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>',
      };
      return { ...n, badgeClass: c.badge, textClass: c.text, codicon: iconMap[n.icon] || iconMap['shopping-bag'] };
    },
    alert(added) {
      added.forEach((n, i) => {
        const pop = { ...n, id: n.id + '-' + Date.now(), tag: n.type === 'order' ? 'New Order' : 'Status Update' };
        setTimeout(() => this.pushPopup(pop), i * 900);
      });
    },
    pushPopup(pop) {
      if (pop.type === 'order') {
        this.popups.push(pop);
        setTimeout(() => this.popups = this.popups.filter(p => p.id !== pop.id), 8000);
      } else {
        this.statusPopups.push(pop);
        setTimeout(() => this.statusPopups = this.statusPopups.filter(p => p.id !== pop.id), 6000);
      }
    },
    dismissPopup(id) { this.popups = this.popups.filter(p => p.id !== id); },
    dismissStatusPopup(id) { this.statusPopups = this.statusPopups.filter(p => p.id !== id); },
    goTo(url) { if (url) window.location = url; },
    async markRead(n) {
      if (!n.read) {
        n.read = true;
        this.unreadCount = Math.max(0, this.unreadCount - 1);
        try { await fetch(this.readBase + '/' + n.id + '/read', { method: 'POST', headers: { 'Accept': 'application/json' } }); } catch(e) {}
      }
      if (n.url) window.location = n.url;
    },
    async markAllRead() {
      try { await fetch(this.readAllUrl, { method: 'POST', headers: { 'Accept': 'application/json' } }); } catch(e) {}
      this.items.forEach(i => i.read = true);
      this.unreadCount = 0;
    },
    playChime(isOrder) {
      // Web Audio chime — no external file needed
      try {
        const Ctx = window.AudioContext || window.webkitAudioContext;
        const ctx = new Ctx();
        const notes = isOrder ? [523.25, 659.25, 783.99, 1046.5] : [659.25, 783.99];
        notes.forEach((freq, i) => {
          const osc = ctx.createOscillator();
          const gain = ctx.createGain();
          osc.type = 'sine';
          osc.frequency.value = freq;
          const t = ctx.currentTime + i * 0.16;
          gain.gain.setValueAtTime(0.0001, t);
          gain.gain.exponentialRampToValueAtTime(0.18, t + 0.02);
          gain.gain.exponentialRampToValueAtTime(0.0001, t + 0.5);
          osc.connect(gain);
          gain.connect(ctx.destination);
          osc.start(t);
          osc.stop(t + 0.5);
        });
      } catch (e) {}
    },
  };
}
</script>
