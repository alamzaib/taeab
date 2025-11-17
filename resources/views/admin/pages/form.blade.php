@php
    $page = $page ?? new \App\Models\Page();
@endphp
@csrf
<div class="form-group">
    <label for="title">Page Title <span class="text-danger">*</span></label>
    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
        value="{{ old('title', $page->title) }}" required>
    @error('title')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="slug">Slug (optional)</label>
        <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror"
            value="{{ old('slug', $page->slug) }}" placeholder="about-us">
        @error('slug')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="status">Status</label>
        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
            @foreach (['draft' => 'Draft', 'published' => 'Published'] as $value => $label)
                <option value="{{ $value }}"
                    {{ old('status', $page->status ?? 'draft') === $value ? 'selected' : '' }}>{{ $label }}
                </option>
            @endforeach
        </select>
        @error('status')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="form-group">
    <label for="meta_title">Meta Title</label>
    <input type="text" name="meta_title" id="meta_title"
        class="form-control @error('meta_title') is-invalid @enderror"
        value="{{ old('meta_title', $page->meta_title) }}">
    @error('meta_title')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="meta_description">Meta Description</label>
    <textarea name="meta_description" id="meta_description" rows="2"
        class="form-control @error('meta_description') is-invalid @enderror">{{ old('meta_description', $page->meta_description) }}</textarea>
    @error('meta_description')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="content">Page Content</label>
    <textarea name="content" id="content" rows="12" class="form-control @error('content') is-invalid @enderror">{{ old('content', $page->content) }}</textarea>
    <small class="form-text text-muted">You can paste full HTML here (headings, paragraphs, links, etc.).</small>
    @error('content')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>
