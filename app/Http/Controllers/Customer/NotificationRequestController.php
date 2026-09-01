<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\NotifyMe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationRequestController extends Controller
{
    public function notifyMe(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'product' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
        ]);

        NotifyMe::updateOrCreate(
            ['email' => $data['email'], 'product_slug' => $data['slug'] ?? '', 'type' => 'notify_me'],
            ['product_name' => $data['product'] ?? null],
        );

        return response()->json(['ok' => true, 'message' => 'You\'re on the list!']);
    }

    public function newsletter(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        NotifyMe::updateOrCreate(
            ['email' => $data['email'], 'product_slug' => '', 'type' => 'newsletter'],
            ['product_name' => null],
        );

        return response()->json(['ok' => true, 'message' => 'Subscribed!']);
    }
}
