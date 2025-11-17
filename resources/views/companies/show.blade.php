@php
    use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')

@section('title', $company->company_name . ' - Company Profile')

@section('content')
<div class="container">
    <div class="card" style="padding:0;">
        <div style="background:{{ $company->banner_path ? 'url('.Storage::disk('public')->url($company->banner_path).') center/cover' : 'linear-gradient(135deg,#0f4c75,#1b262c)' }};color:white;padding:35px;border-radius:18px 18px 0 0; position:relative;">
            <div style="position:relative; z-index:2;">
                <p style="margin:0;opacity:.8;">Company profile</p>
                <h1 style="margin:5px 0 0;font-size:32px;">{{ $company->company_name ?? $company->name }}</h1>
                <p style="margin:8px 0 0;color:rgba(255,255,255,.85);">{{ $company->industry ?? 'Industry not specified' }}</p>
                @if($company->city || $company->country)
                    <p style="margin:4px 0 0;color:rgba(255,255,255,.75);">
                        {{ $company->city }} @if($company->city && $company->country) • @endif {{ $company->country }}
                    </p>
                @endif
            </div>
            @if($company->logo_path)
                <div style="position:absolute; bottom:-35px; right:30px; background:white; border-radius:12px; padding:10px; box-shadow:0 10px 30px rgba(15,23,42,0.15);">
                    <img src="{{ Storage::disk('public')->url($company->logo_path) }}" alt="Logo" style="height:70px; width:auto;">
                </div>
            @endif
        </div>

        <div style="padding:30px; display:grid; gap:25px;">
            <div style="display:grid; grid-template-columns: repeat(auto-fit,minmax(220px,1fr)); gap:15px;">
                <div class="card" style="background:#eef2ff;">
                    <p style="color:#4c51bf; margin-bottom:6px;">Total roles posted</p>
                    <p style="font-size:30px; font-weight:700; color:#312e81;">{{ $stats['total_jobs'] }}</p>
                </div>
                <div class="card" style="background:#ecfccb;">
                    <p style="color:#4d7c0f; margin-bottom:6px;">Active openings</p>
                    <p style="font-size:30px; font-weight:700; color:#3f6212;">{{ $stats['active_jobs'] }}</p>
                </div>
                <div class="card" style="background:#fef3c7;">
                    <p style="color:#b45309; margin-bottom:6px;">Company size</p>
                    <p style="font-size:30px; font-weight:700; color:#92400e;">{{ $company->company_size ?? 'N/A' }}</p>
                </div>
            </div>

            <div style="display:grid; grid-template-columns: repeat(auto-fit,minmax(300px,1fr)); gap:20px;">
                <div class="card" style="border:1px solid #e5e7eb;">
                    <h3 class="primary-text" style="margin-bottom:10px;">About</h3>
                    <p style="color:#4b5563; line-height:1.6;">
                        {{ $company->about ?? 'We are an employer on Job Portal UAE. Add a company description to showcase your culture and values.' }}
                    </p>
                    <ul style="list-style:none; padding-left:0; color:#4b5563; margin:0;">
                        <li><strong>Organization Type:</strong> {{ $company->organization_type ?? 'N/A' }}</li>
                        <li><strong>Website:</strong>
                            @if($company->website)
                                <a href="{{ $company->website }}" target="_blank">{{ $company->website }}</a>
                            @else
                                <span>N/A</span>
                            @endif
                        </li>
                        <li><strong>Email:</strong> {{ $company->email }}</li>
                        <li><strong>Phone:</strong> {{ $company->phone ?? 'N/A' }}</li>
                        <li><strong>Address:</strong> {{ $company->address ?? 'N/A' }}</li>
                        <li><strong>City:</strong> {{ $company->city ?? 'N/A' }}</li>
                        <li><strong>Country:</strong> {{ $company->country ?? 'N/A' }}</li>
                        <li><strong>Coordinates:</strong>
                            @if($company->latitude && $company->longitude)
                                {{ $company->latitude }}, {{ $company->longitude }}
                            @else
                                N/A
                            @endif
                        </li>
                    </ul>
                </div>

                <div class="card" style="border:1px solid #e5e7eb;">
                    <h3 class="primary-text" style="margin-bottom:10px;">Latest Openings</h3>
                    @if($activeJobs->isEmpty())
                        <p style="color:#6b7280;">No active roles at the moment.</p>
                    @else
                        <ul style="list-style:none; padding-left:0; margin:0;">
                            @foreach($activeJobs as $job)
                                <li style="margin-bottom:12px;">
                                    <a href="{{ route('jobs.show', $job->slug) }}" class="primary-text" style="font-weight:600;">
                                        {{ $job->title }}
                                    </a>
                                    <p style="margin:2px 0 0; color:#6b7280;">{{ $job->location ?? 'UAE' }} • Posted {{ optional($job->posted_at)->diffForHumans() ?? $job->created_at->diffForHumans() }}</p>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <div style="text-align:right;">
                <a href="{{ route('companies.index') }}" class="btn btn-secondary">Back to companies</a>
            </div>
        </div>
    </div>
</div>
@endsection

