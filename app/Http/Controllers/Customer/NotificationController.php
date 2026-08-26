<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        return view('customer.account.notifications', [
            'notifications' => $user->notifications()->paginate(15),
            'unreadCount' => $user->unreadNotifications()->count(),
        ]);
    }

    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back();
    }
}
