@extends('layouts.app')

@section('title', 'Job Seeker Dashboard - Job Portal UAE')

@section('content')
<div class="container">
    <div class="card" style="padding: 0;">
        <div style="position:relative; background:{{ $seeker->profile_cover_style }}; color: white; padding: 30px; border-radius: 18px 18px 0 0;">
            <div style="position:relative; z-index:2;">
                <p style="margin:0; font-size: 16px;opacity:.85;">Welcome back,</p>
                <h1 style="margin:5px 0 0; font-size: 32px;">{{ $seeker->name }}</h1>
                <p style="margin:8px 0 0; color: rgba(255,255,255,.85);">Track your job hunt progress and keep your profile up to date.</p>
            </div>
            <div style="position:absolute; bottom:-40px; right:30px; background:white; border-radius:50%; padding:6px; box-shadow:0 15px 40px rgba(15,23,42,0.2);">
                <img src="{{ $seeker->profile_photo_url }}" alt="Profile photo" style="height:80px; width:80px; border-radius:50%; object-fit:cover;">
            </div>
        </div>

        <div style="padding: 30px; display: grid; gap: 20px;">
            <div style="display:grid; grid-template-columns: repeat(auto-fit,minmax(200px,1fr)); gap:15px;">
                <div class="card" style="background:#f3f6fb;">
                    <p style="color:#6b7280; margin-bottom:6px;">Applications sent</p>
                    <p style="font-size:32px; font-weight:700; color:#1f2937;">{{ $stats['applications'] }}</p>
                </div>
                <div class="card" style="background:#fef6ec;">
                    <p style="color:#6b7280; margin-bottom:6px;">Saved jobs</p>
                    <p style="font-size:32px; font-weight:700; color:#b45309;">{{ $stats['favorites'] }}</p>
                </div>
                <div class="card" style="background:#eefcf7;">
                    <p style="color:#6b7280; margin-bottom:6px;">Profile completeness</p>
                    <p style="font-size:32px; font-weight:700; color:#047857;">80%</p>
                </div>
            </div>

            <div style="display:grid; grid-template-columns: repeat(auto-fit,minmax(220px,1fr)); gap:15px;">
                <div class="card" style="border:1px dashed #d1d5db;">
                    <h3 class="primary-text" style="margin-bottom:8px;">Browse openings</h3>
                    <p style="color:#6b7280;">Search thousands of curated roles across the UAE.</p>
                    <a href="{{ route('jobs.index') }}" class="btn-primary" style="margin-top:10px;">Find jobs</a>
                </div>
                <div class="card" style="border:1px dashed #d1d5db;">
                    <h3 class="primary-text" style="margin-bottom:8px;">Documents</h3>
                    <p style="color:#6b7280;">Upload multiple CVs and cover letters for tailored applications.</p>
                    <a href="{{ route('seeker.documents.index') }}" class="btn-primary" style="margin-top:10px;">Manage files</a>
                </div>
                <div class="card" style="border:1px dashed #d1d5db;">
                    <h3 class="primary-text" style="margin-bottom:8px;">Profile</h3>
                    <p style="color:#6b7280;">Keep your contact information current to stay reachable.</p>
                    <a href="{{ route('seeker.profile.edit') }}" class="btn-primary" style="margin-top:10px;">Edit profile</a>
                </div>
            </div>

            <div style="border:1px solid #e5e7eb; border-radius:12px; padding:20px;">
                <h3 class="primary-text" style="margin-bottom:12px;">Saved jobs</h3>
                @if($favorites->isEmpty())
                    <p style="color:#6b7280;">You haven't saved any jobs yet.</p>
                @else
                    <div style="display:grid; gap:12px;">
                        @foreach($favorites as $favorite)
                            <div style="display:flex; justify-content:space-between; flex-wrap:wrap; gap:10px;">
                                <div>
                                    <a href="{{ route('jobs.show', $favorite->job->slug) }}" class="primary-text" style="font-weight:600;">
                                        {{ $favorite->job->title }}
                                    </a>
                                    <p style="margin:4px 0 0; color:#6b7280;">
                                        {{ $favorite->job->company->company_name ?? 'Company' }} • {{ $favorite->job->location ?? 'UAE' }}
                                    </p>
                                </div>
                                <form action="{{ route('jobs.favorite', $favorite->job) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-secondary btn-sm" type="submit">Remove</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

