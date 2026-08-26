<?php

namespace App\Services;

use App\Enums\DeliveryAssignmentStatus;
use App\Enums\OrderStatus;
use App\Events\CodCollected;
use App\Models\DeliveryAssignment;
use App\Models\DeliveryPerson;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Support\Facades\DB;

class DeliveryService
{
    /**
     * Assign (or reassign) an order to a delivery person.
     * Order must be in `packed` or later pre-dispatch state.
     */
    public function assign(Order $order, DeliveryPerson $person, $actor = null): DeliveryAssignment
    {
        if (! in_array($order->status, [OrderStatus::Packed, OrderStatus::Assigned], true)) {
            throw new \DomainException('Order must be packed before assigning delivery.');
        }

        return DB::transaction(function () use ($order, $person, $actor) {
            // Close any previous open assignment
            $order->assignments()
                ->whereIn('status', ['assigned', 'picked_up', 'out_for_delivery'])
                ->update(['status' => DeliveryAssignmentStatus::Assigned->value]);

            $assignment = $order->activeAssignment()->first();

            if ($assignment && $assignment->delivery_person_id !== $person->id) {
                $assignment->update([
                    'delivery_person_id' => $person->id,
                    'assigned_by' => $actor?->id,
                    'attempt_count' => $assignment->attempt_count,
                ]);
            } elseif (! $assignment) {
                $assignment = DeliveryAssignment::create([
                    'order_id' => $order->id,
                    'delivery_person_id' => $person->id,
                    'assigned_by' => $actor?->id,
                    'status' => DeliveryAssignmentStatus::Assigned,
                ]);
            }

            if ($order->status === OrderStatus::Packed) {
                $order->forceFill(['status' => OrderStatus::Assigned])->save();

                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'from_status' => OrderStatus::Packed->value,
                    'to_status' => OrderStatus::Assigned->value,
                    'note' => 'Assigned to '.$person->user->name,
                    'changed_by' => $actor?->id,
                ]);
            }

            return $assignment;
        });
    }

    /** Delivery-person side status updates. */
    public function updateShipment(DeliveryAssignment $assignment, DeliveryAssignmentStatus $to, ?string $failedReason = null): DeliveryAssignment
    {
        if (! $assignment->status->canTransitionTo($to)) {
            throw new \DomainException("Invalid delivery status change to {$to->label()}.");
        }

        return DB::transaction(function () use ($assignment, $to, $failedReason) {
            $updates = ['status' => $to];

            if ($to === DeliveryAssignmentStatus::Delivered) {
                $updates['delivered_at'] = now();
            }
            if ($to === DeliveryAssignmentStatus::Failed) {
                $updates['failed_reason'] = $failedReason ?? 'Customer unavailable';
                $updates['attempt_count'] = $assignment->attempt_count + 1;
            }

            $assignment->update($updates);

            $order = $assignment->order;

            match ($to) {
                DeliveryAssignmentStatus::OutForDelivery => $this->setOrderStatus($order, OrderStatus::OutForDelivery, 'Out for delivery'),
                DeliveryAssignmentStatus::Delivered => $this->setOrderStatus($order, OrderStatus::Delivered, 'Delivered by '.$assignment->deliveryPerson->user->name),
                DeliveryAssignmentStatus::Failed => $this->setOrderStatus($order, OrderStatus::FailedDelivery, $updates['failed_reason']),
                default => null,
            };

            return $assignment;
        });
    }

    protected function setOrderStatus(Order $order, OrderStatus $status, string $note): void
    {
        $from = $order->status;
        $order->forceFill(array_filter([
            'status' => $status,
            'delivered_at' => $status === OrderStatus::Delivered ? now() : null,
        ], fn ($v) => $v !== null))->save();

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'from_status' => $from->value,
            'to_status' => $status->value,
            'note' => $note,
            'changed_by' => auth()->id(),
        ]);

        event(new \App\Events\OrderStatusChanged($order, $from, $status));
    }
}
