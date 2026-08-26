<?php

namespace App\Enums;

enum DeliveryAssignmentStatus: string
{
    case Assigned = 'assigned';
    case PickedUp = 'picked_up';
    case OutForDelivery = 'out_for_delivery';
    case Delivered = 'delivered';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Assigned => 'Assigned',
            self::PickedUp => 'Picked Up',
            self::OutForDelivery => 'Out for Delivery',
            self::Delivered => 'Delivered',
            self::Failed => 'Failed',
        };
    }

    public function canTransitionTo(self $target): bool
    {
        return match ($this) {
            self::Assigned => in_array($target, [self::PickedUp, self::Failed], true),
            self::PickedUp => in_array($target, [self::OutForDelivery, self::Failed], true),
            self::OutForDelivery => in_array($target, [self::Delivered, self::Failed], true),
            self::Delivered, self::Failed => false,
        };
    }
}
