<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $seeker = Auth::guard('seeker')->user();
        return view('seeker.dashboard', compact('seeker'));
    }
}

