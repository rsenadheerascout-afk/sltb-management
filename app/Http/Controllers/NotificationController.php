<?php

namespace App\Http\Controllers;

use App\Models\SltbNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = SltbNotification::where('user_id', auth()->id())
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markRead(SltbNotification $notification)
    {
        if ($notification->user_id === auth()->id()) {
            $notification->update(['is_read' => true]);
        }
        return back();
    }

    public function markAllRead()
    {
        SltbNotification::where('user_id', auth()->id())
            ->update(['is_read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }
}