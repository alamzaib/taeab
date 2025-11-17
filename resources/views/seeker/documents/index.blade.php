@php
    use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')

@section('title', 'My Documents')

@section('content')
<div class="container">
    <div class="card" style="padding:0;">
        <div style="background:linear-gradient(135deg,#235181,#1a3d63);color:white;padding:35px;border-radius:18px 18px 0 0;">
            <p style="margin:0;opacity:.8;">Document hub</p>
            <h1 style="margin:5px 0 0;font-size:32px;">My documents</h1>
            <p style="margin:8px 0 0;color:rgba(255,255,255,.85);">Keep multiple resumes and cover letters ready for tailored applications.</p>
        </div>

        <div style="padding:30px;display:grid;gap:25px;">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:20px;">
                <div class="card" style="border:1px dashed #d1d5db;">
                    <h2 class="primary-text" style="margin-bottom:10px;">Upload Resume</h2>
                    <form action="{{ route('seeker.documents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="resume">
                        <div class="form-group">
                            <label for="resume-file">Choose PDF or Word file</label>
                            <input id="resume-file" type="file" name="file" class="form-control @error('file') is-invalid @enderror" required>
                            @error('file')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label><input type="checkbox" name="set_default" value="1"> Set as default resume</label>
                        </div>
                        <button class="btn-primary" type="submit">Upload Resume</button>
                    </form>
                </div>

                <div class="card" style="border:1px dashed #d1d5db;">
                    <h2 class="primary-text" style="margin-bottom:10px;">Upload Cover Letter</h2>
                    <form action="{{ route('seeker.documents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="cover_letter">
                        <div class="form-group">
                            <label for="cover-file">Choose PDF or Word file</label>
                            <input id="cover-file" type="file" name="file" class="form-control @error('file') is-invalid @enderror" required>
                            @error('file')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label><input type="checkbox" name="set_default" value="1"> Set as default cover letter</label>
                        </div>
                        <button class="btn-primary" type="submit">Upload Cover Letter</button>
                    </form>
                </div>
            </div>

            <div>
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                    <h2 class="primary-text" style="margin:0;">Your Documents</h2>
                    <a href="{{ route('seeker.dashboard') }}" class="btn btn-secondary">← Back to dashboard</a>
                </div>

                @if($documents->isEmpty())
                    <p style="color: #6b7280;">No documents uploaded yet.</p>
                @else
                    <div style="display:grid; gap:15px;">
                        @foreach($documents as $document)
                            <div style="border: 1px solid #e5e7eb; border-radius: 10px; padding: 15px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; background:#f9fafb;">
                                <div>
                                    <strong>{{ ucfirst(str_replace('_', ' ', $document->type)) }}</strong>
                                    <p style="margin: 5px 0 0; color: #6b7280;">
                                        {{ $document->file_name }}
                                        @if($document->file_size)
                                            • {{ number_format($document->file_size / 1024, 1) }} KB
                                        @endif
                                        <br>
                                        Uploaded {{ $document->created_at->format('M d, Y') }}
                                    </p>
                                </div>
                                <div style="display:flex; gap:8px; align-items:center;">
                                    @if($document->is_default)
                                        <span class="badge badge-success">Default</span>
                                    @else
                                        <form action="{{ route('seeker.documents.make-default', $document) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-secondary btn-sm" type="submit">Set Default</button>
                                        </form>
                                    @endif
                                    <a class="btn-primary btn-sm" href="{{ Storage::disk('public')->url($document->file_path) }}" target="_blank">View</a>
                                    <form action="{{ route('seeker.documents.destroy', $document) }}" method="POST" onsubmit="return confirm('Delete this document?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

