<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobType;
use Illuminate\Http\Request;

class JobTypeController extends Controller
{
    public function index()
    {
        $jobTypes = JobType::orderBy('sort_order')->orderBy('name')->get();
        $tab = 'job-types';
        $settings = [];
        return view('admin.settings.index', compact('jobTypes', 'tab', 'settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:job_types,name',
            'sort_order' => 'nullable|integer',
        ]);

        JobType::create([
            'name' => $request->name,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => true,
        ]);

        return back()->with('success', 'Job type added successfully.');
    }

    public function update(Request $request, JobType $jobType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:job_types,name,' . $jobType->id,
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $jobType->update($request->only(['name', 'sort_order', 'is_active']));

        return back()->with('success', 'Job type updated successfully.');
    }

    public function destroy(JobType $jobType)
    {
        $jobType->delete();
        return back()->with('success', 'Job type deleted successfully.');
    }
}
