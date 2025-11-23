<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class RecaptchaSettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::getAll();
        $tab = 'recaptcha';
        return view('admin.settings.index', compact('settings', 'tab'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'recaptcha_site_key' => 'nullable|string|max:255',
            'recaptcha_secret_key' => 'nullable|string|max:255',
        ]);

        Setting::set('recaptcha_site_key', $request->input('recaptcha_site_key'));
        Setting::set('recaptcha_secret_key', $request->input('recaptcha_secret_key'));
        Setting::set('recaptcha_enabled', $request->has('recaptcha_enabled') ? '1' : '0');

        return redirect()->route('admin.settings.recaptcha')->with('success', 'reCAPTCHA settings updated successfully.');
    }
}
