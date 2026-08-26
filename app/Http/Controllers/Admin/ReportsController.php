<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use Illuminate\View\View;

class ReportsController extends Controller
{
    public function index(): View
    {
        $days = (int) request('days', 30);
        $from = now()->subDays($days)->startOfDay();

        $orders = Order::where('placed_at', '>=', $from);
        $delivered = (clone $orders)->where('status', OrderStatus::Delivered);

        return view('admin.reports.index', [
            'days' => $days,
            'summary' => [
                'orders' => (clone $orders)->count(),
                'revenue' => (float) $delivered->sum('grand_total'),
                'aov' => (float) $delivered->avg('grand_total'),
                'items_sold' => OrderItem::whereHas('order', fn ($q) => $q->where('placed_at', '>=', $from))->sum('quantity'),
                'customers_new' => User::whereHas('roles', fn ($q) => $q->where('name', 'customer'))
                    ->where('created_at', '>=', $from)->count(),
                'cancellations' => (clone $orders)->where('status', OrderStatus::Cancelled)->count(),
            ],
            'topProducts' => OrderItem::whereHas('order', fn ($q) => $q->where('placed_at', '>=', $from))
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->selectRaw('products.name, products.id, sum(order_items.quantity) qty, sum(order_items.line_total) revenue')
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('revenue')
                ->limit(10)
                ->get(),
            'categoryBreakdown' => OrderItem::whereHas('order', fn ($q) => $q->where('placed_at', '>=', $from))
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->selectRaw('categories.name, sum(order_items.line_total) revenue')
                ->groupBy('categories.name')
                ->orderByDesc('revenue')
                ->get(),
            'dailySales' => $delivered
                ->selectRaw("DATE(placed_at) day, sum(grand_total) revenue, count(*) orders")
                ->groupBy('day')->orderBy('day')->get(),
            'hourlyPattern' => Order::where('placed_at', '>=', $from)
                ->selectRaw('HOUR(placed_at) h, count(*) c')
                ->groupBy('h')->pluck('c', 'h'),
        ]);
    }
}
