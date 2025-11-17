<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Seeker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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

        $credentials = $request->only('email', 'password');

        if (Auth::guard('seeker')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $redirect = $this->safeRedirect($request->input('redirect'));
            if ($redirect) {
                return redirect($redirect);
            }

            return redirect()->intended(route('seeker.dashboard'));
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
            'phone' => 'required|string|max:20',
        ]);

        $seeker = Seeker::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
        ]);

        Auth::guard('seeker')->login($seeker);

        $redirect = $this->safeRedirect($request->input('redirect'));
        if ($redirect) {
            return redirect($redirect);
        }

        return redirect(route('seeker.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::guard('seeker')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/seeker/login');
    }

    protected function safeRedirect(?string $redirect): ?string
    {
        if ($redirect && Str::startsWith($redirect, '/')) {
            return $redirect;
        }

        return null;
    }
}

