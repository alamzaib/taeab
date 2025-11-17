<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobFavorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobListingController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::with('company')
            ->where('status', 'published')
            ->latest();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('short_description', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('job_type')) {
            $query->where('job_type', $request->job_type);
        }

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'salary_high':
                    $query->orderByDesc('salary_max');
                    break;
                case 'salary_low':
                    $query->orderBy('salary_min');
                    break;
                case 'oldest':
                    $query->orderBy('posted_at', 'asc');
                    break;
                case 'newest':
                default:
                    $query->orderBy('posted_at', 'desc');
            }
        } else {
            $query->orderBy('posted_at', 'desc');
        }

        $jobs = $query->paginate(10)->withQueryString();

        $jobTypes = Job::whereNotNull('job_type')
            ->distinct()
            ->orderBy('job_type')
            ->pluck('job_type');

        return view('jobs.index', [
            'jobs' => $jobs,
            'jobTypes' => $jobTypes,
        ]);
    }

    public function show(string $slug)
    {
        $job = Job::with(['company', 'agent'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $hasApplied = false;
        $defaultResume = null;
        $defaultCover = null;
        $isFavorited = false;

        if (Auth::guard('seeker')->check()) {
            $seeker = Auth::guard('seeker')->user();
            $hasApplied = $job->applications()
                ->where('seeker_id', $seeker->id)
                ->exists();

            $defaultResume = $seeker->documents()->where('type', 'resume')->where('is_default', true)->first();
            $defaultCover = $seeker->documents()->where('type', 'cover_letter')->where('is_default', true)->first();
            $isFavorited = $seeker->favorites()->where('job_id', $job->id)->exists();
        }

        return view('jobs.show', compact('job', 'hasApplied', 'defaultResume', 'defaultCover', 'isFavorited'));
    }

    public function toggleFavorite(Request $request, string $slug)
    {
        $job = Job::where('slug', $slug)->where('status', 'published')->firstOrFail();
        $seeker = Auth::guard('seeker')->user();

        $favorite = $seeker->favorites()->where('job_id', $job->id)->first();

        if ($favorite) {
            $favorite->delete();
            $message = 'Job removed from favorites.';
        } else {
            $seeker->favorites()->create(['job_id' => $job->id]);
            $message = 'Job saved to favorites.';
        }

        return back()->with('success', $message);
    }
}

