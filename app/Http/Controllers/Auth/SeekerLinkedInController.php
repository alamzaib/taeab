<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Seeker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SeekerLinkedInController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('linkedin')->scopes(['r_emailaddress', 'r_liteprofile'])->redirect();
    }

    public function callback()
    {
        try {
            $linkedinUser = Socialite::driver('linkedin')->user();
        } catch (\Throwable $th) {
            return redirect()->route('seeker.login')->with('error', 'Unable to login with LinkedIn, please try again.');
        }

        $email = $linkedinUser->getEmail();

        if (!$email) {
            return redirect()->route('seeker.login')->with('error', 'LinkedIn account does not have a public email address.');
        }

        $seeker = Seeker::where('linkedin_id', $linkedinUser->getId())
            ->orWhere('email', $email)
            ->first();

        if (!$seeker) {
            $seeker = Seeker::create([
                'name' => $linkedinUser->getName() ?: $linkedinUser->getNickname() ?: 'LinkedIn User',
                'email' => $email,
                'linkedin_id' => $linkedinUser->getId(),
                'linkedin_avatar' => $linkedinUser->getAvatar(),
                'password' => Hash::make(Str::random(32)),
                'status' => 'active',
            ]);
        } else {
            $seeker->update([
                'linkedin_id' => $linkedinUser->getId(),
                'linkedin_avatar' => $linkedinUser->getAvatar(),
                'status' => $seeker->status ?? 'active',
            ]);
        }

        Auth::guard('seeker')->login($seeker, true);

        return redirect()->route('seeker.dashboard')->with('success', 'Logged in via LinkedIn successfully.');
    }
}

