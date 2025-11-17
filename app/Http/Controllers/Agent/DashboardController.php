<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:agent');
    }

    public function index()
    {
        $agent = Auth::guard('agent')->user();
        return view('agent.dashboard', compact('agent'));
    }
}

