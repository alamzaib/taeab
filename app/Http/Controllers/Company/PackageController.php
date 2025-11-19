<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\CompanyPackageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{
    public function index()
    {
        $company = Auth::guard('company')->user()->load('package');
        $packages = Package::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        
        $currentPackage = $company->package;
        $pendingRequest = CompanyPackageRequest::where('company_id', $company->id)
            ->where('status', 'pending')
            ->latest()
            ->first();
        
        return view('company.packages.index', compact('company', 'packages', 'currentPackage', 'pendingRequest'));
    }

    public function request(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'message' => 'nullable|string|max:1000',
        ]);

        $company = Auth::guard('company')->user();
        
        // Check if there's already a pending request
        $existingRequest = CompanyPackageRequest::where('company_id', $company->id)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return back()->with('error', 'You already have a pending package request.');
        }

        CompanyPackageRequest::create([
            'company_id' => $company->id,
            'package_id' => $request->package_id,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Package request submitted successfully. Admin will review it soon.');
    }
}
