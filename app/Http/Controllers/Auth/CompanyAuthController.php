<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CompanyAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.company.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('company')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/company/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    public function showRegisterForm()
    {
        return view('auth.company.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:companies',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'company_name' => 'required|string|max:255',
            'company_size' => 'nullable|string|max:50',
            'industry' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
        ]);

        $company = Company::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'company_name' => $request->company_name,
            'company_size' => $request->company_size,
            'industry' => $request->industry,
            'website' => $request->website,
        ]);

        Auth::guard('company')->login($company);

        return redirect('/company/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('company')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/company/login');
    }
}

