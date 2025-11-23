<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobApplication;
use App\Models\ApplicationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class LinkedInJobApplicationController extends Controller
{
    public function redirect(Request $request, string $slug)
    {
        try {
            $request->validate([
                'cover_letter' => 'nullable|string|max:2000',
            ]);

            $job = Job::where('slug', $slug)->where('status', 'published')->firstOrFail();
            $seeker = Auth::guard('seeker')->user();

            if (!$seeker) {
                return redirect()->route('seeker.login')
                    ->with('error', 'Please login to apply for jobs.');
            }

            if (JobApplication::where('job_id', $job->id)->where('seeker_id', $seeker->id)->exists()) {
                return redirect()->route('jobs.show', $job->slug)->with('error', 'You have already applied for this job.');
            }

            // Store job slug and cover letter in session for after LinkedIn callback
            session([
                'linkedin_apply_job_slug' => $slug,
                'linkedin_apply_cover_letter' => $request->input('cover_letter', ''),
            ]);

            // Request additional scopes for profile data
            // Use the job application callback URL
            $redirectUrl = route('jobs.apply.linkedin.callback');
            
            Log::info('Redirecting to LinkedIn for job application', [
                'job_slug' => $slug,
                'seeker_id' => $seeker->id,
                'redirect_url' => $redirectUrl,
            ]);
            
            return Socialite::driver('linkedin')
                ->scopes(['r_emailaddress', 'r_liteprofile', 'r_basicprofile'])
                ->redirectUrl($redirectUrl)
                ->redirect();
        } catch (\Exception $e) {
            Log::error('LinkedIn redirect error: ' . $e->getMessage(), [
                'job_slug' => $slug,
                'error' => $e->getTraceAsString(),
            ]);
            return redirect()->route('jobs.show', $slug)
                ->with('error', 'Unable to connect with LinkedIn. Please try again.');
        }
    }

    public function callback()
    {
        try {
            $linkedinUser = Socialite::driver('linkedin')->user();
        } catch (\Throwable $th) {
            Log::error('LinkedIn application callback error: ' . $th->getMessage());
            return redirect()->route('jobs.index')
                ->with('error', 'Unable to connect with LinkedIn. Please try again.');
        }

        // Check if user is authenticated, if not redirect to login
        $seeker = Auth::guard('seeker')->user();
        if (!$seeker) {
            // Store LinkedIn data in session temporarily
            session([
                'linkedin_temp_user' => [
                    'id' => $linkedinUser->getId(),
                    'email' => $linkedinUser->getEmail(),
                    'name' => $linkedinUser->getName(),
                ],
            ]);
            return redirect()->route('seeker.login')
                ->with('error', 'Please login to complete your LinkedIn application.');
        }

        $jobSlug = session('linkedin_apply_job_slug');
        $coverLetter = session('linkedin_apply_cover_letter');

        if (!$jobSlug) {
            return redirect()->route('jobs.index')
                ->with('error', 'Job information not found. Please try again.');
        }

        $job = Job::where('slug', $jobSlug)->where('status', 'published')->firstOrFail();

        // Check if already applied
        if (JobApplication::where('job_id', $job->id)->where('seeker_id', $seeker->id)->exists()) {
            session()->forget(['linkedin_apply_job_slug', 'linkedin_apply_cover_letter']);
            return redirect()->route('jobs.show', $job->slug)
                ->with('error', 'You have already applied for this job.');
        }

        // Prepare LinkedIn profile data
        $linkedinData = [
            'id' => $linkedinUser->getId(),
            'name' => $linkedinUser->getName(),
            'email' => $linkedinUser->getEmail(),
            'avatar' => $linkedinUser->getAvatar(),
            'nickname' => $linkedinUser->getNickname(),
            'profile_url' => $linkedinUser->user['publicProfileUrl'] ?? null,
            'headline' => $linkedinUser->user['headline'] ?? null,
            'location' => $linkedinUser->user['location']['name'] ?? null,
            'industry' => $linkedinUser->user['industry'] ?? null,
        ];

        // Create job application with LinkedIn data
        $application = JobApplication::create([
            'job_id' => $job->id,
            'seeker_id' => $seeker->id,
            'cover_letter' => $coverLetter,
            'status' => 'submitted',
            'applied_via_linkedin' => true,
            'linkedin_profile_url' => $linkedinData['profile_url'],
            'linkedin_profile_data' => json_encode($linkedinData),
        ]);

        // Update seeker's LinkedIn info if not already set
        if (!$seeker->linkedin_id) {
            $seeker->update([
                'linkedin_id' => $linkedinUser->getId(),
                'linkedin_avatar' => $linkedinUser->getAvatar(),
            ]);
        }

        // Create notification for company
        try {
            ApplicationNotification::create([
                'recipient_type' => 'company',
                'recipient_id' => $job->company_id,
                'type' => 'application',
                'title' => 'New Job Application via LinkedIn',
                'message' => $seeker->name . ' has applied for the position: ' . $job->title . ' via LinkedIn',
                'job_application_id' => $application->id,
                'job_id' => $job->id,
                'email_sent' => false,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create notification for LinkedIn application', [
                'application_id' => $application->id,
                'seeker_id' => $seeker->id,
                'company_id' => $job->company_id,
                'error' => $e->getMessage(),
            ]);
        }

        // Clear session data
        session()->forget(['linkedin_apply_job_slug', 'linkedin_apply_cover_letter']);

        return redirect()->route('jobs.show', $job->slug)
            ->with('success', 'Application submitted successfully via LinkedIn!');
    }
}
