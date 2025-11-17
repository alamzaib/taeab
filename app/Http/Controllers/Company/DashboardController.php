<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:company');
    }

    public function index()
    {
        $company = Auth::guard('company')->user();
        return view('company.dashboard', compact('company'));
    }
}

