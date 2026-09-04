<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\DeliveryAssignment;
use App\Models\DeliveryPerson;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DeliveryController extends Controller
{
    public function index(): View
    {
        return view('admin.delivery.deliveries', [
            'assignments' => DeliveryAssignment::with(['order.user:id,name', 'deliveryPerson.user:id,name'])
                ->latest('assigned_at')
                ->when(request('status'), fn ($q, $s) => $q->where('status', $s))
                ->paginate(20)
                ->withQueryString(),
            'persons' => DeliveryPerson::with('user:id,name')->get(),
            'stats' => [
                'pending_orders' => Order::where('status', OrderStatus::Packed)->count(),
                'assigned' => DeliveryAssignment::where('status', 'assigned')->count(),
                'out' => DeliveryAssignment::where('status', 'out_for_delivery')->count(),
                'delivered_today' => DeliveryAssignment::where('status', 'delivered')->whereDate('delivered_at', now())->count(),
                'failed' => DeliveryAssignment::where('status', 'failed')->count(),
            ],
        ]);
    }

    public function live(): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'stats' => [
                'pending_orders' => Order::where('status', OrderStatus::Packed)->count(),
                'assigned' => DeliveryAssignment::where('status', 'assigned')->count(),
                'out' => DeliveryAssignment::where('status', 'out_for_delivery')->count(),
                'delivered_today' => DeliveryAssignment::where('status', 'delivered')->whereDate('delivered_at', now())->count(),
                'failed' => DeliveryAssignment::where('status', 'failed')->count(),
            ],
            'active_count' => DeliveryAssignment::whereIn('status', ['assigned', 'out_for_delivery'])->count(),
        ]);
    }

    public function assign(Order $order): RedirectResponse
    {
        $data = request()->validate(['delivery_person_id' => ['required', 'integer']]);

        try {
            app(\App\Services\DeliveryService::class)->assign(
                $order,
                DeliveryPerson::findOrFail($data['delivery_person_id']),
                auth()->user()
            );
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Delivery person assigned.');
    }

    public function reassign(DeliveryAssignment $assignment): RedirectResponse
    {
        $data = request()->validate(['delivery_person_id' => ['required', 'integer']]);

        try {
            app(\App\Services\DeliveryService::class)->assign(
                $assignment->order,
                DeliveryPerson::findOrFail($data['delivery_person_id']),
                auth()->user()
            );
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Order reassigned.');
    }
}
