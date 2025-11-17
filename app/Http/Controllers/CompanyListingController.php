<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Job;
use Illuminate\Http\Request;

class CompanyListingController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::query()->orderBy('company_name');

        if ($request->has('status')) {
            if ($request->status !== null && $request->status !== '') {
                $query->where('status', $request->status);
            }
        } else {
            $query->where('status', 'active');
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

        $companies = $query->paginate(12)->withQueryString();

        $industries = Company::whereNotNull('industry')
            ->distinct()
            ->orderBy('industry')
            ->pluck('industry');

        $companySizes = Company::whereNotNull('company_size')
            ->distinct()
            ->orderBy('company_size')
            ->pluck('company_size');

        return view('companies.index', [
            'companies' => $companies,
            'industries' => $industries,
            'companySizes' => $companySizes,
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

