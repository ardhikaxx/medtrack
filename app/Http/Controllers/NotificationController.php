<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function getUnread()
    {
        $notifications = auth()->user()->notifications()
            ->where('is_read', false)
            ->latest()
            ->limit(10)
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'count' => $notifications->count()
        ]);
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id === auth()->id()) {
            $notification->markAsRead();
        }

        return back();
    }

    public function markAllAsRead()
    {
        auth()->user()->notifications()
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return back();
    }
}
