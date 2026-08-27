<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /** Poll for unread notifications + recent list. Used by the admin navbar. */
    public function index(): JsonResponse
    {
        $unreadCount = AdminNotification::unread()->count();

        $items = AdminNotification::query()
            ->latest()
            ->limit(25)
            ->get(['id', 'type', 'title', 'message', 'icon', 'color', 'order_id', 'meta', 'read_at', 'created_at']);

        return response()->json([
            'unread_count' => $unreadCount,
            'items' => $items->map(fn ($n) => [
                'id' => $n->id,
                'type' => $n->type,
                'title' => $n->title,
                'message' => $n->message,
                'icon' => $n->icon,
                'color' => $n->color,
                'order_id' => $n->order_id,
                'meta' => $n->meta,
                'read' => $n->read_at !== null,
                'created_at' => $n->created_at->diffForHumans(),
                'url' => $n->order_id ? route('admin.orders.show', $n->order_id) : null,
            ]),
        ]);
    }

    /** New-notifications since last check — returns only unseen ones (for sound/popup). */
    public function fresh(): JsonResponse
    {
        $items = AdminNotification::query()
            ->whereNull('read_at')
            ->latest()
            ->limit(20)
            ->get(['id', 'type', 'title', 'message', 'icon', 'color', 'order_id', 'meta', 'created_at']);

        return response()->json([
            'count' => $items->count(),
            'items' => $items->map(fn ($n) => [
                'id' => $n->id,
                'type' => $n->type,
                'title' => $n->title,
                'message' => $n->message,
                'icon' => $n->icon,
                'color' => $n->color,
                'order_id' => $n->order_id,
                'meta' => $n->meta,
                'created_at' => $n->created_at->diffForHumans(),
                'url' => $n->order_id ? route('admin.orders.show', $n->order_id) : null,
            ]),
        ]);
    }

    /** Mark a single notification read. */
    public function markRead(AdminNotification $notification): JsonResponse
    {
        $notification->markRead();
        return response()->json(['ok' => true]);
    }

    /** Mark all notifications read. */
    public function markAllRead(): JsonResponse
    {
        AdminNotification::unread()->update(['read_at' => now()]);
        return response()->json(['ok' => true]);
    }
}
