@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;
    $isSeeker = auth('seeker')->check();
@endphp

@extends('layouts.app')

@section('title', $job->title . ' - Job Details')
@section('meta_description', Str::limit(strip_tags($job->short_description ?? $job->description ?? ''), 150))

@section('content')
<div class="container">
    <div class="card">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div style="margin-bottom: 20px; display:flex; justify-content:space-between; align-items:center; gap:10px;">
            <a href="{{ route('jobs.index') }}" style="text-decoration: none; color: #6b7280;">← Back to all jobs</a>
            @if($isSeeker)
                <form action="{{ route('jobs.favorite', $job->slug) }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn btn-secondary" style="display:flex; align-items:center; gap:6px;">
                        {{ $isFavorited ? '★ Favorited' : '☆ Save Job' }}
                    </button>
                </form>
            @else
                <button id="favorite-btn" class="btn btn-secondary" style="display:flex; align-items:center; gap:6px;">☆ Save Job</button>
            @endif
        </div>

        <h1 class="primary-text" style="font-size: 34px; margin-bottom: 10px;">{{ $job->title }}</h1>
        <div style="color: #6b7280; margin-bottom: 20px;">
            {{ $job->company->company_name ?? 'Company' }} • {{ $job->location ?? 'UAE' }}
        </div>

        <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 25px;">
            @if($job->job_type)
                <div style="flex: 1; min-width: 180px;">
                    <div style="font-size: 12px; color: #9ca3af; text-transform: uppercase;">Job Type</div>
                    <div style="font-weight: 600;">{{ $job->job_type }}</div>
                </div>
            @endif
            @if($job->experience_level)
                <div style="flex: 1; min-width: 180px;">
                    <div style="font-size: 12px; color: #9ca3af; text-transform: uppercase;">Experience</div>
                    <div style="font-weight: 600;">{{ $job->experience_level }}</div>
                </div>
            @endif
            <div style="flex: 1; min-width: 180px;">
                <div style="font-size: 12px; color: #9ca3af; text-transform: uppercase;">Salary Range</div>
                <div style="font-weight: 600;">
                    @if($job->salary_min || $job->salary_max)
                        @if($job->salary_min)
                            AED {{ number_format($job->salary_min) }}
                        @endif
                        @if($job->salary_min && $job->salary_max) - @endif
                        @if($job->salary_max)
                            AED {{ number_format($job->salary_max) }}
                        @endif
                    @else
                        Not specified
                    @endif
                </div>
            </div>
            <div style="flex: 1; min-width: 180px;">
                <div style="font-size: 12px; color: #9ca3af; text-transform: uppercase;">Posted</div>
                <div style="font-weight: 600;">{{ optional($job->posted_at)->format('M d, Y') ?? $job->created_at->format('M d, Y') }}</div>
            </div>
        </div>

        @if($job->short_description)
            <div class="page-content" style="margin-bottom: 25px;">
                {!! nl2br(e($job->short_description)) !!}
            </div>
        @endif

        @if($job->description)
            <h2 class="primary-text" style="font-size: 24px; margin-bottom: 10px;">Job Description</h2>
            <div class="page-content" style="margin-bottom: 25px;">
                {!! $job->description !!}
            </div>
        @endif

        @if($job->requirements)
            <h2 class="primary-text" style="font-size: 24px; margin-bottom: 10px;">Requirements</h2>
            <div class="page-content" style="margin-bottom: 25px;">
                {!! $job->requirements !!}
            </div>
        @endif

        @if(!($isSeeker && $hasApplied))
            <div style="margin-top: 40px; border-top: 1px solid #e5e7eb; padding-top: 25px;">
                <h2 class="primary-text" style="font-size: 24px; margin-bottom: 15px;">Apply for this job</h2>

                @if($isSeeker)
                    <div class="card" style="margin-bottom: 20px; background-color: #f8fafc;">
                        <h3 class="primary-text" style="margin-bottom: 10px;">Your default documents</h3>
                        <ul style="list-style: none; padding-left: 0; margin-bottom: 0;">
                            <li style="margin-bottom: 8px;">
                                <strong>Resume:</strong>
                                @if($defaultResume)
                                    <a href="{{ Storage::disk('public')->url($defaultResume->file_path) }}" target="_blank">
                                        {{ $defaultResume->file_name }}
                                    </a>
                                @else
                                    <span style="color: #dc2626;">Not set. <a class="primary-text" href="{{ route('seeker.documents.index') }}">Upload a resume</a>.</span>
                                @endif
                            </li>
                            <li>
                                <strong>Cover Letter:</strong>
                                @if($defaultCover)
                                    <a href="{{ Storage::disk('public')->url($defaultCover->file_path) }}" target="_blank">
                                        {{ $defaultCover->file_name }}
                                    </a>
                                @else
                                    <span style="color: #6b7280;">Optional. <a class="primary-text" href="{{ route('seeker.documents.index') }}">Upload a cover letter</a>.</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('jobs.apply.form', $job->slug) }}" class="btn-primary">Proceed to Application</a>
                @else
                    <button id="apply-btn" class="btn-primary">Apply Now</button>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const applyBtn = document.getElementById('apply-btn');
    const favoriteBtn = document.getElementById('favorite-btn');

    const promptLogin = () => {
        Swal.fire({
            title: 'Login required',
            text: 'Please log in as a job seeker to continue.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Login',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('seeker.login') }}?redirect={{ urlencode(request()->fullUrl()) }}";
            }
        });
    };

    if (applyBtn) {
        applyBtn.addEventListener('click', promptLogin);
    }

    if (favoriteBtn) {
        favoriteBtn.addEventListener('click', promptLogin);
    }
});
</script>
@endpush

