@php
    use Illuminate\Support\Facades\Storage;
    $documents = $seeker->documents()->latest()->get();
@endphp

<h2 class="primary-text" style="margin:0 0 24px; font-size:28px;">My Resume</h2>
<p style="color:#64748b; margin-bottom:24px;">Keep multiple resumes and cover letters ready for tailored applications.</p>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:20px; margin-bottom:30px;">
    <div class="card" style="border:1px dashed #d1d5db;">
        <h3 class="primary-text" style="margin-bottom:10px;">Upload Resume</h3>
        <form action="{{ route('seeker.documents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="resume">
            <div class="form-group">
                <label for="resume-title">Resume Title</label>
                <input id="resume-title" type="text" name="title" class="form-control @error('title') is-invalid @enderror" placeholder="e.g., Software Engineer Resume 2024" required>
                @error('title')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="resume-file">Choose PDF or Word file</label>
                <input id="resume-file" type="file" name="file" class="form-control @error('file') is-invalid @enderror" required>
                @error('file')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label><input type="checkbox" name="set_default" value="1"> Set as default resume</label>
            </div>
            <div class="form-group">
                <label><input type="checkbox" name="parse_resume" value="1" checked> Parse resume and auto-fill profile</label>
                <small style="display:block; color:#6b7280; margin-top:4px;">Automatically extract and update your Education, Job History, References, Skills, and Profile information from the resume.</small>
            </div>
            <button class="btn-primary" type="submit">Upload Resume</button>
        </form>
    </div>

    <div class="card" style="border:1px dashed #d1d5db;">
        <h3 class="primary-text" style="margin-bottom:10px;">Upload Cover Letter</h3>
        <form action="{{ route('seeker.documents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="cover_letter">
            <div class="form-group">
                <label for="cover-title">Cover Letter Title</label>
                <input id="cover-title" type="text" name="title" class="form-control @error('title') is-invalid @enderror" placeholder="e.g., Cover Letter for Tech Companies" required>
                @error('title')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
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
    <h3 class="primary-text" style="margin:0 0 15px;">Your Documents</h3>

    @if($documents->isEmpty())
        <p style="color: #6b7280;">No documents uploaded yet.</p>
    @else
        <div style="display:grid; gap:15px;">
            @foreach($documents as $document)
                <div style="border: 1px solid #e5e7eb; border-radius: 10px; padding: 15px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; background:#f9fafb;">
                    <div>
                        <strong>{{ $document->title ?? ucfirst(str_replace('_', ' ', $document->type)) }}</strong>
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
                        <a class="btn-primary btn-sm" href="{{ storage_url($document->file_path) }}" target="_blank">View</a>
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

