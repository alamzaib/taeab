<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SmtpSettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::getAll();
        $tab = 'smtp';
        return view('admin.settings.index', compact('settings', 'tab'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|integer|min:1|max:65535',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string|max:255',
            'smtp_encryption' => 'nullable|in:tls,ssl,null',
            'smtp_from_address' => 'nullable|email|max:255',
            'smtp_from_name' => 'nullable|string|max:255',
        ]);

        $smtpSettings = [
            'smtp_host',
            'smtp_port',
            'smtp_username',
            'smtp_password',
            'smtp_encryption',
            'smtp_from_address',
            'smtp_from_name',
        ];

        foreach ($smtpSettings as $key) {
            Setting::set($key, $request->input($key));
        }

        // Update .env file or config cache if needed
        // For now, we'll store in settings table and use them in the command

        return back()->with('success', 'SMTP settings updated successfully.');
    }
}
