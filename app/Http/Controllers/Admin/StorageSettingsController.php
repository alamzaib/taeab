<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class StorageSettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::getAll();
        $tab = 'storage';
        return view('admin.settings.index', compact('settings', 'tab'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'storage_driver' => 'required|in:local,s3',
            'aws_access_key_id' => 'required_if:storage_driver,s3|nullable|string|max:255',
            'aws_secret_access_key' => 'required_if:storage_driver,s3|nullable|string|max:255',
            'aws_default_region' => 'required_if:storage_driver,s3|nullable|string|max:255',
            'aws_bucket' => 'required_if:storage_driver,s3|nullable|string|max:255',
            'aws_url' => 'nullable|url|max:255',
            'aws_endpoint' => 'nullable|url|max:255',
        ]);

        Setting::set('storage_driver', $request->input('storage_driver'));
        
        if ($request->input('storage_driver') === 's3') {
            Setting::set('aws_access_key_id', $request->input('aws_access_key_id'));
            Setting::set('aws_secret_access_key', $request->input('aws_secret_access_key'));
            Setting::set('aws_default_region', $request->input('aws_default_region'));
            Setting::set('aws_bucket', $request->input('aws_bucket'));
            Setting::set('aws_url', $request->input('aws_url'));
            Setting::set('aws_endpoint', $request->input('aws_endpoint'));
        }

        return redirect()->route('admin.settings.storage')
            ->with('success', 'Storage settings updated successfully.');
    }
}
