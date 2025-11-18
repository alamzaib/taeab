<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $seeker = $request->user('seeker');
        return view('seeker.profile.edit', compact('seeker'));
    }

    public function update(Request $request)
    {
        $seeker = $request->user('seeker');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('seekers')->ignore($seeker->id)],
            'phone' => ['required', 'string', 'max:20'],
            'whatsapp_number' => ['nullable', 'string', 'max:20'],
            'about' => ['nullable', 'string'],
            'skills' => ['nullable', 'string'],
            'current_salary' => ['nullable', 'string', 'max:50'],
            'target_salary' => ['nullable', 'string', 'max:50'],
            'current_company' => ['nullable', 'string', 'max:255'],
            'residence_country' => ['nullable', 'string', 'max:120'],
            'nationality' => ['nullable', 'string', 'max:120'],
            'date_of_birth' => ['nullable', 'date'],
            'address' => ['nullable', 'string', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'profile_cover' => ['nullable', 'image', 'max:4096'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $seeker->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'whatsapp_number' => $validated['whatsapp_number'] ?? $seeker->whatsapp_number,
            'about' => $validated['about'] ?? $seeker->about,
            'skills' => isset($validated['skills']) ? implode(',', (array) $validated['skills']) : $seeker->skills,
            'current_salary' => $validated['current_salary'] ?? $seeker->current_salary,
            'target_salary' => $validated['target_salary'] ?? $seeker->target_salary,
            'current_company' => $validated['current_company'] ?? $seeker->current_company,
            'residence_country' => $validated['residence_country'] ?? $seeker->residence_country,
            'nationality' => $validated['nationality'] ?? $seeker->nationality,
            'date_of_birth' => $validated['date_of_birth'] ?? $seeker->date_of_birth,
            'address' => $validated['address'] ?? $seeker->address,
            'linkedin_url' => $validated['linkedin_url'] ?? $seeker->linkedin_url,
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($seeker->profile_photo_path) {
                Storage::disk('public')->delete($seeker->profile_photo_path);
            }
            $seeker->profile_photo_path = $request->file('profile_photo')->store('seeker-media', 'public');
        }

        if ($request->hasFile('profile_cover')) {
            if ($seeker->profile_cover_path) {
                Storage::disk('public')->delete($seeker->profile_cover_path);
            }
            $seeker->profile_cover_path = $request->file('profile_cover')->store('seeker-media', 'public');
        }

        if (!empty($validated['password'])) {
            $seeker->password = Hash::make($validated['password']);
        }

        $seeker->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function refresh(Request $request)
    {
        $seeker = $request->user('seeker');
        $seeker->profile_refreshed_at = now();
        $seeker->save();

        return back()->with('success', 'Profile visibility refreshed successfully.');
    }
}

