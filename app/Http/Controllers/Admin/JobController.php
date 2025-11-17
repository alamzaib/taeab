<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Company;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::with(['company', 'agent'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        $jobs = $query->paginate(20)->withQueryString();
        $companies = Company::orderBy('company_name')->pluck('company_name', 'id');

        return view('admin.jobs.index', compact('jobs', 'companies'));
    }

    public function create()
    {
        $companies = Company::orderBy('company_name')->get();
        $agents = Agent::orderBy('name')->get();
        $job = new Job();

        return view('admin.jobs.create', compact('companies', 'agents', 'job'));
    }

    public function store(Request $request)
    {
        $data = $this->validateJob($request);
        $data['slug'] = $this->generateSlug($data['title']);

        if ($data['status'] === 'published') {
            $data['posted_at'] = now();
        }

        Job::create($data);

        return redirect()->route('admin.jobs.index')->with('success', 'Job created successfully.');
    }

    public function edit(Job $job)
    {
        $companies = Company::orderBy('company_name')->get();
        $agents = Agent::orderBy('name')->get();

        return view('admin.jobs.edit', compact('job', 'companies', 'agents'));
    }

    public function update(Request $request, Job $job)
    {
        $data = $this->validateJob($request, $job);

        if ($job->title !== $data['title']) {
            $data['slug'] = $this->generateSlug($data['title']);
        }

        if ($data['status'] === 'published' && !$job->posted_at) {
            $data['posted_at'] = now();
        }

        $job->update($data);

        return redirect()->route('admin.jobs.index')->with('success', 'Job updated successfully.');
    }

    public function destroy(Job $job)
    {
        $job->delete();

        return redirect()->route('admin.jobs.index')->with('success', 'Job deleted successfully.');
    }

    public function importForm()
    {
        return view('admin.jobs.import');
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $path = $request->file('file')->getRealPath();
        $handle = fopen($path, 'r');

        if (!$handle) {
            return back()->with('error', 'Unable to read the uploaded file.');
        }

        $header = fgetcsv($handle);
        $requiredHeaders = ['title', 'company_email', 'location', 'job_type', 'status', 'salary_min', 'salary_max', 'short_description', 'description'];

        if (!$header || array_map('strtolower', $header) !== $requiredHeaders) {
            fclose($handle);
            return back()->with('error', 'Invalid CSV format. Please download the sample file for reference.');
        }

        $created = 0;
        $errors = [];

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle)) !== false) {
                $data = array_combine($requiredHeaders, $row);

                $company = Company::where('email', $data['company_email'])->first();
                if (!$company) {
                    $errors[] = "Company with email {$data['company_email']} not found.";
                    continue;
                }

                $jobData = [
                    'company_id' => $company->id,
                    'agent_id' => null,
                    'title' => $data['title'],
                    'slug' => $this->generateSlug($data['title']),
                    'location' => $data['location'],
                    'job_type' => $data['job_type'],
                    'status' => in_array($data['status'], ['draft', 'published', 'closed']) ? $data['status'] : 'draft',
                    'salary_min' => $data['salary_min'] ?: null,
                    'salary_max' => $data['salary_max'] ?: null,
                    'short_description' => $data['short_description'],
                    'description' => $data['description'],
                ];

                if ($jobData['status'] === 'published') {
                    $jobData['posted_at'] = now();
                }

                Job::create($jobData);
                $created++;
            }

            fclose($handle);
            DB::commit();
        } catch (\Throwable $th) {
            fclose($handle);
            DB::rollBack();
            return back()->with('error', 'Import failed: ' . $th->getMessage());
        }

        $message = "{$created} jobs imported successfully.";
        if (!empty($errors)) {
            $message .= ' Some rows could not be imported: ' . implode(' ', $errors);
        }

        return redirect()->route('admin.jobs.index')->with('success', $message);
    }

    protected function validateJob(Request $request, ?Job $job = null): array
    {
        return $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
            'agent_id' => ['nullable', 'exists:agents,id'],
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

    protected function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = Job::where('slug', 'like', "{$slug}%")->count();
        return $count ? "{$slug}-" . ($count + 1) : $slug;
    }
}

