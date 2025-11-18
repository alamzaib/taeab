<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $seeker = Auth::guard('seeker')->user();
        $seeker->load([
            'educations' => fn ($query) => $query->orderByDesc('end_date')->orderByDesc('start_date'),
            'experiences' => fn ($query) => $query->orderByDesc('is_current')->orderByDesc('end_date')->orderByDesc('start_date'),
            'references',
            'hobbies',
        ]);

        $stats = [
            'applications' => $seeker->applications()->count(),
            'favorites' => $seeker->favorites()->count(),
        ];

        $favorites = $seeker->favorites()
            ->with('job.company')
            ->latest()
            ->take(3)
            ->get();

        $recentApplications = $seeker->applications()
            ->with('job.company')
            ->latest()
            ->take(5)
            ->get();

        return view('seeker.dashboard', compact('seeker', 'stats', 'favorites', 'recentApplications'));
    }
}

