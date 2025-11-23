@php
    $isEdit = $post->exists;
@endphp

@csrf

<div class="card-body">
<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="title">Title *</label>
            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                value="{{ old('title', $post->title) }}" required>
            @error('title')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="slug">Slug</label>
            <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror"
                value="{{ old('slug', $post->slug) }}" placeholder="auto-generated if empty">
            @error('slug')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="excerpt">Excerpt</label>
            <textarea name="excerpt" id="excerpt" rows="3"
                class="form-control @error('excerpt') is-invalid @enderror">{{ old('excerpt', $post->excerpt) }}</textarea>
            @error('excerpt')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="content">Content *</label>
            <textarea name="content" id="content" rows="12"
                class="form-control @error('content') is-invalid @enderror" required>{{ old('content', $post->content) }}</textarea>
            @error('content')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="status">Status *</label>
            @php
                $currentStatus = old('status', $post->status ?? 'published');
            @endphp
            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                <option value="draft" {{ $currentStatus === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ $currentStatus === 'published' ? 'selected' : '' }}>Published</option>
            </select>
            @error('status')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="published_at">Publish Date</label>
            <input type="datetime-local" name="published_at" id="published_at"
                class="form-control @error('published_at') is-invalid @enderror"
                value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}">
            @error('published_at')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="featured_image">Featured Image</label>
            <input type="file" name="featured_image" id="featured_image"
                class="form-control-file @error('featured_image') is-invalid @enderror">
            @error('featured_image')
                <span class="text-danger d-block">{{ $message }}</span>
            @enderror

            @if($post->featured_image)
                <div class="mt-2">
                    <img src="{{ Storage::url($post->featured_image) }}" alt="" class="img-fluid rounded mb-2" style="max-height: 150px;">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remove_featured_image" value="1" id="remove_featured_image">
                        <label class="form-check-label" for="remove_featured_image">Remove current image</label>
                    </div>
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="meta_title">Meta Title</label>
            <input type="text" name="meta_title" id="meta_title" class="form-control @error('meta_title') is-invalid @enderror"
                value="{{ old('meta_title', $post->meta_title) }}">
            @error('meta_title')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="meta_description">Meta Description</label>
            <textarea name="meta_description" id="meta_description" rows="3"
                class="form-control @error('meta_description') is-invalid @enderror">{{ old('meta_description', $post->meta_description) }}</textarea>
            @error('meta_description')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>
</div>

<div class="card-footer">
    <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update Post' : 'Create Post' }}</button>
    <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-secondary">Cancel</a>
</div>

