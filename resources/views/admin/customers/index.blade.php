@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
<div class="space-y-6" x-data="{ deleteId: null }">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-forest/10 dark:bg-forest/20">
                <x-lucide-users class="h-5 w-5 text-forest dark:text-green-400" />
            </div>
            <div>
                <h1 class="adm-page-title">Customers</h1>
                <p class="text-xs adm-text-muted">{{ $customers->total() }} total customers</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="adm-page-count">
                <x-lucide-users class="h-3 w-3" />
                {{ $customers->total() }}
            </span>
            <x-admin.export-btn url="{{ route('admin.customers.index', array_merge(request()->query(), ['export' => 'csv'])) }}" />
        </div>
    </div>

    {{-- Search --}}
    <x-admin.filter-bar action="{{ route('admin.customers.index') }}">
        <div class="relative flex-1 min-w-[200px]">
            <x-lucide-search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-charcoal/30 dark:text-gray-500" />
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by name, email or phone..."
                class="adm-input !pl-9" />
        </div>
    </x-admin.filter-bar>

    {{-- Table --}}
    <div class="adm-table-wrap">
        {{-- Desktop Table --}}
        <div class="hidden overflow-x-auto md:block">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Orders</th>
                        <th>Joined</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        @php
                            $colors = ['bg-forest', 'bg-blue-500', 'bg-purple-500', 'bg-amber-500', 'bg-rose-500', 'bg-teal-500', 'bg-indigo-500'];
                            $colorIndex = crc32($customer->email) % count($colors);
                            $initials = strtoupper(substr($customer->name, 0, 1));
                        @endphp
                        <tr class="group">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full {{ $colors[$colorIndex] }} text-xs font-bold text-white shadow-sm">
                                        {{ $initials }}
                                    </div>
                                    <span class="text-sm font-semibold text-charcoal dark:text-white">{{ $customer->name }}</span>
                                </div>
                            </td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone ?? '—' }}</td>
                            <td>
                                <span class="inline-flex items-center gap-1 rounded-full bg-charcoal/[0.04] px-2 py-0.5 text-xs font-semibold text-charcoal/60 dark:bg-gray-700 dark:text-gray-300">
                                    <x-lucide-shopping-bag class="h-3 w-3" />
                                    {{ $customer->orders_count }}
                                </span>
                            </td>
                            <td class="adm-text-muted">{{ $customer->created_at->format('M d, Y') }}</td>
                            <td>
                                <x-admin.status-badge :status="$customer->is_active ? 'active' : 'inactive'" />
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.customers.show', $customer) }}"
                                       class="adm-action-link"
                                       title="View customer">
                                        <x-lucide-eye class="h-3.5 w-3.5" />
                                        View
                                    </a>
                                    @if($customer->is_active)
                                        <form method="POST" action="{{ route('admin.customers.block', $customer) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="adm-btn-danger"
                                                title="Block customer"
                                                onclick="return confirm('Block this customer?')">
                                                <x-lucide-ban class="h-3.5 w-3.5" />
                                                Block
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.customers.unblock', $customer) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="adm-action-link"
                                                title="Unblock customer">
                                                <x-lucide-check-circle class="h-3.5 w-3.5" />
                                                Unblock
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-admin.empty-state
                                    icon="users"
                                    title="No customers found"
                                    description="{{ request('q') ? 'Try a different search term.' : 'Customers will appear here once they register.' }}" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="divide-y divide-sage/10 md:hidden dark:divide-gray-700/50">
            @forelse($customers as $customer)
                @php
                    $colors = ['bg-forest', 'bg-blue-500', 'bg-purple-500', 'bg-amber-500', 'bg-rose-500', 'bg-teal-500', 'bg-indigo-500'];
                    $colorIndex = crc32($customer->email) % count($colors);
                    $initials = strtoupper(substr($customer->name, 0, 1));
                @endphp
                <div class="p-4 transition hover:bg-charcoal/[0.02] dark:hover:bg-gray-700/30">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $colors[$colorIndex] }} text-sm font-bold text-white shadow-sm">
                                {{ $initials }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-charcoal dark:text-white">{{ $customer->name }}</p>
                                <p class="text-xs adm-text-muted">{{ $customer->email }}</p>
                            </div>
                        </div>
                        <x-admin.status-badge :status="$customer->is_active ? 'active' : 'inactive'" />
                    </div>
                    <div class="mt-3 flex items-center gap-4 text-xs adm-text-muted">
                        <span class="inline-flex items-center gap-1">
                            <x-lucide-phone class="h-3 w-3" />
                            {{ $customer->phone ?? '—' }}
                        </span>
                        <span class="inline-flex items-center gap-1">
                            <x-lucide-shopping-bag class="h-3 w-3" />
                            {{ $customer->orders_count }} orders
                        </span>
                        <span class="inline-flex items-center gap-1">
                            <x-lucide-calendar class="h-3 w-3" />
                            {{ $customer->created_at->format('M d, Y') }}
                        </span>
                    </div>
                    <div class="mt-3 flex items-center gap-2">
                        <a href="{{ route('admin.customers.show', $customer) }}"
                           class="adm-action-link">
                            <x-lucide-eye class="h-3.5 w-3.5" />
                            View
                        </a>
                        @if($customer->is_active)
                            <form method="POST" action="{{ route('admin.customers.block', $customer) }}" class="inline">
                                @csrf
                                <button type="submit" onclick="return confirm('Block this customer?')"
                                    class="adm-btn-danger">
                                    <x-lucide-ban class="h-3.5 w-3.5" />
                                    Block
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.customers.unblock', $customer) }}" class="inline">
                                @csrf
                                <button type="submit"
                                    class="adm-action-link">
                                    <x-lucide-check-circle class="h-3.5 w-3.5" />
                                    Unblock
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <x-admin.empty-state
                    icon="users"
                    title="No customers found"
                    description="{{ request('q') ? 'Try a different search term.' : 'Customers will appear here once they register.' }}" />
            @endforelse
        </div>
    </div>

    {{-- Pagination --}}
    @if($customers->hasPages())
        <div class="flex justify-center">
            {{ $customers->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
