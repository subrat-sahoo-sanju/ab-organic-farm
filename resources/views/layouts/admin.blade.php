<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth" x-data="darkMode()" :class="{ 'dark': dark }">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? 'Admin' }} — {{ setting('store.name') }} Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,600;9..144,700&display=swap" rel="stylesheet">
  <link rel="icon" href="{{ asset('storage/sections/app-icon.jpg') }}" type="image/jpeg">
  @php $css = @file_get_contents(public_path('static/assets/app-DFSc02Nj.css')); @endphp
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
<body class="min-h-screen admin-bg font-sans text-charcoal antialiased" x-data="{ sidebarOpen: false }">

  {{-- ==================== SIDEBAR ==================== --}}
  <aside
    class="fixed inset-y-0 left-0 z-40 flex w-64 flex-col border-r border-gray-200 bg-white shadow-sm transition-transform duration-300 lg:translate-x-0 dark:border-gray-700 dark:bg-gray-900"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
  >
    {{-- Logo --}}
    <div class="flex h-16 shrink-0 items-center gap-3 border-b border-gray-200 px-5 dark:border-gray-700">
      <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-forest text-sm font-bold text-white shadow-sm">AO</span>
      <div class="min-w-0">
        <div class="truncate text-sm font-bold text-charcoal dark:text-white">{{ setting('store.name') }}</div>
        <div class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500">Admin</div>
      </div>
    </div>

    {{-- Navigation --}}
    @php $current = request()->route()->getName(); @endphp
    <nav class="flex-1 overflow-y-auto px-3 py-4 text-sm space-y-1">
      {{-- Dashboard --}}
      <a href="{{ route('admin.dashboard') }}"
         class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium transition
                {{ str_starts_with($current, 'admin.dashboard')
                  ? 'border-l-[3px] border-forest bg-forest/10 text-forest dark:bg-forest/20 dark:text-green-400'
                  : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white' }}">
        <x-lucide-layout-dashboard class="h-4 w-4 shrink-0" />
        <span>Dashboard</span>
      </a>

      {{-- Catalogue --}}
      <div class="pt-4 pb-1 px-3 text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Catalogue</div>

      <a href="{{ route('admin.products.index') }}"
         class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium transition
                {{ str_starts_with($current, 'admin.products')
                  ? 'border-l-[3px] border-forest bg-forest/10 text-forest dark:bg-forest/20 dark:text-green-400'
                  : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white' }}">
        <x-lucide-package class="h-4 w-4 shrink-0" />
        <span>Products</span>
      </a>

      <a href="{{ route('admin.categories.index') }}"
         class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium transition
                {{ str_starts_with($current, 'admin.categories')
                  ? 'border-l-[3px] border-forest bg-forest/10 text-forest dark:bg-forest/20 dark:text-green-400'
                  : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white' }}">
        <x-lucide-tag class="h-4 w-4 shrink-0" />
        <span>Categories</span>
      </a>

      <a href="{{ route('admin.brands.index') }}"
         class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium transition
                {{ str_starts_with($current, 'admin.brands')
                  ? 'border-l-[3px] border-forest bg-forest/10 text-forest dark:bg-forest/20 dark:text-green-400'
                  : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white' }}">
        <x-lucide-award class="h-4 w-4 shrink-0" />
        <span>Brands</span>
      </a>

      <a href="{{ route('admin.inventory.index') }}"
         class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium transition
                {{ str_starts_with($current, 'admin.inventory')
                  ? 'border-l-[3px] border-forest bg-forest/10 text-forest dark:bg-forest/20 dark:text-green-400'
                  : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white' }}">
        <x-lucide-clipboard-list class="h-4 w-4 shrink-0" />
        <span>Inventory</span>
      </a>

      {{-- Orders --}}
      <div class="pt-4 pb-1 px-3 text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Orders</div>

      <a href="{{ route('admin.orders.index') }}"
         class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium transition
                {{ str_starts_with($current, 'admin.orders')
                  ? 'border-l-[3px] border-forest bg-forest/10 text-forest dark:bg-forest/20 dark:text-green-400'
                  : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white' }}">
        <x-lucide-shopping-cart class="h-4 w-4 shrink-0" />
        <span>Orders</span>
      </a>

      <a href="{{ route('admin.delivery.index') }}"
         class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium transition
                {{ str_starts_with($current, 'admin.delivery')
                  ? 'border-l-[3px] border-forest bg-forest/10 text-forest dark:bg-forest/20 dark:text-green-400'
                  : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white' }}">
        <x-lucide-truck class="h-4 w-4 shrink-0" />
        <span>Delivery</span>
      </a>

      {{-- Marketing --}}
      <div class="pt-4 pb-1 px-3 text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Marketing</div>

      <a href="{{ route('admin.banners.index') }}"
         class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium transition
                {{ str_starts_with($current, 'admin.banners')
                  ? 'border-l-[3px] border-forest bg-forest/10 text-forest dark:bg-forest/20 dark:text-green-400'
                  : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white' }}">
        <x-lucide-image class="h-4 w-4 shrink-0" />
        <span>Banners</span>
      </a>

      <a href="{{ route('admin.coupons.index') }}"
         class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium transition
                {{ str_starts_with($current, 'admin.coupons')
                  ? 'border-l-[3px] border-forest bg-forest/10 text-forest dark:bg-forest/20 dark:text-green-400'
                  : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white' }}">
        <x-lucide-ticket class="h-4 w-4 shrink-0" />
        <span>Coupons</span>
      </a>

      <a href="{{ route('admin.reviews.index') }}"
         class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium transition
                {{ str_starts_with($current, 'admin.reviews')
                  ? 'border-l-[3px] border-forest bg-forest/10 text-forest dark:bg-forest/20 dark:text-green-400'
                  : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white' }}">
        <x-lucide-star class="h-4 w-4 shrink-0" />
        <span>Reviews</span>
      </a>

      <a href="{{ route('admin.pages.index') }}"
         class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium transition
                {{ str_starts_with($current, 'admin.pages')
                  ? 'border-l-[3px] border-forest bg-forest/10 text-forest dark:bg-forest/20 dark:text-green-400'
                  : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white' }}">
        <x-lucide-file-text class="h-4 w-4 shrink-0" />
        <span>Pages &amp; Policies</span>
      </a>

      {{-- People --}}
      <div class="pt-4 pb-1 px-3 text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">People</div>

      <a href="{{ route('admin.customers.index') }}"
         class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium transition
                {{ str_starts_with($current, 'admin.customers')
                  ? 'border-l-[3px] border-forest bg-forest/10 text-forest dark:bg-forest/20 dark:text-green-400'
                  : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white' }}">
        <x-lucide-users class="h-4 w-4 shrink-0" />
        <span>Customers</span>
      </a>

      <a href="{{ route('admin.staff.index') }}"
         class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium transition
                {{ str_starts_with($current, 'admin.staff')
                  ? 'border-l-[3px] border-forest bg-forest/10 text-forest dark:bg-forest/20 dark:text-green-400'
                  : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white' }}">
        <x-lucide-shield class="h-4 w-4 shrink-0" />
        <span>Staff</span>
      </a>

      <a href="{{ route('admin.delivery-persons.index') }}"
         class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium transition
                {{ str_starts_with($current, 'admin.delivery-persons')
                  ? 'border-l-[3px] border-forest bg-forest/10 text-forest dark:bg-forest/20 dark:text-green-400'
                  : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white' }}">
        <x-lucide-bike class="h-4 w-4 shrink-0" />
        <span>Delivery Staff</span>
      </a>

      {{-- System --}}
      <div class="pt-4 pb-1 px-3 text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">System</div>

      <a href="{{ route('admin.reports.index') }}"
         class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium transition
                {{ str_starts_with($current, 'admin.reports')
                  ? 'border-l-[3px] border-forest bg-forest/10 text-forest dark:bg-forest/20 dark:text-green-400'
                  : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white' }}">
        <x-lucide-bar-chart-3 class="h-4 w-4 shrink-0" />
        <span>Reports</span>
      </a>

      <a href="{{ route('admin.settings.show') }}"
         class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium transition
                {{ str_starts_with($current, 'admin.settings')
                  ? 'border-l-[3px] border-forest bg-forest/10 text-forest dark:bg-forest/20 dark:text-green-400'
                  : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white' }}">
        <x-lucide-settings class="h-4 w-4 shrink-0" />
        <span>Settings</span>
      </a>
    </nav>

    {{-- User Info --}}
    <div class="shrink-0 border-t border-gray-200 p-4 dark:border-gray-700">
      <div class="flex items-center gap-3">
        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-forest/10 text-xs font-bold text-forest dark:bg-forest/20 dark:text-green-400">
          {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
        </div>
        <div class="min-w-0 flex-1">
          <div class="truncate text-sm font-semibold text-charcoal dark:text-white">{{ auth()->user()->name }}</div>
          <div class="truncate text-[11px] text-gray-400 dark:text-gray-500">{{ auth()->user()->roles->pluck('name')->implode(', ') }}</div>
        </div>
      </div>
      <form action="{{ route('logout') }}" method="POST" class="mt-3">
        @csrf
        <button type="submit" class="adm-btn-outline w-full text-xs !py-2 hover:!border-red-300 hover:!text-red-600 dark:hover:!border-red-800 dark:hover:!text-red-400">
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
         class="fixed top-4 right-4 z-50 flex items-center gap-3 rounded-xl border border-forest/20 bg-white px-4 py-3 text-sm text-forest shadow-lg max-w-sm dark:border-green-800 dark:bg-gray-900 dark:text-green-400">
      <x-lucide-check-circle class="h-4 w-4 shrink-0 text-forest dark:text-green-400" />
      <span class="flex-1">{{ session('success') }}</span>
      <button @click="show = false" class="shrink-0 rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-500 dark:hover:bg-gray-800 dark:hover:text-white">
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

  @if(session('warning'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="fixed top-4 right-4 z-50 flex items-center gap-3 rounded-xl border border-amber-200 bg-white px-4 py-3 text-sm text-amber-600 shadow-lg max-w-sm dark:border-amber-900 dark:bg-gray-900 dark:text-amber-400">
      <x-lucide-alert-triangle class="h-4 w-4 shrink-0 text-amber-500 dark:text-amber-400" />
      <span class="flex-1">{{ session('warning') }}</span>
      <button @click="show = false" class="shrink-0 rounded-lg p-1 text-gray-400 hover:bg-amber-50 hover:text-amber-600 dark:text-gray-500 dark:hover:bg-gray-800 dark:hover:text-white">
        <x-lucide-x class="h-3.5 w-3.5" />
      </button>
    </div>
  @endif

  @if(session('info'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="fixed top-4 right-4 z-50 flex items-center gap-3 rounded-xl border border-blue-200 bg-white px-4 py-3 text-sm text-blue-600 shadow-lg max-w-sm dark:border-blue-900 dark:bg-gray-900 dark:text-blue-400">
      <x-lucide-info class="h-4 w-4 shrink-0 text-blue-500 dark:text-blue-400" />
      <span class="flex-1">{{ session('info') }}</span>
      <button @click="show = false" class="shrink-0 rounded-lg p-1 text-gray-400 hover:bg-blue-50 hover:text-blue-600 dark:text-gray-500 dark:hover:bg-gray-800 dark:hover:text-white">
        <x-lucide-x class="h-3.5 w-3.5" />
      </button>
    </div>
  @endif

  {{-- ==================== MAIN CONTENT ==================== --}}
  <div class="lg:pl-64">

    {{-- Top Header --}}
    <header class="sticky top-0 z-30 flex h-16 items-center gap-4 border-b border-gray-200 bg-white/80 px-4 backdrop-blur-md shadow-sm sm:px-6 dark:border-gray-700 dark:bg-gray-900/80 dark:shadow-none">

      {{-- Mobile Hamburger --}}
      <button @click="sidebarOpen = !sidebarOpen" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900 lg:hidden dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white">
        <x-lucide-menu class="h-5 w-5" />
      </button>

      {{-- Page Title --}}
      <div class="flex-1">
        <h1 class="text-base font-semibold text-charcoal sm:text-lg dark:text-white">{{ $title ?? 'Dashboard' }}</h1>
      </div>

      {{-- Right Actions --}}
      <div class="flex items-center gap-1">

        {{-- View Store --}}
        <a href="{{ route('shop.index') }}" target="_blank" rel="noopener noreferrer"
           class="hidden items-center gap-1.5 rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-semibold text-gray-500 transition hover:border-green-300 hover:text-green-700 sm:flex dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:border-green-700 dark:hover:text-green-400">
          <x-lucide-external-link class="h-3.5 w-3.5" />
          <span>View Store</span>
        </a>

        {{-- Dark Mode Toggle --}}
        <button @click="toggle()" class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-gray-800 dark:hover:text-white" title="Toggle dark mode">
          <template x-if="dark">
            <x-lucide-sun class="h-5 w-5" />
          </template>
          <template x-if="!dark">
            <x-lucide-moon class="h-5 w-5" />
          </template>
        </button>

        {{-- Live Notifications Bell --}}
        <x-admin.notifications />

        {{-- Mobile View Store --}}
        <a href="{{ route('shop.index') }}" target="_blank" rel="noopener noreferrer"
           class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-900 sm:hidden dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white" title="View Store">
          <x-lucide-external-link class="h-5 w-5" />
        </a>
      </div>
    </header>

    {{-- Page Content --}}
    <main class="p-4 sm:p-6">
      {{ $slot ?? '' }}
      @yield('content')
    </main>
  </div>

  @stack('scripts')
  @php $js = @file_get_contents(public_path('static/assets/app-CohnTwkU.js')); @endphp
  <script>{!! $js !!}</script>
</body>
</html>
