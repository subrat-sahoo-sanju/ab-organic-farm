<?php

namespace App\Events;

use App\Models\CodCollection;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CodCollected
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public CodCollection $collection) {}
}
