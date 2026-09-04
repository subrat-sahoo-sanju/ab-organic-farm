<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\DeliveryAssignment;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $person = auth()->user()->deliveryPerson;

        abort_unless($person, 403);

        return view('delivery.dashboard', [
            'assignments' => DeliveryAssignment::where('delivery_person_id', $person->id)
                ->with(['order' => fn ($q) => $q->with(['items.product', 'payment.codCollection', 'payment'])])
                ->whereIn('status', ['assigned', 'out_for_delivery'])
                ->latest('assigned_at')
                ->get(),
            'stats' => [
                'pending' => DeliveryAssignment::where('delivery_person_id', $person->id)->where('status', 'assigned')->count(),
                'out' => DeliveryAssignment::where('delivery_person_id', $person->id)->where('status', 'out_for_delivery')->count(),
                'delivered_today' => DeliveryAssignment::where('delivery_person_id', $person->id)
                    ->where('status', 'delivered')->whereDate('delivered_at', now())->count(),
                'cod_pending' => DeliveryAssignment::where('delivery_person_id', $person->id)
                    ->whereHas('order.payment', fn ($q) => $q->where('status', 'pending'))
                    ->where('status', 'out_for_delivery')
                    ->count(),
            ],
            'todayDelivered' => DeliveryAssignment::where('delivery_person_id', $person->id)
                ->where('status', 'delivered')->whereDate('delivered_at', now())->count(),
        ]);
    }
}
