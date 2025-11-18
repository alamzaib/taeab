<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class JobListingController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::with('company')
            ->where('status', 'published');

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

        $jobTypeFilters = array_filter((array) $request->input('job_types', []));
        if (!empty($jobTypeFilters)) {
            $query->whereIn('job_type', $jobTypeFilters);
        }

        $experienceFilters = array_filter((array) $request->input('experience_levels', []));
        if (!empty($experienceFilters)) {
            $query->whereIn('experience_level', $experienceFilters);
        }

        if ($request->filled('company_name')) {
            $companySearch = $request->company_name;
            $query->whereHas('company', function ($q) use ($companySearch) {
                $q->where('company_name', 'like', '%' . $companySearch . '%');
            });
        }

        if ($request->filled('salary_min')) {
            $salaryMin = (int) preg_replace('/[^\d]/', '', $request->salary_min);
            $query->where(function ($q) use ($salaryMin) {
                $q->where(function ($sub) use ($salaryMin) {
                    $sub->whereNotNull('salary_min')->where('salary_min', '>=', $salaryMin);
                })->orWhere(function ($sub) use ($salaryMin) {
                    $sub->whereNotNull('salary_max')->where('salary_max', '>=', $salaryMin);
                });
            });
        }

        if ($request->filled('salary_max')) {
            $salaryMax = (int) preg_replace('/[^\d]/', '', $request->salary_max);
            $query->where(function ($q) use ($salaryMax) {
                $q->where(function ($sub) use ($salaryMax) {
                    $sub->whereNotNull('salary_max')->where('salary_max', '<=', $salaryMax);
                })->orWhere(function ($sub) use ($salaryMax) {
                    $sub->whereNotNull('salary_min')->where('salary_min', '<=', $salaryMax);
                });
            });
        }

        if ($request->filled('posted_within') && $request->posted_within !== 'any') {
            $thresholds = [
                '24h' => Carbon::now()->subDay(),
                '3d' => Carbon::now()->subDays(3),
                '7d' => Carbon::now()->subDays(7),
                '30d' => Carbon::now()->subDays(30),
            ];

            if (isset($thresholds[$request->posted_within])) {
                $date = $thresholds[$request->posted_within];
                $query->where(function ($q) use ($date) {
                    $q->where('posted_at', '>=', $date)
                        ->orWhere(function ($sub) use ($date) {
                            $sub->whereNull('posted_at')->where('created_at', '>=', $date);
                        });
                });
            }
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

        $jobs = $query->paginate(12)->withQueryString();

        $jobTypes = Job::whereNotNull('job_type')
            ->distinct()
            ->orderBy('job_type')
            ->pluck('job_type');

        $experienceLevels = Job::whereNotNull('experience_level')
            ->distinct()
            ->orderBy('experience_level')
            ->pluck('experience_level');

        $salaryRange = [
            'min' => Job::whereNotNull('salary_min')->min('salary_min') ?? 0,
            'max' => Job::whereNotNull('salary_max')->max('salary_max') ?? 0,
        ];

        return view('jobs.index', [
            'jobs' => $jobs,
            'jobTypes' => $jobTypes,
            'experienceLevels' => $experienceLevels,
            'salaryRange' => $salaryRange,
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

