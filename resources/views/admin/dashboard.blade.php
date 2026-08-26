@extends('layouts.admin', ['title' => 'Dashboard'])

@section('content')
<div class="space-y-6">

  {{-- ───────── KPI Stat Cards ───────── --}}
  <div class="adm-grid-5">

    {{-- Revenue --}}
    <div class="adm-stat">
      <div class="absolute inset-y-0 left-0 w-1 bg-green-500"></div>
      <div class="flex items-start justify-between">
        <div>
          <p class="adm-stat-label">Revenue (Month)</p>
          <p class="mt-2 text-3xl font-bold adm-text-primary">₹{{ number_format($stats['revenue_month']) }}</p>
          <p class="mt-1 text-xs text-green-600">₹{{ number_format($stats['sales_today']) }} today</p>
        </div>
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-green-50">
          <x-lucide-indian-rupee class="h-5 w-5 text-green-600" />
        </div>
      </div>
    </div>

    {{-- Orders --}}
    <div class="adm-stat">
      <div class="absolute inset-y-0 left-0 w-1 bg-blue-500"></div>
      <div class="flex items-start justify-between">
        <div>
          <p class="adm-stat-label">Total Orders</p>
          <p class="mt-2 text-3xl font-bold adm-text-primary">{{ number_format($stats['orders_total']) }}</p>
          <p class="mt-1 text-xs text-blue-600">{{ $stats['orders_today'] }} today · {{ $stats['orders_pending'] }} pending</p>
        </div>
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-blue-50">
          <x-lucide-shopping-cart class="h-5 w-5 text-blue-600" />
        </div>
      </div>
    </div>

    {{-- Customers --}}
    <div class="adm-stat">
      <div class="absolute inset-y-0 left-0 w-1 bg-purple-500"></div>
      <div class="flex items-start justify-between">
        <div>
          <p class="adm-stat-label">Customers</p>
          <p class="mt-2 text-3xl font-bold adm-text-primary">{{ number_format($stats['customers']) }}</p>
          <p class="mt-1 text-xs text-purple-600">+{{ $stats['new_customers_7d'] }} this week</p>
        </div>
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-purple-50">
          <x-lucide-users class="h-5 w-5 text-purple-600" />
        </div>
      </div>
    </div>

    {{-- AOV --}}
    <div class="adm-stat">
      <div class="absolute inset-y-0 left-0 w-1 bg-amber-500"></div>
      <div class="flex items-start justify-between">
        <div>
          <p class="adm-stat-label">Avg. Order Value</p>
          <p class="mt-2 text-3xl font-bold adm-text-primary">₹{{ number_format($stats['aov']) }}</p>
          <p class="mt-1 text-xs adm-text-muted">{{ $stats['products'] }} products live</p>
        </div>
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-amber-50">
          <x-lucide-trending-up class="h-5 w-5 text-amber-600" />
        </div>
      </div>
    </div>

    {{-- Delivered --}}
    <div class="adm-stat">
      <div class="absolute inset-y-0 left-0 w-1 bg-emerald-500"></div>
      <div class="flex items-start justify-between">
        <div>
          <p class="adm-stat-label">Delivered</p>
          <p class="mt-2 text-3xl font-bold text-emerald-600">{{ number_format($stats['orders_delivered']) }}</p>
          <p class="mt-1 text-xs text-emerald-600">{{ $stats['orders_cancelled'] }} cancelled</p>
        </div>
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-emerald-50">
          <x-lucide-check-circle class="h-5 w-5 text-emerald-600" />
        </div>
      </div>
    </div>

  </div>

  {{-- ───────── Mini Stat Cards ───────── --}}
  <div class="adm-grid-3">

    {{-- COD Pending --}}
    <div class="adm-stat flex items-center gap-4 px-5 py-4">
      <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-amber-50">
        <x-lucide-clock class="h-5 w-5 text-amber-600" />
      </div>
      <div>
        <p class="adm-stat-label">COD Pending</p>
        <p class="text-xl font-bold adm-text-primary">₹{{ number_format($stats['cod_pending']) }}</p>
      </div>
    </div>

    {{-- COD Collected --}}
    <div class="adm-stat flex items-center gap-4 px-5 py-4">
      <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-green-50">
        <x-lucide-banknote class="h-5 w-5 text-green-600" />
      </div>
      <div>
        <p class="adm-stat-label">COD Collected</p>
        <p class="text-xl font-bold adm-text-primary">₹{{ number_format($stats['cod_collected']) }}</p>
      </div>
    </div>

    {{-- Low Stock --}}
    <div class="adm-stat flex items-center gap-4 px-5 py-4">
      <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ $stats['low_stock'] > 0 ? 'bg-red-50' : 'bg-green-50' }}">
        <x-lucide-alert-triangle class="h-5 w-5 {{ $stats['low_stock'] > 0 ? 'text-red-600' : 'text-green-600' }}" />
      </div>
      <div>
        <p class="adm-stat-label">Low Stock</p>
        <p class="text-xl font-bold {{ $stats['low_stock'] > 0 ? 'text-red-600' : 'text-green-600' }}">{{ $stats['low_stock'] }} <span class="text-xs font-normal adm-text-muted">variants</span></p>
      </div>
    </div>

  </div>

  {{-- ───────── Sales Chart ───────── --}}
  <div class="adm-section">
    <div class="mb-5 flex items-center justify-between">
      <div>
        <h2 class="adm-section-title mb-0 border-0 pb-0">Sales Overview</h2>
        <p class="text-xs adm-text-muted">Last 30 days revenue trend</p>
      </div>
      <div class="flex items-center gap-4 text-xs">
        <span class="flex items-center gap-1.5"><span class="inline-block h-2 w-2 rounded-full bg-forest"></span> Revenue</span>
      </div>
    </div>
    <div class="h-72" x-data="dashboardChart()">
      <canvas x-ref="chart" x-init="$nextTick(() => renderChart())"></canvas>
    </div>
  </div>

  {{-- ───────── Two-Column: Products & Categories / Status & Recent Orders ───────── --}}
  <div class="adm-grid-2 lg:!grid-cols-2">

    {{-- Left Column --}}
    <div class="space-y-6">

      {{-- Top Products --}}
      <div class="adm-section">
        <h3 class="adm-section-title">Top Products</h3>
        <div class="space-y-3">
          @forelse($topProducts as $i => $row)
            <div class="flex items-center gap-3">
              <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-forest/10 text-[11px] font-bold text-forest">{{ $i + 1 }}</span>
              <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-medium adm-text-primary">{{ $row->name }}</p>
              </div>
              <span class="shrink-0 rounded-full bg-charcoal/5 px-2.5 py-0.5 text-xs font-semibold adm-text-secondary">{{ $row->qty }} sold</span>
            </div>
          @empty
            <p class="text-sm adm-text-muted">No sales data yet.</p>
          @endforelse
        </div>
      </div>

      {{-- Top Categories --}}
      <div class="adm-section">
        <h3 class="adm-section-title">Top Categories</h3>
        <div class="space-y-3">
          @forelse($topCategories as $i => $row)
            <div class="flex items-center gap-3">
              <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-purple-500/10 text-[11px] font-bold text-purple-600">{{ $i + 1 }}</span>
              <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-medium adm-text-primary">{{ $row->name }}</p>
              </div>
              <span class="shrink-0 text-xs font-semibold adm-text-secondary">₹{{ number_format($row->revenue) }}</span>
            </div>
          @empty
            <p class="text-sm adm-text-muted">No category data yet.</p>
          @endforelse
        </div>
      </div>

    </div>

    {{-- Right Column --}}
    <div class="space-y-6">

      {{-- Order Status Distribution --}}
      <div class="adm-section">
        <h3 class="adm-section-title">Order Status</h3>
        @php
          $statusColors = [
              'pending'       => ['bg' => 'bg-amber-500',  'text' => 'text-amber-600'],
              'confirmed'     => ['bg' => 'bg-blue-500',   'text' => 'text-blue-600'],
              'processing'    => ['bg' => 'bg-indigo-500', 'text' => 'text-indigo-600'],
              'out_for_delivery' => ['bg' => 'bg-cyan-500', 'text' => 'text-cyan-600'],
              'delivered'     => ['bg' => 'bg-green-500',  'text' => 'text-green-600'],
              'cancelled'     => ['bg' => 'bg-red-500',    'text' => 'text-red-600'],
              'returned'      => ['bg' => 'bg-orange-500', 'text' => 'text-orange-600'],
          ];
          $totalOrders = $statusDistribution->sum();
        @endphp
        <div class="space-y-3">
          @forelse($statusDistribution as $status => $count)
            @php
              $label = \App\Enums\OrderStatus::tryFrom($status)?->label() ?? ucfirst(str_replace('_', ' ', $status));
              $color = $statusColors[$status] ?? ['bg' => 'bg-gray-500', 'text' => 'text-gray-600'];
              $pct = $totalOrders > 0 ? round(($count / $totalOrders) * 100) : 0;
            @endphp
            <div>
              <div class="mb-1 flex items-center justify-between text-sm">
                <span class="flex items-center gap-2">
                  <span class="inline-block h-2 w-2 rounded-full {{ $color['bg'] }}"></span>
                  <span class="adm-text-secondary">{{ $label }}</span>
                </span>
                <span class="font-semibold adm-text-primary">{{ $count }}</span>
              </div>
              <div class="h-1.5 w-full overflow-hidden rounded-full bg-charcoal/5">
                <div class="{{ $color['bg'] }} h-full rounded-full transition-all" style="width: {{ $pct }}%"></div>
              </div>
            </div>
          @empty
            <p class="text-sm adm-text-muted">No orders yet.</p>
          @endforelse
        </div>
      </div>

      {{-- Recent Orders --}}
      <div class="adm-section">
        <h3 class="adm-section-title">Recent Orders</h3>
        <div class="space-y-1">
          @forelse($recentOrders as $order)
            @php
              $color = $statusColors[$order->status->value] ?? ['bg' => 'bg-gray-500', 'text' => 'text-gray-600'];
            @endphp
            <a href="{{ route('admin.orders.show', $order) }}" class="group flex items-center justify-between rounded-lg px-3 py-2.5 transition hover:bg-charcoal/[0.03]">
              <div class="min-w-0">
                <p class="text-sm font-semibold adm-text-primary group-hover:text-forest">{{ $order->order_number }}</p>
                <p class="text-xs adm-text-muted">{{ $order->user->name ?? 'Guest' }} · {{ $order->placed_at?->diffForHumans() }}</p>
              </div>
              <div class="flex items-center gap-2.5">
                <span class="text-sm font-semibold adm-text-primary">₹{{ number_format($order->grand_total) }}</span>
                <span class="rounded-full {{ $color['bg'] }}/10 px-2 py-0.5 text-[10px] font-bold uppercase {{ $color['text'] }}">{{ $order->status->value }}</span>
              </div>
            </a>
          @empty
            <p class="text-sm adm-text-muted">No recent orders.</p>
          @endforelse
        </div>
      </div>

    </div>

  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
function dashboardChart() {
  return {
    renderChart() {
      const labels = @json($salesByDay['labels']);
      const revenue = @json($salesByDay['revenue']);
      const isDark = document.documentElement.classList.contains('dark');

      const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
      const tickColor = isDark ? '#9ca3af' : '#6b7280';

      new Chart(this.$refs.chart, {
        type: 'line',
        data: {
          labels,
          datasets: [{
            label: 'Revenue (₹)',
            data: revenue,
            borderColor: '#1B4332',
            backgroundColor: (ctx) => {
              const gradient = ctx.chart.ctx.createLinearGradient(0, 0, 0, ctx.chart.height);
              gradient.addColorStop(0, isDark ? 'rgba(27,67,50,0.35)' : 'rgba(27,67,50,0.15)');
              gradient.addColorStop(1, 'rgba(27,67,50,0)');
              return gradient;
            },
            fill: true,
            tension: 0.4,
            borderWidth: 2,
            pointRadius: 0,
            pointHoverRadius: 5,
            pointHoverBackgroundColor: '#1B4332',
            pointHoverBorderColor: '#fff',
            pointHoverBorderWidth: 2,
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          interaction: { mode: 'index', intersect: false },
          plugins: {
            legend: { display: false },
            tooltip: {
              backgroundColor: isDark ? '#1f2937' : '#1B4332',
              titleColor: '#fff',
              bodyColor: '#fff',
              padding: 12,
              cornerRadius: 8,
              displayColors: false,
              callbacks: {
                label: (ctx) => '₹' + ctx.parsed.y.toLocaleString('en-IN')
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              grid: { color: gridColor },
              ticks: { color: tickColor, font: { size: 11 }, callback: v => '₹' + v.toLocaleString('en-IN') }
            },
            x: {
              grid: { display: false },
              ticks: { color: tickColor, maxTicksLimit: 7, font: { size: 11 } }
            }
          }
        }
      });
    }
  }
}
</script>
@endsection
