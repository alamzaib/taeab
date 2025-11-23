<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Services\RecaptchaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AgentAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.agent.login');
    }

    public function login(Request $request, RecaptchaService $recaptcha)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Verify reCAPTCHA
        if (!$recaptcha->verify($request->input('g-recaptcha-response'), $request->ip())) {
            throw ValidationException::withMessages([
                'g-recaptcha-response' => ['reCAPTCHA verification failed. Please try again.'],
            ]);
        }

        if (Auth::guard('agent')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/agent/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    public function showRegisterForm()
    {
        return view('auth.agent.register');
    }

    public function register(Request $request, RecaptchaService $recaptcha)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:agents',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
        ]);

        // Verify reCAPTCHA
        if (!$recaptcha->verify($request->input('g-recaptcha-response'), $request->ip())) {
            return back()->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed. Please try again.'])->withInput();
        }

        $agent = Agent::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
        ]);

        Auth::guard('agent')->login($agent);

        return redirect('/agent/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('agent')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/agent/login');
    }
}

