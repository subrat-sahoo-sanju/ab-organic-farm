<?php

namespace App\Http\Controllers\Delivery;

use App\Enums\DeliveryAssignmentStatus;
use App\Http\Controllers\Controller;
use App\Models\DeliveryAssignment;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DeliveryController extends Controller
{
    public function index(): View
    {
        $person = auth()->user()->deliveryPerson;
        abort_unless($person, 403);

        return view('delivery.deliveries', [
            'assignments' => DeliveryAssignment::where('delivery_person_id', $person->id)
                ->with(['order' => fn ($q) => $q->with(['orderItems.product', 'payment'])])
                ->latest('assigned_at')
                ->when(request('status'), fn ($q, $s) => $q->where('status', $s))
                ->paginate(15)
                ->withQueryString(),
        ]);
    }

    public function show(DeliveryAssignment $assignment): View
    {
        abort_unless(
            $assignment->delivery_person_id === auth()->user()->deliveryPerson->id,
            403
        );

        $assignment->load([
            'order' => fn ($q) => $q->with([
                'user:id,name,phone',
                'orderItems.product.primaryImage',
                'payment',
                'payment.codCollection',
                'addresses',
            ]),
            'order.addresses',
        ]);

        return view('delivery.show', [
            'assignment' => $assignment,
            'order' => $assignment->order,
            'customer' => $assignment->order->user,
            'address' => $assignment->order->addresses()
                ->where('label', $assignment->order->ship_label)
                ->first() ?? $assignment->order->addresses()->first(),
        ]);
    }

    public function pickedUp(DeliveryAssignment $assignment): RedirectResponse
    {
        $this->authorize($assignment);
        abort_unless($assignment->status === DeliveryAssignmentStatus::Assigned, 422, 'Already picked up.');

        $assignment->update([
            'status' => DeliveryAssignmentStatus::OutForDelivery,
            'picked_up_at' => now(),
        ]);

        return back()->with('success', 'Order picked up — heading out for delivery!');
    }

    public function delivered(DeliveryAssignment $assignment): RedirectResponse
    {
        $this->authorize($assignment);
        abort_unless(in_array($assignment->status, ['assigned', 'out_for_delivery']), 422, 'Invalid status.');

        $person = auth()->user()->deliveryPerson;
        app(\App\Services\CodService::class)->markDelivered($assignment, $person);

        return back()->with('success', 'Delivery confirmed! Well done.');
    }

    public function failed(DeliveryAssignment $assignment): RedirectResponse
    {
        $this->authorize($assignment);

        $reason = request()->validate(['reason' => ['required', 'string', 'max:190']])['reason'];
        $person = auth()->user()->deliveryPerson;

        app(\App\Services\CodService::class)->markFailed($assignment, $person, $reason);

        return back()->with('success', 'Marked as failed. COD will not be collected for this order.');
    }

    protected function authorize(DeliveryAssignment $assignment): void
    {
        abort_unless(
            $assignment->delivery_person_id === auth()->user()->deliveryPerson->id,
            403
        );
    }
}
