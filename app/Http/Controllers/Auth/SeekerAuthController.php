<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Seeker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SeekerAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.seeker.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('seeker')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/seeker/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    public function showRegisterForm()
    {
        return view('auth.seeker.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:seekers',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        $seeker = Seeker::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
        ]);

        Auth::guard('seeker')->login($seeker);

        return redirect('/seeker/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('seeker')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/seeker/login');
    }
}

