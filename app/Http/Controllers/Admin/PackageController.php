<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\CompanyPackageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{
    public function requests()
    {
        $requests = CompanyPackageRequest::with(['company', 'package'])
            ->latest()
            ->paginate(20);

        return view('admin.packages.requests', compact('requests'));
    }

    public function approve(Request $request, CompanyPackageRequest $packageRequest)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        if ($packageRequest->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        $packageRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::guard('admin')->id(),
            'approved_at' => now(),
            'admin_notes' => $request->admin_notes,
        ]);

        // Assign package to company
        $packageRequest->company->update([
            'package_id' => $packageRequest->package_id,
        ]);

        return back()->with('success', 'Package request approved and assigned to company.');
    }

    public function reject(Request $request, CompanyPackageRequest $packageRequest)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        if ($packageRequest->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        $packageRequest->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'Package request rejected.');
    }
}
