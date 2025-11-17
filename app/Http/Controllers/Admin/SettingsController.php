<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $settings = Setting::getAll();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'application_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'footer_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png,jpg|max:512',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle file uploads
        if ($request->hasFile('application_logo')) {
            $logoPath = $request->file('application_logo')->store('settings', 'public');
            Setting::set('application_logo', $logoPath);
        }

        if ($request->hasFile('footer_logo')) {
            $footerLogoPath = $request->file('footer_logo')->store('settings', 'public');
            Setting::set('footer_logo', $footerLogoPath);
        }

        if ($request->hasFile('favicon')) {
            $faviconPath = $request->file('favicon')->store('settings', 'public');
            Setting::set('favicon', $faviconPath);
        }

        // Handle delete logo requests
        if ($request->has('delete_application_logo')) {
            $oldLogo = Setting::get('application_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            Setting::set('application_logo', null);
        }

        if ($request->has('delete_footer_logo')) {
            $oldLogo = Setting::get('footer_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            Setting::set('footer_logo', null);
        }

        if ($request->has('delete_favicon')) {
            $oldFavicon = Setting::get('favicon');
            if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                Storage::disk('public')->delete($oldFavicon);
            }
            Setting::set('favicon', null);
        }

        // Update text settings
        $textSettings = [
            'meta_title',
            'meta_description',
            'meta_keywords',
            'google_analytics_code',
            'custom_css',
            'custom_javascript',
            'phone',
            'official_email',
            'address',
            'longitude',
            'latitude',
            'city',
            'country',
        ];

        foreach ($textSettings as $key) {
            Setting::set($key, $request->input($key));
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully!');
    }
}

