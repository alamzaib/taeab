<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobApplicationMessage;
use App\Models\ApplicationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ApplicationController extends Controller
{
    public function index()
    {
        $seeker = Auth::guard('seeker')->user();

        $applications = JobApplication::with('job.company')
            ->where('seeker_id', $seeker->id)
            ->latest()
            ->paginate(12);

        return view('seeker.applications.index', compact('applications', 'seeker'));
    }

    public function show(Request $request, JobApplication $application)
    {
        $seeker = Auth::guard('seeker')->user();
        abort_if($application->seeker_id !== $seeker->id, 403);

        $messages = $application->messages()
            ->with('company')
            ->orderBy('created_at')
            ->get();

        $application->messages()
            ->whereNull('read_at')
            ->where('sender_type', 'company')
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
                })
            ]);
        }

        return view('seeker.applications.show', compact('application', 'messages', 'seeker'));
    }

    public function storeMessage(Request $request, JobApplication $application)
    {
        $seeker = Auth::guard('seeker')->user();
        abort_if($application->seeker_id !== $seeker->id, 403);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:3000'],
        ]);

        // Load job relationship to ensure it's available
        $application->load('job');

        $message = JobApplicationMessage::create([
            'job_application_id' => $application->id,
            'sender_type' => 'seeker',
            'seeker_id' => $seeker->id,
            'company_id' => $application->job->company_id,
            'message' => $validated['message'],
        ]);

        // Create notification for company
        try {
            ApplicationNotification::create([
                'recipient_type' => 'company',
                'recipient_id' => $application->job->company_id,
                'type' => 'message',
                'title' => 'New Message from ' . $seeker->name,
                'message' => 'You have a new message regarding the application for: ' . $application->job->title,
                'job_application_id' => $application->id,
                'job_id' => $application->job_id,
                'email_sent' => false, // Explicitly set to false for email sending
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create notification for message', [
                'application_id' => $application->id,
                'seeker_id' => $seeker->id,
                'company_id' => $application->job->company_id,
                'error' => $e->getMessage(),
            ]);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully.'
            ]);
        }

        return back()->with('success', 'Message sent to company.');
    }
}

