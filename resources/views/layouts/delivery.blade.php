<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth" x-data="darkMode()" :class="{ 'dark': dark }">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? 'Delivery' }} — {{ setting('store.name') }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,600;9..144,700&display=swap" rel="stylesheet">
  <link rel="icon" href="{{ asset('storage/sections/app-icon.jpg') }}" type="image/jpeg">
  @php $css = @file_get_contents(public_path('static/assets/app-CwmQRRjJ.css')); @endphp
  <style>{!! $css !!}</style>
  <script>
    function darkMode() {
      return {
        dark: localStorage.getItem('darkMode') === 'true',
        toggle() {
          this.dark = !this.dark;
          localStorage.setItem('darkMode', this.dark);
        }
      }
    }
  </script>
</head>
<body class="min-h-screen bg-gray-50 font-sans text-gray-900 antialiased dark:bg-gray-950 dark:text-gray-200" x-data="{ sidebarOpen: false }">

  {{-- ==================== SIDEBAR ==================== --}}
  <aside
    class="fixed inset-y-0 left-0 z-40 flex w-64 flex-col border-r border-gray-200 bg-white shadow-sm transition-transform duration-300 lg:translate-x-0 dark:border-gray-800 dark:bg-gray-900"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
  >
    {{-- Logo --}}
    <div class="flex h-16 shrink-0 items-center gap-3 border-b border-gray-200 px-5 dark:border-gray-800">
      <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-600 text-sm font-bold text-white shadow-sm">AO</span>
      <div class="min-w-0">
        <div class="truncate text-sm font-bold text-gray-900 dark:text-white">{{ setting('store.name') }}</div>
        <div class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500">Delivery Portal</div>
      </div>
    </div>

    {{-- Navigation --}}
    @php $current = request()->route()->getName(); @endphp
    <nav class="flex-1 overflow-y-auto px-3 py-4 text-sm space-y-1">
      <a href="{{ route('delivery.dashboard') }}"
         class="group flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium transition
                {{ $current === 'delivery.dashboard'
                  ? 'border-l-[3px] border-emerald-600 bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'
                  : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white' }}">
        <x-lucide-layout-dashboard class="h-4 w-4 shrink-0" />
        <span>Dashboard</span>
      </a>

      <a href="{{ route('delivery.deliveries') }}"
         class="group flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium transition
                {{ str_starts_with($current, 'delivery.show') || $current === 'delivery.deliveries'
                  ? 'border-l-[3px] border-emerald-600 bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'
                  : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white' }}">
        <x-lucide-truck class="h-4 w-4 shrink-0" />
        <span>My Deliveries</span>
      </a>

      <a href="{{ route('delivery.cod') }}"
         class="group flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium transition
                {{ $current === 'delivery.cod'
                  ? 'border-l-[3px] border-emerald-600 bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'
                  : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white' }}">
        <x-lucide-wallet class="h-4 w-4 shrink-0" />
        <span>COD Collections</span>
      </a>

      <a href="{{ route('delivery.profile') }}"
         class="group flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium transition
                {{ $current === 'delivery.profile'
                  ? 'border-l-[3px] border-emerald-600 bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'
                  : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white' }}">
        <x-lucide-user class="h-4 w-4 shrink-0" />
        <span>My Profile</span>
      </a>
    </nav>

    {{-- User Info --}}
    <div class="shrink-0 border-t border-gray-200 p-4 dark:border-gray-800">
      <div class="flex items-center gap-3">
        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-xs font-bold text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400">
          {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
        </div>
        <div class="min-w-0 flex-1">
          <div class="truncate text-sm font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name }}</div>
          <div class="truncate text-[11px] text-gray-400 dark:text-gray-500">Delivery Partner</div>
        </div>
      </div>
      <form action="{{ route('logout') }}" method="POST" class="mt-3">
        @csrf
        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-500 transition hover:border-red-200 hover:bg-red-50 hover:text-red-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:border-red-900 dark:hover:bg-red-950 dark:hover:text-red-400">
          <x-lucide-log-out class="h-3.5 w-3.5" />
          Logout
        </button>
      </form>
    </div>
  </aside>

  {{-- Mobile Sidebar Overlay --}}
  <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak x-transition:enter="transition-opacity ease-out duration-200" x-transition:leave="transition-opacity ease-in duration-150" class="fixed inset-0 z-30 bg-black/50 backdrop-blur-sm lg:hidden"></div>

  {{-- ==================== TOAST NOTIFICATIONS ==================== --}}
  @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="fixed top-4 right-4 z-50 flex items-center gap-3 rounded-xl border border-emerald-200 bg-white px-4 py-3 text-sm text-emerald-700 shadow-lg max-w-sm dark:border-emerald-800 dark:bg-gray-900 dark:text-emerald-400">
      <x-lucide-check-circle class="h-4 w-4 shrink-0 text-emerald-500 dark:text-emerald-400" />
      <span class="flex-1">{{ session('success') }}</span>
      <button @click="show = false" class="shrink-0 rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:text-gray-500 dark:hover:bg-gray-800 dark:hover:text-white">
        <x-lucide-x class="h-3.5 w-3.5" />
      </button>
    </div>
  @endif

  @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="fixed top-4 right-4 z-50 flex items-center gap-3 rounded-xl border border-red-200 bg-white px-4 py-3 text-sm text-red-600 shadow-lg max-w-sm dark:border-red-900 dark:bg-gray-900 dark:text-red-400">
      <x-lucide-alert-circle class="h-4 w-4 shrink-0 text-red-500 dark:text-red-400" />
      <span class="flex-1">{{ session('error') }}</span>
      <button @click="show = false" class="shrink-0 rounded-lg p-1 text-gray-400 hover:bg-red-50 hover:text-red-600 dark:text-gray-500 dark:hover:bg-gray-800 dark:hover:text-white">
        <x-lucide-x class="h-3.5 w-3.5" />
      </button>
    </div>
  @endif

  {{-- ==================== MAIN CONTENT ==================== --}}
  <div class="lg:pl-64">

    {{-- Top Header --}}
    <header class="sticky top-0 z-30 flex h-16 items-center gap-4 border-b border-gray-200 bg-white/80 px-4 backdrop-blur-md shadow-sm sm:px-6 dark:border-gray-800 dark:bg-gray-900/80 dark:shadow-none">

      {{-- Mobile Hamburger --}}
      <button @click="sidebarOpen = !sidebarOpen" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 lg:hidden dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white">
        <x-lucide-menu class="h-5 w-5" />
      </button>

      {{-- Page Title --}}
      <div class="flex-1">
        <h1 class="text-base font-semibold text-gray-900 sm:text-lg dark:text-white">{{ $title ?? 'Dashboard' }}</h1>
      </div>

      {{-- Right Actions --}}
      <div class="flex items-center gap-1">
        {{-- Live Indicator --}}
        <span class="hidden sm:inline-flex items-center gap-1.5 rounded-lg border px-2.5 py-1.5 text-[11px] font-bold text-emerald-600
          border-emerald-200 bg-emerald-50 dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
          <span class="relative flex h-2 w-2">
            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-500 opacity-75"></span>
            <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
          </span>
          LIVE
        </span>

        {{-- Manual Refresh --}}
        <button onclick="window.location.reload()" class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white" title="Refresh now">
          <x-lucide-refresh-cw class="h-5 w-5" />
        </button>

        {{-- Availability Indicator --}}
        @php $person = auth()->user()->deliveryPerson; @endphp
        @if($person)
          <div class="hidden items-center gap-2 rounded-lg border px-3 py-1.5 text-xs font-semibold sm:flex
            {{ $person->is_available
              ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400'
              : 'border-gray-200 bg-gray-100 text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400' }}">
            <span class="h-2 w-2 rounded-full {{ $person->is_available ? 'bg-emerald-500 animate-pulse' : 'bg-gray-400' }}"></span>
            {{ $person->is_available ? 'Online' : 'Offline' }}
          </div>
        @endif

        {{-- Dark Mode Toggle --}}
        <button @click="toggle()" class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white" title="Toggle dark mode">
          <template x-if="dark">
            <x-lucide-sun class="h-5 w-5" />
          </template>
          <template x-if="!dark">
            <x-lucide-moon class="h-5 w-5" />
          </template>
        </button>
      </div>
    </header>

    {{-- Page Content --}}
    <main class="p-4 sm:p-6">
      {{ $slot ?? '' }}
      @yield('content')
    </main>
  </div>

  @stack('scripts')
  @php $js = @file_get_contents(public_path('static/assets/app-CfqTcJ9y.js')); @endphp
  <script>{!! $js !!}</script>
</body>
</html>
