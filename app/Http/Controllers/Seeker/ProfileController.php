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

    public function update(Request $request, StorageService $storageService)
    {
        $seeker = $request->user('seeker');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
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

        // Process skills - it comes as a comma-separated string from the hidden input
        $skills = isset($validated['skills']) ? trim($validated['skills']) : null;
        $skills = !empty($skills) ? $skills : null;

        // Update all fields - use validated values, or null if empty
        // Note: Email cannot be changed
        $seeker->name = $validated['name'];
        $seeker->phone = $validated['phone'];
        $seeker->whatsapp_number = !empty($validated['whatsapp_number']) ? $validated['whatsapp_number'] : null;
        $seeker->about = !empty($validated['about']) ? $validated['about'] : null;
        $seeker->skills = $skills;
        $seeker->current_salary = !empty($validated['current_salary']) ? $validated['current_salary'] : null;
        $seeker->target_salary = !empty($validated['target_salary']) ? $validated['target_salary'] : null;
        $seeker->current_company = !empty($validated['current_company']) ? $validated['current_company'] : null;
        $seeker->residence_country = !empty($validated['residence_country']) ? $validated['residence_country'] : null;
        $seeker->nationality = !empty($validated['nationality']) ? $validated['nationality'] : null;
        $seeker->date_of_birth = !empty($validated['date_of_birth']) ? $validated['date_of_birth'] : null;
        $seeker->address = !empty($validated['address']) ? $validated['address'] : null;
        $seeker->linkedin_url = !empty($validated['linkedin_url']) ? $validated['linkedin_url'] : null;

        if ($request->hasFile('profile_photo')) {
            if ($seeker->profile_photo_path) {
                $storageService->delete($seeker->profile_photo_path);
            }
            $seeker->profile_photo_path = $storageService->storeFile($request->file('profile_photo'), 'seeker-media');
        }

        if ($request->hasFile('profile_cover')) {
            if ($seeker->profile_cover_path) {
                $storageService->delete($seeker->profile_cover_path);
            }
            $seeker->profile_cover_path = $storageService->storeFile($request->file('profile_cover'), 'seeker-media');
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

