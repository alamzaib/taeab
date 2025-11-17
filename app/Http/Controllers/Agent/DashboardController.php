<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $agent = Auth::guard('agent')->user();
        $stats = [
            'total_jobs' => Job::where('agent_id', $agent->id)->count(),
            'active_jobs' => Job::where('agent_id', $agent->id)->where('status', 'published')->count(),
        ];

        return view('agent.dashboard', compact('agent', 'stats'));
    }
}

