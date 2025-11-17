<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $agent = auth('agent')->user();

        $query = Job::with('company')->where('agent_id', $agent->id)->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $jobs = $query->paginate(15)->withQueryString();

        return view('agent.jobs.index', compact('jobs'));
    }

    public function create()
    {
        $companies = Company::orderBy('company_name')->get();
        $job = new Job();
        return view('agent.jobs.create', compact('companies', 'job'));
    }

    public function store(Request $request)
    {
        $agent = auth('agent')->user();

        $data = $this->validateJob($request);
        $data['agent_id'] = $agent->id;
        $data['slug'] = $this->generateSlug($data['title']);

        if ($data['status'] === 'published') {
            $data['posted_at'] = now();
        }

        Job::create($data);

        return redirect()->route('agent.jobs.index')->with('success', 'Job created successfully.');
    }

    public function edit(Job $job)
    {
        $this->authorizeJob($job);
        $companies = Company::orderBy('company_name')->get();
        return view('agent.jobs.edit', compact('job', 'companies'));
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

        return redirect()->route('agent.jobs.index')->with('success', 'Job updated successfully.');
    }

    public function destroy(Job $job)
    {
        $this->authorizeJob($job);
        $job->delete();

        return redirect()->route('agent.jobs.index')->with('success', 'Job deleted successfully.');
    }

    protected function validateJob(Request $request): array
    {
        return $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
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
        $agent = auth('agent')->user();
        abort_if($job->agent_id !== $agent->id, 403);
    }

    protected function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = Job::where('slug', 'like', "{$slug}%")->count();
        return $count ? "{$slug}-" . ($count + 1) : $slug;
    }
}

