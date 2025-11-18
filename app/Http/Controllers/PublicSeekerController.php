<?php

namespace App\Http\Controllers;

use App\Models\Seeker;

class PublicSeekerController extends Controller
{
    public function show(string $code)
    {
        $seeker = Seeker::where('unique_code', $code)
            ->with([
                'educations' => fn ($query) => $query->orderByDesc('end_date')->orderByDesc('start_date'),
                'experiences' => fn ($query) => $query->orderByDesc('is_current')->orderByDesc('end_date')->orderByDesc('start_date'),
                'references',
                'hobbies',
            ])
            ->firstOrFail();

        $skills = $seeker->skills
            ? array_filter(array_map('trim', explode(',', $seeker->skills)))
            : [];

        $defaultResume = $seeker->documents()
            ->where('type', 'resume')
            ->where('is_default', true)
            ->first();

        return view('seekers.public.show', compact('seeker', 'skills', 'defaultResume'));
    }
}

