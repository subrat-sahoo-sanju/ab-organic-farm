<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Preparing = 'preparing';
    case Packed = 'packed';
    case Assigned = 'assigned';
    case OutForDelivery = 'out_for_delivery';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';
    case Returned = 'returned';
    case FailedDelivery = 'failed_delivery';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Confirmed => 'Confirmed',
            self::Preparing => 'Preparing',
            self::Packed => 'Packed',
            self::Assigned => 'Assigned',
            self::OutForDelivery => 'Out for Delivery',
            self::Delivered => 'Delivered',
            self::Cancelled => 'Cancelled',
            self::Returned => 'Returned',
            self::FailedDelivery => 'Failed Delivery',
        };
    }

    /** Tailwind badge classes keyed by status. */
    public function color(): string
    {
        return match ($this) {
            self::Pending => 'bg-amber-50 text-amber-700 ring-amber-600/20',
            self::Confirmed, self::Preparing => 'bg-sky-50 text-sky-700 ring-sky-600/20',
            self::Packed, self::Assigned => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20',
            self::OutForDelivery => 'bg-violet-50 text-violet-700 ring-violet-600/20',
            self::Delivered => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
            self::Cancelled, self::FailedDelivery => 'bg-rose-50 text-rose-700 ring-rose-600/20',
            self::Returned => 'bg-orange-50 text-orange-700 ring-orange-600/20',
        };
    }

    /**
     * Legal state machine — enforced centrally in OrderService.
     */
    public static function allowedTransitions(): array
    {
        return [
            self::Pending->value => [self::Confirmed->value, self::Cancelled->value],
            self::Confirmed->value => [self::Preparing->value, self::Cancelled->value],
            self::Preparing->value => [self::Packed->value, self::Cancelled->value],
            self::Packed->value => [self::Assigned->value],
            self::Assigned->value => [self::OutForDelivery->value, self::Cancelled->value],
            self::OutForDelivery->value => [self::Delivered->value, self::FailedDelivery->value],
            self::FailedDelivery->value => [self::OutForDelivery->value, self::Returned->value],
            self::Delivered->value => [self::Returned->value],
            self::Cancelled->value => [],
            self::Returned->value => [],
        ];
    }

    public function canTransitionTo(self $target): bool
    {
        return in_array($target->value, self::allowedTransitions()[$this->value] ?? [], true);
    }

    /** Customer may cancel themselves only in these states. */
    public static function customerCancellable(): array
    {
        return [self::Pending->value, self::Confirmed->value];
    }
}
