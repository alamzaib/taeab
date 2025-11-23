<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\ApplicationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $seeker = Auth::guard('seeker')->user();
        
        $notifications = $seeker->notifications()
            ->with(['jobApplication.job', 'job'])
            ->paginate(20);

        return view('seeker.notifications.index', compact('notifications'));
    }

    public function markAsRead(ApplicationNotification $notification)
    {
        $seeker = Auth::guard('seeker')->user();
        
        abort_if($notification->recipient_type !== 'seeker' || $notification->recipient_id !== $seeker->id, 403);
        
        $notification->markAsRead();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Notification marked as read.']);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead()
    {
        $seeker = Auth::guard('seeker')->user();
        
        $seeker->unreadNotifications()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'All notifications marked as read.']);
        }

        return back()->with('success', 'All notifications marked as read.');
    }

    public function count()
    {
        $seeker = Auth::guard('seeker')->user();
        
        return response()->json([
            'count' => $seeker->unreadNotifications()->count()
        ]);
    }
}

