<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Job;
use Illuminate\Http\Request;

class CompanyListingController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::query()
            ->withCount(['jobs as active_jobs_count' => function ($q) {
                $q->where('status', 'published');
            }]);

        $statusFilter = $request->has('status')
            ? $request->status
            : 'active';

        if ($statusFilter !== null && $statusFilter !== '') {
            $query->where('status', $statusFilter);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', '%' . $search . '%')
                    ->orWhere('industry', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('industry')) {
            $query->where('industry', $request->industry);
        }

        if ($request->filled('company_size')) {
            $query->where('company_size', $request->company_size);
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->filled('country')) {
            $query->where('country', 'like', '%' . $request->country . '%');
        }

        if ($request->filled('organization_type')) {
            $query->where('organization_type', $request->organization_type);
        }

        if ($request->boolean('has_openings')) {
            $query->whereHas('jobs', function ($q) {
                $q->where('status', 'published');
            });
        }

        switch ($request->get('sort', 'name_asc')) {
            case 'name_desc':
                $query->orderBy('company_name', 'desc');
                break;
            case 'recent':
                $query->orderByDesc('created_at');
                break;
            case 'jobs_desc':
                $query->orderByDesc('active_jobs_count');
                break;
            case 'name_asc':
            default:
                $query->orderBy('company_name');
                break;
        }

        $companies = $query->paginate(12)->withQueryString();

        $industries = Company::whereNotNull('industry')
            ->distinct()
            ->orderBy('industry')
            ->pluck('industry');

        $companySizes = Company::whereNotNull('company_size')
            ->distinct()
            ->orderBy('company_size')
            ->pluck('company_size');

        $organizationTypes = Company::whereNotNull('organization_type')
            ->distinct()
            ->orderBy('organization_type')
            ->pluck('organization_type');

        $countries = Company::whereNotNull('country')
            ->distinct()
            ->orderBy('country')
            ->pluck('country');

        $cities = Company::whereNotNull('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        return view('companies.index', [
            'companies' => $companies,
            'industries' => $industries,
            'companySizes' => $companySizes,
            'organizationTypes' => $organizationTypes,
            'countries' => $countries,
            'cities' => $cities,
            'statusFilter' => $statusFilter,
        ]);
    }

    public function show(Company $company)
    {
        $activeJobs = Job::where('company_id', $company->id)->where('status', 'published')->latest()->take(5)->get();
        $stats = [
            'total_jobs' => Job::where('company_id', $company->id)->count(),
            'active_jobs' => Job::where('company_id', $company->id)->where('status', 'published')->count(),
        ];

        return view('companies.show', compact('company', 'activeJobs', 'stats'));
    }
}

