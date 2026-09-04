<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\DeliveryAssignment;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class LiveController extends Controller
{
    /**
     * Lightweight real-time polling endpoint for the delivery dashboard.
     * Returns live stats plus any newly assigned / updated assignments newer
     * than the client's last-seen version, so the courier sees new orders
     * appear without a manual page refresh.
     */
    public function dashboard(): JsonResponse
    {
        $person = auth()->user()->deliveryPerson;
        abort_unless($person, 403);

        $since = (int) (request()->query('since') ?? 0);

        $active = DeliveryAssignment::where('delivery_person_id', $person->id)
            ->whereIn('status', ['assigned', 'out_for_delivery'])
            ->with(['order' => fn ($q) => $q->with(['user', 'payment'])])
            ->latest('assigned_at')
            ->get();

        // Highest updated_at among this courier's active assignments (change token).
        $version = 0;
        foreach ($active as $a) {
            $ts = (int) $this->ts($a->updated_at);
            if ($ts > $version) $version = $ts;
        }

        // Orders assigned/updated after the client's last seen token.
        $fresh = [];
        $freshIds = [];
        $ordered = [];
        foreach ($active as $a) {
            $ts = (int) $this->ts($a->updated_at ?? $a->assigned_at);
            // >= so two assignments landing in the same second both surface;
            // the client dedupes by seen id.
            if ($ts >= $since) {
                $ordered[] = $a;
                $freshIds[] = $a->id;
            }
        }
        foreach ($ordered as $a) {
            if (count($fresh) < 6) $fresh[] = $this->preview($a);
        }

        return response()->json([
            'ok' => true,
            'version' => $version,
            'new_ids' => $freshIds,
            'stats' => [
                'pending' => (int) $active->where('status', 'assigned')->count(),
                'out' => (int) $active->where('status', 'out_for_delivery')->count(),
                'delivered_today' => (int) DeliveryAssignment::where('delivery_person_id', $person->id)
                    ->where('status', 'delivered')->whereDate('delivered_at', now())->count(),
                'cod_pending' => (int) DeliveryAssignment::where('delivery_person_id', $person->id)
                    ->whereHas('order.payment', fn ($q) => $q->where('status', 'pending'))
                    ->where('status', 'out_for_delivery')
                    ->count(),
            ],
            'new' => $fresh,
        ]);
    }

    private function preview(DeliveryAssignment $a): array
    {
        $order = $a->order;

        return [
            'id' => (int) $a->id,
            'order_number' => $order?->order_number,
            'status' => $a->status->value,
            'status_label' => $a->status->label(),
            'total' => (float) ($order?->grand_total ?? 0),
            'cod' => ($order?->payment?->method ?? null) === 'cod',
            'cod_amount' => (float) (($order?->payment?->method ?? null) === 'cod'
                ? ($order?->payment->amount ?? $order?->grand_total ?? 0)
                : 0),
            'customer' => $order?->user?->name ?? 'Customer',
            'phone' => $order?->user?->phone ?? null,
            'url' => $a->id ? route('delivery.show', $a) : null,
        ];
    }

    /**
     * Safely get a Unix timestamp from a Carbon instance or datetime string.
     * (Direct `->timestamp` on a model attribute is unreliable because Carbon
     * exposes a static `timestamp()` method that shadows the property.)
     */
    protected function ts($value): int
    {
        if ($value instanceof Carbon) return (int) $value->getTimestamp();
        return (int) (new Carbon($value))->getTimestamp();
    }
}