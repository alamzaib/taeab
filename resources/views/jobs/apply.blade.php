@php
    use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')

@section('title', 'Apply - ' . $job->title)

@section('content')
<div class="job-apply-shell container">
    <div class="apply-header card">
        <a href="{{ route('jobs.show', $job->slug) }}" class="back-link">← Back to {{ $job->title }}</a>
        <h1>Apply for {{ $job->title }}</h1>
        <p class="muted-text">{{ $job->company->company_name ?? 'Company' }} • {{ $job->location ?? 'UAE' }}</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('jobs.apply', $job->slug) }}" class="apply-form" id="jobApplicationForm">
        @csrf
        <div class="apply-grid">
            <section class="card apply-card">
                <h2>Step 1: Select resume</h2>
                @if($resumes->isEmpty())
                    <div class="alert alert-danger">
                        You don't have any resumes yet. <a href="{{ route('seeker.documents.index') }}">Upload a resume</a> to continue.
                    </div>
                @else
                    <div class="select-list">
                        @foreach($resumes as $resume)
                            <label class="select-card">
                                <div>
                                    <input type="radio" name="resume_document_id" value="{{ $resume->id }}"
                                           {{ ($defaultResume && $resume->id === $defaultResume->id) ? 'checked' : '' }} required>
                                    <strong>{{ $resume->title ?? $resume->file_name }}</strong>
                                    <p class="muted-text">Uploaded {{ $resume->created_at->format('M d, Y') }}</p>
                                </div>
                                <a href="{{ storage_url($resume->file_path) }}" target="_blank" class="btn btn-light btn-sm">Preview</a>
                            </label>
                        @endforeach
                    </div>
                @endif
            </section>

            <section class="card apply-card">
                <h2>Step 2: Cover letter</h2>
                @if($coverLetters->isEmpty())
                    <p class="muted-text">No cover letter uploaded. You can continue without one or <a href="{{ route('seeker.documents.index') }}">upload a file</a>.</p>
                @else
                    <div class="select-list">
                        <label class="select-card dotted">
                            <div>
                                <input type="radio" name="cover_letter_document_id" value="" {{ !$defaultCover ? 'checked' : '' }}>
                                <strong>Continue without a file</strong>
                                <p class="muted-text">Add notes below instead.</p>
                            </div>
                        </label>
                        @foreach($coverLetters as $cover)
                            <label class="select-card">
                                <div>
                                    <input type="radio" name="cover_letter_document_id" value="{{ $cover->id }}"
                                           {{ ($defaultCover && $cover->id === $defaultCover->id) ? 'checked' : '' }}>
                                    <strong>{{ $cover->title ?? $cover->file_name }}</strong>
                                    <p class="muted-text">Uploaded {{ $cover->created_at->format('M d, Y') }}</p>
                                </div>
                                <a href="{{ storage_url($cover->file_path) }}" target="_blank" class="btn btn-light btn-sm">Preview</a>
                            </label>
                        @endforeach
                    </div>
                @endif

                <div class="form-group" style="margin-top:16px;">
                    <label for="cover_letter">Additional notes (optional)</label>
                    <textarea name="cover_letter" id="cover_letter" rows="4"
                              class="form-control @error('cover_letter') is-invalid @enderror"
                              placeholder="Share availability, notice period, or key highlights">{{ old('cover_letter') }}</textarea>
                    @error('cover_letter')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </section>

            <aside class="card apply-summary">
                <h3>Summary</h3>
                <p class="muted-text">Review your selections before submitting.</p>
                <ul class="summary-list">
                    <li>
                        <span>Role</span>
                        <strong>{{ $job->title }}</strong>
                    </li>
                    <li>
                        <span>Company</span>
                        <strong>{{ $job->company->company_name ?? 'Company' }}</strong>
                    </li>
                    <li>
                        <span>Location</span>
                        <strong>{{ $job->location ?? 'UAE' }}</strong>
                    </li>
                </ul>
                
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <button type="submit" form="jobApplicationForm" class="btn-primary btn-lg" {{ $resumes->isEmpty() ? 'disabled style=opacity:0.6;' : '' }}>
                        Submit application
                    </button>
                    
                    <div style="text-align: center; margin: 8px 0; color: #94a3b8; font-size: 14px;">OR</div>
                    
                    <button type="button" id="linkedinApplyBtn" class="btn-linkedin btn-lg" style="width: 100%;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" style="vertical-align: middle; margin-right: 8px;">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                        Apply with LinkedIn
                    </button>
                </div>
            </aside>
        </div>
    </form>
    
    <!-- Separate form for LinkedIn application -->
    <form method="POST" action="{{ route('jobs.apply.linkedin', $job->slug) }}" id="linkedinApplyForm" style="position: absolute; left: -9999px; visibility: hidden;">
        @csrf
        <input type="hidden" name="cover_letter" id="linkedin_cover_letter" value="">
    </form>
</div>

<style>
    .job-apply-shell {
        margin-bottom: 40px;
    }
    .apply-header {
        margin-bottom: 20px;
        padding: 28px;
    }
    .apply-header h1 {
        margin: 4px 0;
    }
    .apply-steps {
        list-style: none;
        padding: 0;
        margin: 16px 0 0;
        display: flex;
        gap: 16px;
        font-size: 14px;
        color: #94a3b8;
    }
    .apply-steps li {
        display: flex;
        gap: 6px;
        align-items: center;
    }
    .apply-steps li::before {
        content: '';
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #cbd5f5;
    }
    .apply-steps li.active {
        color: #1d4ed8;
        font-weight: 600;
    }
    .apply-steps li.active::before {
        background: #1d4ed8;
    }
    .apply-grid {
        display: grid;
        grid-template-columns: minmax(0, 2fr) minmax(260px, 1fr);
        gap: 24px;
    }
    .apply-card {
        margin-bottom: 20px;
        padding: 24px;
    }
    .apply-card h2 {
        margin-top: 0;
    }
    .select-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .select-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        background: #fff;
    }
    .select-card input {
        margin-right: 10px;
    }
    .select-card.dotted {
        border-style: dashed;
    }
    .apply-summary {
        padding: 24px;
        height: fit-content;
        align-self: start;
    }
    .summary-list {
        list-style: none;
        padding: 0;
        margin: 0 0 16px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .summary-list li {
        display: flex;
        justify-content: space-between;
        color: #475569;
    }
    .summary-list span {
        font-size: 13px;
        color: #94a3b8;
    }
    .btn-linkedin {
        background-color: #0077b5;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.3s;
        text-decoration: none;
    }
    .btn-linkedin:hover:not(:disabled) {
        background-color: #005885;
        color: white;
    }
    .btn-linkedin:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    @media screen and (max-width: 1024px) {
        .apply-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const linkedinBtn = document.getElementById('linkedinApplyBtn');
    const linkedinForm = document.getElementById('linkedinApplyForm');
    const coverLetterTextarea = document.getElementById('cover_letter');
    const linkedinCoverLetterInput = document.getElementById('linkedin_cover_letter');
    
    if (linkedinBtn && linkedinForm) {
        linkedinBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Copy cover letter value to LinkedIn form if textarea exists
            if (coverLetterTextarea && linkedinCoverLetterInput) {
                linkedinCoverLetterInput.value = coverLetterTextarea.value || '';
            }
            
            // Show loading state
            const originalHtml = linkedinBtn.innerHTML;
            linkedinBtn.disabled = true;
            linkedinBtn.innerHTML = '<span>Redirecting to LinkedIn...</span>';
            
            // Submit the form - this will cause a page navigation to LinkedIn
            setTimeout(function() {
                linkedinForm.submit();
            }, 100);
        });
    } else {
        console.error('LinkedIn apply elements not found', {
            btn: !!linkedinBtn,
            form: !!linkedinForm
        });
    }
});
</script>
@endpush
@endsection

