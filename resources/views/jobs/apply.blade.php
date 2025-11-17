@php
    use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')

@section('title', 'Apply - ' . $job->title)

@section('content')
<div class="container">
    <div class="card">
        <a href="{{ route('jobs.show', $job->slug) }}" style="text-decoration:none; color:#6b7280;">← Back to job</a>
        <h1 class="primary-text" style="font-size: 32px; margin-bottom: 10px;">Apply for {{ $job->title }}</h1>
        <p style="color:#6b7280; margin-bottom: 30px;">{{ $job->company->company_name ?? 'Company' }} • {{ $job->location ?? 'UAE' }}</p>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('jobs.apply', $job->slug) }}">
            @csrf

            <section style="margin-bottom:30px;">
                <h2 class="primary-text" style="font-size:20px; margin-bottom:12px;">Step 1: Select Resume</h2>
                @if($resumes->isEmpty())
                    <div class="alert alert-danger">
                        You do not have any resumes uploaded. <a href="{{ route('seeker.documents.index') }}" class="primary-text">Upload a resume</a> to continue.
                    </div>
                @else
                    <div style="display:grid; gap:12px;">
                        @foreach($resumes as $resume)
                            <label style="border:1px solid #e5e7eb; border-radius:10px; padding:12px; display:flex; justify-content:space-between; align-items:center;">
                                <div>
                                    <input type="radio" name="resume_document_id" value="{{ $resume->id }}" {{ ($defaultResume && $resume->id === $defaultResume->id) ? 'checked' : '' }} required>
                                    <strong style="margin-left:8px;">{{ $resume->file_name }}</strong>
                                    <p style="margin:4px 0 0; color:#6b7280;">Uploaded {{ $resume->created_at->format('M d, Y') }}</p>
                                </div>
                                <a href="{{ Storage::disk('public')->url($resume->file_path) }}" target="_blank" class="btn btn-sm btn-secondary">View</a>
                            </label>
                        @endforeach
                    </div>
                @endif
            </section>

            <section style="margin-bottom:30px;">
                <h2 class="primary-text" style="font-size:20px; margin-bottom:12px;">Step 2: Cover Letter</h2>
                @if($coverLetters->isEmpty())
                    <p style="color:#6b7280;">No cover letter uploaded. <a href="{{ route('seeker.documents.index') }}" class="primary-text">Upload one</a> or continue without.</p>
                @else
                    <div style="display:grid; gap:12px; margin-bottom:15px;">
                        <label style="border:1px dashed #d1d5db; border-radius:10px; padding:12px;">
                            <input type="radio" name="cover_letter_document_id" value="" {{ !$defaultCover ? 'checked' : '' }}>
                            <span style="margin-left:8px;">No cover letter file</span>
                        </label>
                        @foreach($coverLetters as $cover)
                            <label style="border:1px solid #e5e7eb; border-radius:10px; padding:12px; display:flex; justify-content:space-between; align-items:center;">
                                <div>
                                    <input type="radio" name="cover_letter_document_id" value="{{ $cover->id }}" {{ ($defaultCover && $cover->id === $defaultCover->id) ? 'checked' : '' }}>
                                    <strong style="margin-left:8px;">{{ $cover->file_name }}</strong>
                                    <p style="margin:4px 0 0; color:#6b7280;">Uploaded {{ $cover->created_at->format('M d, Y') }}</p>
                                </div>
                                <a href="{{ Storage::disk('public')->url($cover->file_path) }}" target="_blank" class="btn btn-sm btn-secondary">View</a>
                            </label>
                        @endforeach
                    </div>
                @endif

                <div class="form-group">
                    <label for="cover_letter">Additional information (optional)</label>
                    <textarea name="cover_letter" id="cover_letter" rows="4" class="form-control @error('cover_letter') is-invalid @enderror" placeholder="Highlight relevant experience or availability...">{{ old('cover_letter') }}</textarea>
                    @error('cover_letter')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </section>

            <section style="border-top:1px solid #e5e7eb; padding-top:20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px;">
                <div>
                    <p style="margin:0; color:#6b7280;">Review your selections before submitting.</p>
                </div>
                <button type="submit" class="btn-primary" {{ $resumes->isEmpty() ? 'disabled style=opacity:0.6;' : '' }}>Submit Application</button>
            </section>
        </form>
    </div>
</div>
@endsection

