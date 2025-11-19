<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:2000',
        ]);

        $settings = \App\Models\Setting::getAll();
        $adminEmail = $settings['official_email'] ?? config('mail.from.address');

        // Create notification record
        Notification::create([
            'to_email' => $adminEmail,
            'subject' => 'Contact Form: ' . $request->subject,
            'message' => "Name: {$request->name}\nEmail: {$request->email}\n\nMessage:\n{$request->message}",
            'status' => 'pending',
            'failed_count' => 0,
        ]);

        return back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }
}
