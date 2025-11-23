<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplicationMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $company = Auth::guard('company')->user()->load('package');
        $stats = [
            'total_jobs' => Job::where('company_id', $company->id)->count(),
            'active_jobs' => Job::where('company_id', $company->id)->where('status', 'published')->count(),
        ];

        $recentMessages = JobApplicationMessage::with(['application.job', 'application.seeker'])
            ->where('company_id', $company->id)
            ->latest()
            ->take(5)
            ->get();

        $unreadNotificationCount = $company->unreadNotifications()->count();

        return view('company.dashboard', compact('company', 'stats', 'recentMessages', 'unreadNotificationCount'));
    }
}

