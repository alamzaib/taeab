<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplicationMessage;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $company = Auth::guard('company')->user();
        $stats = [
            'total_jobs' => Job::where('company_id', $company->id)->count(),
            'active_jobs' => Job::where('company_id', $company->id)->where('status', 'published')->count(),
        ];

        $recentMessages = JobApplicationMessage::with(['application.job', 'application.seeker'])
            ->where('company_id', $company->id)
            ->latest()
            ->take(5)
            ->get();

        return view('company.dashboard', compact('company', 'stats', 'recentMessages'));
    }
}

