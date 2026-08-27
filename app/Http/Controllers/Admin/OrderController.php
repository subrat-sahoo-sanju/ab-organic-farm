<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $filters = request()->validate([
            'q' => ['nullable', 'string', 'max:64'],
            'status' => ['nullable', 'in:'.implode(',', array_column(OrderStatus::cases(), 'value'))],
            'range' => ['nullable', 'in:today,yesterday,week,month'],
            'cod' => ['nullable', 'in:pending,collected'],
        ]);

        return view('admin.orders.index', [
            'orders' => Order::query()
                ->with(['user:id,name,email', 'payment'])
                ->when($filters['q'] ?? null, fn ($q, $v) => $q->where(fn ($w) => $w
                    ->where('order_number', 'like', "%{$v}%")
                    ->orWhere('ship_phone', 'like', "%{$v}%")
                    ->orWhere('ship_name', 'like', "%{$v}%")
                    ->orWhereHas('user', fn ($u) => $u->where('email', 'like', "%{$v}%"))))
                ->when($filters['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
                ->when($filters['cod'] ?? null, fn ($q, $v) => $q->whereHas('payment', fn ($p) => $p->where('status', $v)))
                ->when($filters['range'] ?? null, function ($q, $range) {
                    [$start, $end] = match ($range) {
                        'today' => [now()->startOfDay(), now()->endOfDay()],
                        'yesterday' => [now()->subDay()->startOfDay(), now()->subDay()->endOfDay()],
                        'week' => [now()->startOfWeek(), now()->endOfDay()],
                        'month' => [now()->startOfMonth(), now()->endOfDay()],
                    };
                    $q->whereBetween('placed_at', [$start, $end]);
                })
                ->latest('placed_at')
                ->paginate(20)
                ->withQueryString(),
            'filters' => $filters,
        ]);
    }

    public function show(Order $order): View
    {
        $order->load([
            'items.product.primaryImage',
            'payment.codCollection',
            'statusHistories.changedBy:id,name',
            'assignments.deliveryPerson.user',
            'coupon',
        ]);

        return view('admin.orders.show', [
            'order' => $order,
            'nextStatuses' => OrderStatus::allowedTransitions()[$order->status->value] ?? [],
            'deliveryPersons' => \App\Models\DeliveryPerson::with('user:id,name')->where('is_available', true)->get(),
        ]);
    }

    /** Central admin transition — delegates to the state machine in OrderService. */
    public function transition(Order $order): RedirectResponse
    {
        $data = request()->validate([
            'to_status' => ['required', 'string'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            app(\App\Services\OrderService::class)->transition(
                $order,
                OrderStatus::from($data['to_status']),
                $data['note'] ?? null,
                auth()->user()
            );
        } catch (\DomainException|\ValueError $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', "Order moved to {$order->refresh()->status->label()}.");
    }

    /** JSON live feed of today's orders for the dashboard ticker (no DB notification needed). */
    public function live(\Illuminate\Http\Request $request): \Illuminate\Http\JsonResponse
    {
        $date = $request->query('date', now()->toDateString());
        [$start, $end] = [\Illuminate\Support\Carbon::parse($date)->startOfDay(), \Illuminate\Support\Carbon::parse($date)->endOfDay()];

        $orders = Order::with(['user:id,name', 'payment'])
            ->whereBetween('placed_at', [$start, $end])
            ->latest('placed_at')
            ->limit(30)
            ->get();

        $statusMeta = [
            'pending' => ['bg' => 'bg-amber-500', 'text' => 'bg-amber-50 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300'],
            'confirmed' => ['bg' => 'bg-sky-500', 'text' => 'bg-sky-50 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300'],
            'preparing' => ['bg' => 'bg-sky-600', 'text' => 'bg-sky-50 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300'],
            'packed' => ['bg' => 'bg-indigo-500', 'text' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300'],
            'assigned' => ['bg' => 'bg-indigo-600', 'text' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300'],
            'out_for_delivery' => ['bg' => 'bg-violet-500', 'text' => 'bg-violet-50 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300'],
            'delivered' => ['bg' => 'bg-emerald-500', 'text' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300'],
            'cancelled' => ['bg' => 'bg-rose-500', 'text' => 'bg-rose-50 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300'],
            'returned' => ['bg' => 'bg-orange-500', 'text' => 'bg-orange-50 text-orange-700 dark:bg-orange-900/40 dark:text-orange-300'],
            'failed_delivery' => ['bg' => 'bg-rose-600', 'text' => 'bg-rose-50 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300'],
        ];

        return response()->json($orders->map(fn ($o) => [
            'id' => $o->id,
            'order_number' => $o->order_number,
            'customer' => $o->user->name ?? 'Guest',
            'amount' => '₹'.number_format($o->grand_total),
            'status' => $o->status->label(),
            'status_value' => $o->status->value,
            'status_bg' => $statusMeta[$o->status->value]['bg'] ?? 'bg-gray-500',
            'status_text' => $statusMeta[$o->status->value]['text'] ?? 'bg-gray-50 text-gray-700',
            'payment' => ucfirst($o->payment->status ?? 'pending'),
            'time' => $o->placed_at?->diffForHumans(),
            'url' => route('admin.orders.show', $o->id),
        ]));
    }
}
