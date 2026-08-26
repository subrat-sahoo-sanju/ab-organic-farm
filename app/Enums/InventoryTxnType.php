<?php

namespace App\Enums;

enum InventoryTxnType: string
{
    case Purchase = 'purchase';
    case Sale = 'sale';
    case Return = 'return';
    case Adjustment = 'adjustment';
    case Reservation = 'reservation';
    case Release = 'release';
    case Cancel = 'cancel';
    case Damage = 'damage';
}
