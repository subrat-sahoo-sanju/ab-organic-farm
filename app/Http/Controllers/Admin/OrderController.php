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
}
