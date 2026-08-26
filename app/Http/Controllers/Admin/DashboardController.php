<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Payment;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = now()->startOfDay();

        return view('admin.dashboard', [
            'stats' => [
                'customers' => User::whereHas('roles', fn ($q) => $q->where('name', 'customer'))->count(),
                'new_customers_7d' => User::whereHas('roles', fn ($q) => $q->where('name', 'customer'))
                    ->where('created_at', '>=', now()->subDays(7))->count(),
                'orders_total' => Order::count(),
                'orders_today' => Order::whereDate('placed_at', $today)->count(),
                'orders_pending' => Order::whereIn('status', [OrderStatus::Pending, OrderStatus::Confirmed])->count(),
                'orders_delivered' => Order::where('status', OrderStatus::Delivered)->count(),
                'orders_cancelled' => Order::where('status', OrderStatus::Cancelled)->count(),
                'products' => Product::published()->count(),
                'low_stock' => \App\Models\Inventory::get()->filter->isLow()->count(),
                'sales_today' => (float) Order::whereDate('placed_at', $today)->sum('grand_total'),
                'revenue_month' => (float) Order::whereIn('status', [OrderStatus::Delivered, OrderStatus::OutForDelivery])
                    ->whereMonth('placed_at', now())->sum('grand_total'),
                'aov' => round((float) Order::whereIn('status', [OrderStatus::Delivered, OrderStatus::OutForDelivery])->avg('grand_total'), 0),
                'cod_pending' => (float) Payment::where('status', 'pending')->sum('amount'),
                'cod_collected' => (float) Payment::where('status', 'collected')->sum('amount'),
            ],
            'salesByDay' => $this->salesByDay(),
            'statusDistribution' => Order::selectRaw('status, count(*) c')
                ->groupBy('status')->pluck('c', 'status'),
            'topProducts' => \App\Models\OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
                ->selectRaw('products.name, sum(order_items.quantity) qty')
                ->groupBy('products.name')->orderByDesc('qty')->limit(5)->get(),
            'topCategories' => \App\Models\OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->selectRaw('categories.name, sum(order_items.line_total) revenue')
                ->groupBy('categories.name')->orderByDesc('revenue')->limit(5)->get(),
            'recentOrders' => Order::with('user')->latest('placed_at')->take(8)->get(),
        ]);
    }

    protected function salesByDay(): array
    {
        $from = now()->subDays(29)->startOfDay();

        $rows = Order::whereIn('status', [OrderStatus::Delivered, OrderStatus::OutForDelivery])
            ->where('placed_at', '>=', $from)
            ->selectRaw("DATE(placed_at) as day, count(*) orders, sum(grand_total) revenue")
            ->groupBy('day')->orderBy('day')->get()
            ->keyBy(fn ($r) => $r->day);

        $labels = [];
        $revenue = [];
        $orders = [];
        for ($i = 29; $i >= 0; $i--) {
            $day = now()->subDays($i)->toDateString();
            $labels[] = now()->subDays($i)->format('M j');
            $revenue[] = round((float) ($rows[$day]->revenue ?? 0), 2);
            $orders[] = (int) ($rows[$day]->orders ?? 0);
        }

        return compact('labels', 'revenue', 'orders');
    }
}
