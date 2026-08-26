@extends('layouts.admin')

@section('title', 'Reports')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="adm-page-title">Reports</h1>
        <div class="flex items-center gap-2">
            @foreach([7, 14, 30, 90] as $d)
                <a href="{{ route('admin.reports.index', ['days' => $d]) }}"
                    class="{{ $days == $d ? 'adm-pill-active' : 'adm-pill' }}">
                    {{ $d }} days
                </a>
            @endforeach
        </div>
    </div>

    <div class="adm-grid-5">
        <div class="adm-stat">
            <p class="adm-stat-label">Total Orders</p>
            <p class="adm-stat-value">{{ number_format($summary['orders']) }}</p>
        </div>
        <div class="adm-stat">
            <p class="adm-stat-label">Revenue</p>
            <p class="adm-stat-value">₹{{ number_format($summary['revenue'], 2) }}</p>
        </div>
        <div class="adm-stat">
            <p class="adm-stat-label">Avg Order Value</p>
            <p class="adm-stat-value">₹{{ number_format($summary['aov'], 2) }}</p>
        </div>
        <div class="adm-stat">
            <p class="adm-stat-label">Items Sold</p>
            <p class="adm-stat-value">{{ number_format($summary['items_sold']) }}</p>
        </div>
        <div class="adm-stat">
            <p class="adm-stat-label">New Customers</p>
            <p class="adm-stat-value">{{ number_format($summary['customers_new']) }}</p>
        </div>
    </div>

    <div class="adm-grid-3">
        <div class="adm-section p-6">
            <h2 class="adm-section-title">Top Products</h2>
            @if($topProducts->count())
                <div class="adm-table-wrap">
                    <table class="adm-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty Sold</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topProducts as $product)
                                <tr>
                                    <td class="font-medium">{{ $product->name }}</td>
                                    <td class="adm-text-secondary">{{ $product->qty_sold }}</td>
                                    <td class="font-medium">₹{{ number_format($product->revenue, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="adm-text-muted text-sm">No data available for this period.</p>
            @endif
        </div>

        <div class="adm-section p-6">
            <h2 class="adm-section-title">Category Breakdown</h2>
            @if($categoryBreakdown->count())
                <div class="adm-table-wrap">
                    <table class="adm-table">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categoryBreakdown as $category)
                                <tr>
                                    <td class="font-medium">{{ $category->name }}</td>
                                    <td class="font-medium">₹{{ number_format($category->revenue, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="adm-text-muted text-sm">No data available for this period.</p>
            @endif
        </div>
    </div>

    <div class="adm-section p-6">
        <h2 class="adm-section-title">Daily Sales</h2>
        @if($dailySales->count())
            <div class="adm-table-wrap">
                <table class="adm-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Orders</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dailySales as $sale)
                            <tr>
                                <td class="font-medium">{{ $sale->date }}</td>
                                <td class="adm-text-secondary">{{ $sale->orders }}</td>
                                <td class="font-medium">₹{{ number_format($sale->revenue, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="adm-text-muted text-sm">No data available for this period.</p>
        @endif
    </div>
</div>
@endsection
