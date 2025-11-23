<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\ApplicationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $company = Auth::guard('company')->user()->load('package');
        
        $notifications = $company->notifications()
            ->with(['jobApplication.job', 'jobApplication.seeker', 'job'])
            ->paginate(20);

        $unreadNotificationCount = $company->unreadNotifications()->count();

        return view('company.notifications.index', compact('notifications', 'company', 'unreadNotificationCount'));
    }

    public function markAsRead(ApplicationNotification $notification)
    {
        $company = Auth::guard('company')->user();
        
        abort_if($notification->recipient_type !== 'company' || $notification->recipient_id !== $company->id, 403);
        
        $notification->markAsRead();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Notification marked as read.']);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead()
    {
        $company = Auth::guard('company')->user();
        
        $company->unreadNotifications()->update([
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
        $company = Auth::guard('company')->user();
        
        return response()->json([
            'count' => $company->unreadNotifications()->count()
        ]);
    }
}

