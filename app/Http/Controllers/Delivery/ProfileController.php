<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    public function show()
    {
        $person = auth()->user()->deliveryPerson;
        abort_unless($person, 403);

        return view('delivery.profile', [
            'person' => $person->load('user:id,name,email,phone'),
            'stats' => $person->stats(),
        ]);
    }

    public function toggleAvailability(): RedirectResponse
    {
        $person = auth()->user()->deliveryPerson;
        abort_unless($person, 403);

        $person->update(['is_available' => !$person->is_available]);

        return back()->with('success', $person->is_available ? 'You are now accepting deliveries.' : 'You are now unavailable.');
    }
}
