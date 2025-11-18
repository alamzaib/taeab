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

    <form method="POST" action="{{ route('jobs.apply', $job->slug) }}" class="apply-form">
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
                                <a href="{{ Storage::disk('public')->url($resume->file_path) }}" target="_blank" class="btn btn-light btn-sm">Preview</a>
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
                                <a href="{{ Storage::disk('public')->url($cover->file_path) }}" target="_blank" class="btn btn-light btn-sm">Preview</a>
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
                <button type="submit" class="btn-primary btn-lg" {{ $resumes->isEmpty() ? 'disabled style=opacity:0.6;' : '' }}>
                    Submit application
                </button>
            </aside>
        </div>
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
    @media screen and (max-width: 1024px) {
        .apply-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

