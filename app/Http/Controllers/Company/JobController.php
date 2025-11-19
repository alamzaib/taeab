<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $company = auth('company')->user();

        $query = Job::where('company_id', $company->id)->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $jobs = $query->paginate(15)->withQueryString();

        return view('company.jobs.index', compact('jobs', 'company'));
    }

    public function create()
    {
        $job = new Job();
        $company = auth('company')->user()->load('package');
        return view('company.jobs.create', compact('job', 'company'));
    }

    public function store(Request $request)
    {
        $company = auth('company')->user()->load('package');

        $data = $this->validateJob($request);
        $data['company_id'] = $company->id;
        $data['slug'] = $this->generateSlug($data['title']);

        // Handle location from country and city
        if ($request->filled('city') && $request->filled('country')) {
            $data['location'] = $request->city . ', ' . $request->country;
        } elseif ($request->filled('location')) {
            // Keep existing location if provided directly
        } else {
            $data['location'] = null;
        }

        // Only allow featured for Gold package companies
        if (isset($data['featured']) && $data['featured']) {
            if (!$company->package || $company->package->name !== 'gold') {
                $data['featured'] = false;
            }
        } else {
            $data['featured'] = false;
        }

        if ($data['status'] === 'published') {
            $data['posted_at'] = now();
            $data['expires_at'] = now()->addMonth(); // Set expiration to 1 month from now
        }

        Job::create($data);

        return redirect()->route('company.jobs.index')->with('success', 'Job created successfully.');
    }

    public function edit(Job $job)
    {
        $this->authorizeJob($job);
        $company = auth('company')->user()->load('package');
        return view('company.jobs.edit', compact('job', 'company'));
    }

    public function update(Request $request, Job $job)
    {
        $this->authorizeJob($job);
        $company = auth('company')->user()->load('package');

        $data = $this->validateJob($request);

        if ($job->title !== $data['title']) {
            $data['slug'] = $this->generateSlug($data['title']);
        }

        // Handle location from country and city
        if ($request->filled('city') && $request->filled('country')) {
            $data['location'] = $request->city . ', ' . $request->country;
        } elseif ($request->filled('location')) {
            // Keep existing location if provided directly
        } else {
            $data['location'] = null;
        }

        // Only allow featured for Gold package companies
        if (isset($data['featured']) && $data['featured']) {
            if (!$company->package || $company->package->name !== 'gold') {
                $data['featured'] = false;
            }
        } else {
            $data['featured'] = false;
        }

        if ($data['status'] === 'published' && !$job->posted_at) {
            $data['posted_at'] = now();
            $data['expires_at'] = now()->addMonth(); // Set expiration to 1 month from now
        }

        $job->update($data);

        return redirect()->route('company.jobs.index')->with('success', 'Job updated successfully.');
    }

    public function destroy(Job $job)
    {
        $this->authorizeJob($job);
        $job->delete();

        return redirect()->route('company.jobs.index')->with('success', 'Job deleted successfully.');
    }

    public function renew(Job $job)
    {
        $this->authorizeJob($job);

        if (!$job->isExpired()) {
            return back()->with('error', 'Job is not expired yet.');
        }

        $job->update([
            'posted_at' => now(),
            'expires_at' => now()->addMonth(),
            'status' => 'published',
        ]);

        return back()->with('success', 'Job renewed successfully for another month.');
    }

    protected function validateJob(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'exists:countries,id'],
            'city' => ['nullable', 'exists:cities,id'],
            'job_type' => ['nullable', 'string', 'max:100'],
            'experience_level' => ['nullable', 'string', 'max:100'],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'min:0'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published,closed'],
            'featured' => ['nullable', 'boolean'],
        ]);
    }

    protected function authorizeJob(Job $job): void
    {
        $company = auth('company')->user();
        abort_if($job->company_id !== $company->id, 403);
    }

    protected function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = Job::where('slug', 'like', "{$slug}%")->count();
        return $count ? "{$slug}-" . ($count + 1) : $slug;
    }
}

