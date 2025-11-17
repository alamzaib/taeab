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

        return view('company.jobs.index', compact('jobs'));
    }

    public function create()
    {
        $job = new Job();
        return view('company.jobs.create', compact('job'));
    }

    public function store(Request $request)
    {
        $company = auth('company')->user();

        $data = $this->validateJob($request);
        $data['company_id'] = $company->id;
        $data['slug'] = $this->generateSlug($data['title']);

        if ($data['status'] === 'published') {
            $data['posted_at'] = now();
        }

        Job::create($data);

        return redirect()->route('company.jobs.index')->with('success', 'Job created successfully.');
    }

    public function edit(Job $job)
    {
        $this->authorizeJob($job);
        return view('company.jobs.edit', compact('job'));
    }

    public function update(Request $request, Job $job)
    {
        $this->authorizeJob($job);

        $data = $this->validateJob($request);

        if ($job->title !== $data['title']) {
            $data['slug'] = $this->generateSlug($data['title']);
        }

        if ($data['status'] === 'published' && !$job->posted_at) {
            $data['posted_at'] = now();
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

    protected function validateJob(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'job_type' => ['nullable', 'string', 'max:100'],
            'experience_level' => ['nullable', 'string', 'max:100'],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'min:0'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published,closed'],
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

