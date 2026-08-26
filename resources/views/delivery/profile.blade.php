@extends('layouts.delivery')

@section('title', 'My Profile')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">

  {{-- Page Header --}}
  <div>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Profile</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage your delivery profile and availability.</p>
  </div>

  <div class="grid gap-6 lg:grid-cols-[1fr_380px]">

    {{-- Left Column --}}
    <div class="space-y-6">

      {{-- Profile Details --}}
      <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <h2 class="mb-5 flex items-center gap-2 text-sm font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
          <x-lucide-user class="h-4 w-4" />
          Profile Details
        </h2>
        <div class="space-y-1">
          <div class="flex items-center justify-between rounded-xl px-4 py-3 transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
            <span class="text-sm text-gray-500 dark:text-gray-400">Name</span>
            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $person->user->name ?? '—' }}</span>
          </div>
          <div class="flex items-center justify-between rounded-xl px-4 py-3 transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
            <span class="text-sm text-gray-500 dark:text-gray-400">Email</span>
            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $person->user->email ?? '—' }}</span>
          </div>
          <div class="flex items-center justify-between rounded-xl px-4 py-3 transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
            <span class="text-sm text-gray-500 dark:text-gray-400">Phone</span>
            @if($person->user->phone)
              <a href="tel:{{ $person->user->phone }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-600 transition hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300">
                <x-lucide-phone class="h-3.5 w-3.5" />
                {{ $person->user->phone }}
              </a>
            @else
              <span class="text-sm font-semibold text-gray-900 dark:text-white">—</span>
            @endif
          </div>
          <div class="flex items-center justify-between rounded-xl px-4 py-3 transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
            <span class="text-sm text-gray-500 dark:text-gray-400">Vehicle Type</span>
            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $person->vehicle_type ?? '—' }}</span>
          </div>
          <div class="flex items-center justify-between rounded-xl px-4 py-3 transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
            <span class="text-sm text-gray-500 dark:text-gray-400">License Plate</span>
            @if($person->license_plate)
              <span class="inline-flex items-center rounded-lg bg-gray-100 px-3 py-1 text-sm font-mono font-bold text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                {{ $person->license_plate }}
              </span>
            @else
              <span class="text-sm font-semibold text-gray-900 dark:text-white">—</span>
            @endif
          </div>
          <div class="flex items-center justify-between rounded-xl px-4 py-3 transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
            <span class="text-sm text-gray-500 dark:text-gray-400">Availability</span>
            @if($person->is_available)
              <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                Available
              </span>
            @else
              <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                Unavailable
              </span>
            @endif
          </div>
        </div>
      </section>

      {{-- Availability Toggle --}}
      <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <h2 class="mb-4 text-sm font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
          Delivery Status
        </h2>
        <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-800/50">
          <div class="flex items-center justify-between gap-4">
            <div>
              <p class="text-sm font-semibold text-gray-900 dark:text-white">
                {{ $person->is_available ? 'You are online' : 'You are offline' }}
              </p>
              <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                {{ $person->is_available ? 'You will receive new delivery assignments.' : 'You will not receive new assignments.' }}
              </p>
            </div>
            <form action="{{ route('delivery.profile.toggle') }}" method="POST">
              @csrf
              @if($person->is_available)
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-red-200 bg-white px-5 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-50 dark:border-red-900 dark:bg-transparent dark:text-red-400 dark:hover:bg-red-950">
                  <x-lucide-x-circle class="h-4 w-4" />
                  Go Offline
                </button>
              @else
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 hover:shadow-md">
                  <x-lucide-check-circle class="h-4 w-4" />
                  Go Online
                </button>
              @endif
            </form>
          </div>
        </div>
      </section>
    </div>

    {{-- Right Column (Stats) --}}
    <div class="space-y-4 lg:sticky lg:top-24 lg:self-start">

      {{-- Delivery Stats --}}
      <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <h3 class="mb-4 flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
          <x-lucide-bar-chart-3 class="h-3.5 w-3.5" />
          Delivery Stats
        </h3>
        <div class="space-y-3">
          <div class="flex items-center justify-between text-sm">
            <span class="text-gray-500 dark:text-gray-400">Total Deliveries</span>
            <span class="font-bold text-gray-900 dark:text-white">{{ $stats['total'] ?? 0 }}</span>
          </div>
          <div class="flex items-center justify-between text-sm">
            <span class="text-gray-500 dark:text-gray-400">Successful</span>
            <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ $stats['successful'] ?? 0 }}</span>
          </div>
          <div class="flex items-center justify-between text-sm">
            <span class="text-gray-500 dark:text-gray-400">Failed</span>
            <span class="font-bold text-red-600 dark:text-red-400">{{ $stats['failed'] ?? 0 }}</span>
          </div>
          <div class="border-t border-gray-100 pt-3 dark:border-gray-800"></div>
          <div class="flex items-center justify-between text-sm">
            <span class="text-gray-500 dark:text-gray-400">COD Collected</span>
            <span class="font-bold text-orange-600 dark:text-orange-400">₹{{ number_format($stats['cod_collected'] ?? 0) }}</span>
          </div>
          <div class="border-t border-gray-100 pt-3 dark:border-gray-800"></div>
          <div class="flex items-center justify-between text-sm">
            <span class="text-gray-500 dark:text-gray-400">Today's Deliveries</span>
            <div class="flex items-center gap-1.5">
              <x-lucide-calendar class="h-3.5 w-3.5 text-emerald-500" />
              <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ $stats['today'] ?? 0 }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
