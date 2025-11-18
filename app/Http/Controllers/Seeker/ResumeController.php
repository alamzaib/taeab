<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\SeekerEducation;
use App\Models\SeekerExperience;
use App\Models\SeekerHobby;
use App\Models\SeekerReference;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResumeController extends Controller
{
    public function updateProfile(Request $request): RedirectResponse
    {
        $seeker = Auth::guard('seeker')->user();

        $data = $request->validate([
            'resume_bio' => ['nullable', 'string'],
            'resume_portfolio_url' => ['nullable', 'url'],
            'linkedin_url' => ['nullable', 'url'],
            'skills_list' => ['nullable', 'string'],
        ]);

        $skills = $this->sanitizeSkills($data['skills_list'] ?? null);
        $seeker->resume_bio = $data['resume_bio'] ?? null;
        $seeker->resume_portfolio_url = $data['resume_portfolio_url'] ?? null;
        $seeker->linkedin_url = $data['linkedin_url'] ?? $seeker->linkedin_url;
        $seeker->skills = $skills ? implode(',', $skills) : null;
        $seeker->save();

        return back()->with('success', 'Resume profile updated successfully.');
    }

    public function storeEducation(Request $request): RedirectResponse
    {
        $seeker = Auth::guard('seeker')->user();

        $data = $request->validate([
            'institution' => ['required', 'string', 'max:255'],
            'degree' => ['nullable', 'string', 'max:255'],
            'field_of_study' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'description' => ['nullable', 'string'],
        ]);

        $seeker->educations()->create($data);

        return back()->with('success', 'Education added.');
    }

    public function destroyEducation(SeekerEducation $education): RedirectResponse
    {
        $this->authorizeEntry($education);
        $education->delete();

        return back()->with('success', 'Education removed.');
    }

    public function storeExperience(Request $request): RedirectResponse
    {
        $seeker = Auth::guard('seeker')->user();

        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'role_title' => ['required', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_current' => ['sometimes', 'boolean'],
            'achievements' => ['nullable', 'string'],
        ]);

        $data['is_current'] = $request->boolean('is_current');

        $seeker->experiences()->create($data);

        return back()->with('success', 'Experience added.');
    }

    public function destroyExperience(SeekerExperience $experience): RedirectResponse
    {
        $this->authorizeEntry($experience);
        $experience->delete();

        return back()->with('success', 'Experience removed.');
    }

    public function storeReference(Request $request): RedirectResponse
    {
        $seeker = Auth::guard('seeker')->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ]);

        $seeker->references()->create($data);

        return back()->with('success', 'Reference added.');
    }

    public function destroyReference(SeekerReference $reference): RedirectResponse
    {
        $this->authorizeEntry($reference);
        $reference->delete();

        return back()->with('success', 'Reference removed.');
    }

    public function storeHobby(Request $request): RedirectResponse
    {
        $seeker = Auth::guard('seeker')->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string'],
        ]);

        $seeker->hobbies()->create($data);

        return back()->with('success', 'Hobby added.');
    }

    public function destroyHobby(SeekerHobby $hobby): RedirectResponse
    {
        $this->authorizeEntry($hobby);
        $hobby->delete();

        return back()->with('success', 'Hobby removed.');
    }

    public function preview()
    {
        $seeker = Auth::guard('seeker')->user();
        $seeker->load([
            'educations' => fn ($q) => $q->orderByDesc('end_date')->orderByDesc('start_date'),
            'experiences' => fn ($q) => $q->orderByDesc('is_current')->orderByDesc('end_date')->orderByDesc('start_date'),
            'references',
            'hobbies',
        ]);

        $skills = $seeker->skills
            ? array_filter(array_map('trim', explode(',', $seeker->skills)))
            : [];

        return view('seeker.resume.preview', compact('seeker', 'skills'));
    }

    protected function authorizeEntry($model): void
    {
        $seekerId = Auth::guard('seeker')->id();

        if ($model->seeker_id !== $seekerId) {
            abort(403);
        }
    }

    protected function sanitizeSkills(?string $skills): array
    {
        if (!$skills) {
            return [];
        }

        $list = array_filter(array_map('trim', explode(',', $skills)));

        return array_values(array_unique($list));
    }
}

