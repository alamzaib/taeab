@extends('layouts.app')

@section('title', 'Contact Us - Job Portal UAE')

@section('content')
    @php
        $settings = \App\Models\Setting::getAll();
    @endphp

    <div class="contact-hero">
        <div class="contact-hero-content">
            <h1 class="contact-hero-title">Get in Touch</h1>
            <p class="contact-hero-subtitle">We're here to help! Reach out to us and we'll respond as soon as possible.</p>
        </div>
    </div>

    <div class="contact-container">
        <div class="contact-wrapper">
            <!-- Contact Information Sidebar -->
            <div class="contact-info-sidebar">
                <div class="contact-info-card">
                    <div class="contact-info-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                    </div>
                    <h3 class="contact-info-title">Our Location</h3>
                    <p class="contact-info-text">
                        @if (!empty($settings['address']))
                            {{ $settings['address'] }}
                            @if (!empty($settings['city']))
                                , {{ $settings['city'] }}
                            @endif
                            @if (!empty($settings['country']))
                                , {{ $settings['country'] }}
                            @endif
                        @else
                            United Arab Emirates
                        @endif
                    </p>
                </div>

                <div class="contact-info-card">
                    <div class="contact-info-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="contact-info-title">Phone</h3>
                    <p class="contact-info-text">
                        @if (!empty($settings['phone']))
                            <a href="tel:{{ $settings['phone'] }}" class="contact-link">{{ $settings['phone'] }}</a>
                        @else
                            <span style="color: #94a3b8;">Not available</span>
                        @endif
                    </p>
                </div>

                <div class="contact-info-card">
                    <div class="contact-info-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                    </div>
                    <h3 class="contact-info-title">Email</h3>
                    <p class="contact-info-text">
                        @if (!empty($settings['official_email']))
                            <a href="mailto:{{ $settings['official_email'] }}"
                                class="contact-link">{{ $settings['official_email'] }}</a>
                        @else
                            <span style="color: #94a3b8;">Not available</span>
                        @endif
                    </p>
                </div>

                <div class="contact-info-card">
                    <div class="contact-info-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <h3 class="contact-info-title">Response Time</h3>
                    <p class="contact-info-text">We typically respond within 24 hours</p>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="contact-form-section">
                <div class="contact-form-card">
                    <h2 class="contact-form-title">Send us a Message</h2>
                    <p class="contact-form-subtitle">Fill out the form below and we'll get back to you as soon as possible.
                    </p>

                    @if (session('success'))
                        <div class="contact-alert contact-alert-success">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="contact-alert contact-alert-error">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                            <div>
                                <strong>Please fix the following errors:</strong>
                                <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST" class="contact-form" id="contactForm">
                        @csrf
                        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

                        <div class="contact-form-row">
                            <div class="contact-form-group">
                                <label for="name" class="contact-form-label">
                                    Your Name <span class="required">*</span>
                                </label>
                                <div class="contact-input-wrapper">
                                    <svg class="contact-input-icon" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <input type="text" name="name" id="name"
                                        class="contact-input @error('name') contact-input-error @enderror"
                                        value="{{ old('name') }}" placeholder="John Doe" required>
                                </div>
                                @error('name')
                                    <span class="contact-error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="contact-form-group">
                                <label for="email" class="contact-form-label">
                                    Your Email <span class="required">*</span>
                                </label>
                                <div class="contact-input-wrapper">
                                    <svg class="contact-input-icon" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <path
                                            d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                        </path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                    <input type="email" name="email" id="email"
                                        class="contact-input @error('email') contact-input-error @enderror"
                                        value="{{ old('email') }}" placeholder="john@example.com" required>
                                </div>
                                @error('email')
                                    <span class="contact-error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="contact-form-group">
                            <label for="subject" class="contact-form-label">
                                Subject <span class="required">*</span>
                            </label>
                            <div class="contact-input-wrapper">
                                <svg class="contact-input-icon" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="4" y1="7" x2="20" y2="7"></line>
                                    <line x1="4" y1="12" x2="20" y2="12"></line>
                                    <line x1="4" y1="17" x2="20" y2="17"></line>
                                </svg>
                                <input type="text" name="subject" id="subject"
                                    class="contact-input @error('subject') contact-input-error @enderror"
                                    value="{{ old('subject') }}" placeholder="How can we help you?" required>
                            </div>
                            @error('subject')
                                <span class="contact-error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="contact-form-group">
                            <label for="message" class="contact-form-label">
                                Message <span class="required">*</span>
                            </label>
                            <div class="contact-textarea-wrapper">
                                <svg class="contact-textarea-icon" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                </svg>
                                <textarea name="message" id="message" rows="6"
                                    class="contact-textarea @error('message') contact-input-error @enderror"
                                    placeholder="Tell us more about your inquiry..." required>{{ old('message') }}</textarea>
                            </div>
                            @error('message')
                                <span class="contact-error-message">{{ $message }}</span>
                            @enderror
                            <small class="contact-form-hint">Minimum 10 characters required</small>
                        </div>

                        <button type="submit" class="contact-submit-btn">
                            <span>Send Message</span>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <line x1="22" y1="2" x2="11" y2="13"></line>
                                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
    document.getElementById('contactForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        
        if (window.recaptchaSiteKey) {
            executeRecaptcha('contact').then(function(token) {
                document.getElementById('g-recaptcha-response').value = token;
                form.submit();
            });
        } else {
            form.submit();
        }
    });
    </script>
    @endpush

    <style>
        .contact-hero {
            background: linear-gradient(135deg, #235181 0%, #1a3d63 100%);
            padding: 80px 20px;
            text-align: center;
            color: white;
        }

        .contact-hero-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .contact-hero-title {
            font-size: 48px;
            font-weight: 700;
            margin: 0 0 16px;
            line-height: 1.2;
        }

        .contact-hero-subtitle {
            font-size: 20px;
            margin: 0;
            opacity: 0.9;
            line-height: 1.6;
        }

        .contact-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 60px 20px;
        }

        .contact-wrapper {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 40px;
            align-items: start;
        }

        .contact-info-sidebar {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .contact-info-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 32px;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .contact-info-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(35, 81, 129, 0.12);
            border-color: #235181;
        }

        .contact-info-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #235181 0%, #1a3d63 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-bottom: 20px;
        }

        .contact-info-title {
            font-size: 20px;
            font-weight: 600;
            color: #0f172a;
            margin: 0 0 12px;
        }

        .contact-info-text {
            font-size: 16px;
            color: #64748b;
            margin: 0;
            line-height: 1.6;
        }

        .contact-link {
            color: #235181;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .contact-link:hover {
            color: #1a3d63;
            text-decoration: underline;
        }

        .contact-form-section {
            position: sticky;
            top: 24px;
        }

        .contact-form-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .contact-form-title {
            font-size: 32px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 8px;
        }

        .contact-form-subtitle {
            font-size: 16px;
            color: #64748b;
            margin: 0 0 32px;
            line-height: 1.6;
        }

        .contact-alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 14px;
            line-height: 1.6;
        }

        .contact-alert-success {
            background: #ecfdf5;
            border: 1px solid #86efac;
            color: #065f46;
        }

        .contact-alert-error {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }

        .contact-alert svg {
            flex-shrink: 0;
            margin-top: 2px;
        }

        .contact-form {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .contact-form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .contact-form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .contact-form-label {
            font-size: 14px;
            font-weight: 600;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .required {
            color: #ef4444;
        }

        .contact-input-wrapper,
        .contact-textarea-wrapper {
            position: relative;
        }

        .contact-input-icon,
        .contact-textarea-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
            z-index: 1;
        }

        .contact-textarea-icon {
            top: 20px;
            transform: none;
        }

        .contact-input,
        .contact-textarea {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 16px;
            font-family: inherit;
            color: #0f172a;
            background: #ffffff;
            transition: all 0.3s ease;
        }

        .contact-textarea {
            padding-top: 16px;
            min-height: 140px;
            resize: vertical;
        }

        .contact-input:focus,
        .contact-textarea:focus {
            outline: none;
            border-color: #235181;
            box-shadow: 0 0 0 4px rgba(35, 81, 129, 0.1);
        }

        .contact-input::placeholder,
        .contact-textarea::placeholder {
            color: #94a3b8;
        }

        .contact-input-error {
            border-color: #ef4444;
        }

        .contact-input-error:focus {
            border-color: #ef4444;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }

        .contact-error-message {
            font-size: 13px;
            color: #ef4444;
            margin-top: 4px;
        }

        .contact-form-hint {
            font-size: 13px;
            color: #94a3b8;
            margin-top: 4px;
        }

        .contact-submit-btn {
            background: linear-gradient(135deg, #235181 0%, #1a3d63 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 16px 32px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
            margin-top: 8px;
            box-shadow: 0 4px 6px rgba(35, 81, 129, 0.2);
        }

        .contact-submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(35, 81, 129, 0.3);
        }

        .contact-submit-btn:active {
            transform: translateY(0);
        }

        .contact-submit-btn svg {
            transition: transform 0.3s;
        }

        .contact-submit-btn:hover svg {
            transform: translateX(4px);
        }

        @media (max-width: 968px) {
            .contact-wrapper {
                grid-template-columns: 1fr;
            }

            .contact-form-section {
                position: static;
            }

            .contact-form-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .contact-hero {
                padding: 60px 20px;
            }

            .contact-hero-title {
                font-size: 36px;
            }

            .contact-hero-subtitle {
                font-size: 18px;
            }

            .contact-container {
                padding: 40px 20px;
            }

            .contact-form-card {
                padding: 24px;
            }

            .contact-form-title {
                font-size: 24px;
            }

            .contact-info-card {
                padding: 24px;
            }
        }
    </style>
@endsection
