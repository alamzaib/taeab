<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobApplicationMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $company = Auth::guard('company')->user();

        $applicationsQuery = JobApplication::with(['job', 'seeker'])
            ->whereHas('job', function ($query) use ($company) {
                $query->where('company_id', $company->id);
            })
            ->latest();

        if ($request->filled('job')) {
            $applicationsQuery->where('job_id', $request->input('job'));
        }

        $applications = $applicationsQuery->paginate(12)->withQueryString();

        $jobs = Job::where('company_id', $company->id)
            ->orderBy('title')
            ->get(['id', 'title']);

        return view('company.applications.index', compact('applications', 'jobs', 'company'));
    }

    public function show(Request $request, JobApplication $application)
    {
        $company = Auth::guard('company')->user();
        abort_if($application->job->company_id !== $company->id, 403);

        $messages = $application->messages()
            ->with(['seeker', 'company'])
            ->orderBy('created_at')
            ->get();

        $application->messages()
            ->whereNull('read_at')
            ->where('sender_type', 'seeker')
            ->update(['read_at' => now()]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'messages' => $messages->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'sender_type' => $message->sender_type,
                        'message' => $message->message,
                        'created_at' => $message->created_at->toIso8601String(),
                    ];
                }),
            ]);
        }

        return view('company.applications.show', compact('application', 'messages', 'company'));
    }

    public function storeMessage(Request $request, JobApplication $application)
    {
        $company = Auth::guard('company')->user();
        abort_if($application->job->company_id !== $company->id, 403);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:3000'],
        ]);

        JobApplicationMessage::create([
            'job_application_id' => $application->id,
            'sender_type' => 'company',
            'company_id' => $company->id,
            'seeker_id' => $application->seeker_id,
            'message' => $validated['message'],
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully.',
            ]);
        }

        return back()->with('success', 'Message sent to seeker.');
    }
}

