<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobDocument;
use App\Models\ApplicationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class JobApplicationController extends Controller
{
    public function create(Request $request, string $slug)
    {
        $job = Job::with(['company'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $seeker = Auth::guard('seeker')->user();

        if (JobApplication::where('job_id', $job->id)->where('seeker_id', $seeker->id)->exists()) {
            return redirect()->route('jobs.show', $job->slug)->with('success', 'You have already applied for this job.');
        }

        $resumes = $seeker->documents()->where('type', 'resume')->get();
        $coverLetters = $seeker->documents()->where('type', 'cover_letter')->get();
        $defaultResume = $resumes->firstWhere('is_default', true);
        $defaultCover = $coverLetters->firstWhere('is_default', true);

        return view('jobs.apply', compact('job', 'resumes', 'coverLetters', 'defaultResume', 'defaultCover'));
    }

    public function store(Request $request, string $slug)
    {
        $request->validate([
            'resume_document_id' => ['nullable', 'integer'],
            'cover_letter_document_id' => ['nullable', 'integer'],
            'cover_letter' => ['nullable', 'string', 'max:2000'],
        ]);

        $job = Job::where('slug', $slug)->where('status', 'published')->firstOrFail();
        $seeker = Auth::guard('seeker')->user();

        if (JobApplication::where('job_id', $job->id)->where('seeker_id', $seeker->id)->exists()) {
            return redirect()->route('jobs.show', $job->slug)->with('success', 'You have already applied for this job.');
        }

        $resume = $this->resolveDocument($seeker, $request->input('resume_document_id'), 'resume');
        if (!$resume) {
            return redirect()->route('seeker.documents.index')->with('error', 'Please upload and select a resume before applying.');
        }

        $coverLetterDoc = $this->resolveDocument($seeker, $request->input('cover_letter_document_id'), 'cover_letter', allowNull: true);

        $application = JobApplication::create([
            'job_id' => $job->id,
            'seeker_id' => $seeker->id,
            'resume_document_id' => $resume->id,
            'cover_letter_document_id' => optional($coverLetterDoc)->id,
            'cover_letter' => $request->input('cover_letter'),
            'status' => 'submitted',
        ]);

        // Create notification for company
        try {
            ApplicationNotification::create([
                'recipient_type' => 'company',
                'recipient_id' => $job->company_id,
                'type' => 'application',
                'title' => 'New Job Application',
                'message' => $seeker->name . ' has applied for the position: ' . $job->title,
                'job_application_id' => $application->id,
                'job_id' => $job->id,
                'email_sent' => false, // Explicitly set to false for email sending
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create notification for application', [
                'application_id' => $application->id,
                'seeker_id' => $seeker->id,
                'company_id' => $job->company_id,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()->route('jobs.show', $job->slug)->with('success', 'Application submitted successfully!');
    }

    protected function resolveDocument($seeker, ?int $documentId, string $type, bool $allowNull = false): ?JobDocument
    {
        if ($documentId) {
            $document = $seeker->documents()->where('id', $documentId)->where('type', $type)->first();
            if ($document) {
                return $document;
            }
        }

        $default = $seeker->documents()->where('type', $type)->where('is_default', true)->first();
        if ($default) {
            return $default;
        }

        return $allowNull ? null : null;
    }
}

