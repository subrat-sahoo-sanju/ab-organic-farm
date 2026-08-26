<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CODController extends Controller
{
    public function index(): View
    {
        $person = auth()->user()->deliveryPerson;
        abort_unless($person, 403);

        $payments = \App\Models\Payment::where('method', 'cod')
            ->whereHas('order', fn ($q) => $q->where('assigned_to', $person->id))
            ->with(['order:id,order_number,grand_total,status', 'codCollection'])
            ->latest('created_at')
            ->paginate(20);

        return view('delivery.cod', [
            'payments' => $payments,
            'stats' => $person->codStats(),
        ]);
    }
}
