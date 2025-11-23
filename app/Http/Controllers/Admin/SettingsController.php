<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\StorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $settings = Setting::getAll();
        $tab = $request->get('tab', 'general');
        return view('admin.settings.index', compact('settings', 'tab'));
    }

    public function update(Request $request, StorageService $storageService)
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
        try {
            if ($request->hasFile('application_logo')) {
                $file = $request->file('application_logo');
                
                // Validate file before processing
                if (!$file->isValid()) {
                    return redirect()->back()
                        ->with('error', 'Invalid file uploaded for application logo.')
                        ->withInput();
                }
                
                \Log::info('Application logo file received', [
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'is_valid' => $file->isValid(),
                    'error' => $file->getError()
                ]);
                
                $oldLogo = Setting::get('application_logo');
                if ($oldLogo) {
                    $storageService->delete($oldLogo);
                }
                
                $logoPath = $storageService->storeFile($file, 'settings');
                
                // Verify file exists immediately after storage
                $fileExists = \Illuminate\Support\Facades\Storage::disk('public')->exists($logoPath);
                if (!$fileExists) {
                    \Log::error('File not found after storage', ['path' => $logoPath]);
                    return redirect()->back()
                        ->with('error', 'File was uploaded but could not be verified. Please try again.')
                        ->withInput();
                }
                
                Setting::set('application_logo', $logoPath);
                
                // Get URL for logging
                $generatedUrl = $storageService->getUrl($logoPath);
                
                \Log::info('Application logo uploaded successfully', [
                    'path' => $logoPath,
                    'file_exists' => $fileExists,
                    'url' => $generatedUrl,
                    'disk' => 'public',
                    'full_path' => storage_path('app/public/' . $logoPath)
                ]);
            }

            if ($request->hasFile('footer_logo')) {
                $file = $request->file('footer_logo');
                \Log::info('Footer logo file received', [
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'is_valid' => $file->isValid()
                ]);
                
                $oldLogo = Setting::get('footer_logo');
                if ($oldLogo) {
                    $storageService->delete($oldLogo);
                }
                
                $footerLogoPath = $storageService->storeFile($file, 'settings');
                Setting::set('footer_logo', $footerLogoPath);
                \Log::info('Footer logo uploaded successfully', [
                    'path' => $footerLogoPath,
                    'url' => $storageService->getUrl($footerLogoPath)
                ]);
            }

            if ($request->hasFile('favicon')) {
                $file = $request->file('favicon');
                \Log::info('Favicon file received', [
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'is_valid' => $file->isValid()
                ]);
                
                $oldFavicon = Setting::get('favicon');
                if ($oldFavicon) {
                    $storageService->delete($oldFavicon);
                }
                
                $faviconPath = $storageService->storeFile($file, 'settings');
                Setting::set('favicon', $faviconPath);
                \Log::info('Favicon uploaded successfully', [
                    'path' => $faviconPath,
                    'url' => $storageService->getUrl($faviconPath)
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('File upload error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'Failed to upload file: ' . $e->getMessage())
                ->withInput();
        }

        // Handle delete logo requests
        if ($request->has('delete_application_logo')) {
            $oldLogo = Setting::get('application_logo');
            if ($oldLogo) {
                $storageService->delete($oldLogo);
            }
            Setting::set('application_logo', null);
        }

        if ($request->has('delete_footer_logo')) {
            $oldLogo = Setting::get('footer_logo');
            if ($oldLogo) {
                $storageService->delete($oldLogo);
            }
            Setting::set('footer_logo', null);
        }

        if ($request->has('delete_favicon')) {
            $oldFavicon = Setting::get('favicon');
            if ($oldFavicon) {
                $storageService->delete($oldFavicon);
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
            'smtp_host',
            'smtp_port',
            'smtp_username',
            'smtp_password',
            'smtp_encryption',
            'smtp_from_address',
            'smtp_from_name',
            'recaptcha_site_key',
            'recaptcha_secret_key',
        ];

        foreach ($textSettings as $key) {
            Setting::set($key, $request->input($key));
        }

        // Handle reCAPTCHA enabled checkbox
        Setting::set('recaptcha_enabled', $request->has('recaptcha_enabled') ? '1' : '0');

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully!');
    }
}

