<?php

namespace App\Services;

use App\Enums\DeliveryAssignmentStatus;
use App\Enums\OrderStatus;
use App\Events\CodCollected;
use App\Models\CodCollection;
use App\Models\DeliveryAssignment;
use App\Models\DeliveryPerson;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class CodService
{
    public function collect(Payment $payment, float $amount, int $collectedBy, string $collectorType = 'delivery_person', ?string $notes = null): CodCollection
    {
        if ($payment->status === 'collected') {
            throw new \DomainException('Payment has already been collected for this order.');
        }

        if (abs($amount - (float) $payment->amount) > 0.01 && empty($notes)) {
            throw new \DomainException('Collected amount differs from expected amount — please add a note.');
        }

        return DB::transaction(function () use ($payment, $amount, $collectedBy, $collectorType, $notes) {
            if ($payment->refresh()->status === 'collected') {
                throw new \DomainException('Payment has already been collected for this order.');
            }

            $payment->update(['status' => 'collected']);

            $collection = CodCollection::create([
                'payment_id' => $payment->id,
                'collected_by' => $collectedBy,
                'collector_type' => $collectorType,
                'amount' => $amount,
                'collected_at' => now(),
                'notes' => $notes,
            ]);

            event(new CodCollected($collection));

            return $collection;
        });
    }

    public function markDelivered(DeliveryAssignment $assignment, DeliveryPerson $person): void
    {
        DB::transaction(function () use ($assignment, $person) {
            $assignment->update([
                'status' => DeliveryAssignmentStatus::Delivered,
                'delivered_at' => now(),
            ]);

            $order = $assignment->order;
            app(OrderService::class)->transition(
                $order,
                OrderStatus::Delivered,
                'Delivered by '.$person->user->name
            );

            $payment = $order->payment;
            if ($payment && $payment->method === 'cod' && $payment->status === 'pending') {
                $this->collect(
                    $payment,
                    (float) $payment->amount,
                    $person->id,
                    'delivery_person',
                    'COD collected on delivery'
                );
            }
        });
    }

    public function markFailed(DeliveryAssignment $assignment, DeliveryPerson $person, string $reason): void
    {
        DB::transaction(function () use ($assignment, $person, $reason) {
            $assignment->update([
                'status' => DeliveryAssignmentStatus::Failed,
                'failed_reason' => $reason,
            ]);

            app(OrderService::class)->transition(
                $assignment->order,
                OrderStatus::Cancelled,
                "Delivery failed: {$reason}"
            );
        });
    }
}
