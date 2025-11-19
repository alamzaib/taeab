@php
    $job = $job ?? new \App\Models\Job();
@endphp
@csrf
<div class="form-group">
    <label for="title">Job Title <span class="text-danger">*</span></label>
    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
           value="{{ old('title', $job->title ?? '') }}" required>
    @error('title')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="location">Location</label>
        <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror"
               value="{{ old('location', $job->location ?? '') }}">
        @error('location')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group col-md-3">
        <label for="job_type">Job Type</label>
        <input type="text" name="job_type" id="job_type" class="form-control @error('job_type') is-invalid @enderror"
               value="{{ old('job_type', $job->job_type ?? '') }}">
        @error('job_type')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group col-md-3">
        <label for="experience_level">Experience Level</label>
        <input type="text" name="experience_level" id="experience_level"
               class="form-control @error('experience_level') is-invalid @enderror"
               value="{{ old('experience_level', $job->experience_level ?? '') }}">
        @error('experience_level')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-3">
        <label for="salary_min">Salary Min</label>
        <input type="number" step="0.01" name="salary_min" id="salary_min" class="form-control @error('salary_min') is-invalid @enderror"
               value="{{ old('salary_min', $job->salary_min ?? '') }}">
        @error('salary_min')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group col-md-3">
        <label for="salary_max">Salary Max</label>
        <input type="number" step="0.01" name="salary_max" id="salary_max" class="form-control @error('salary_max') is-invalid @enderror"
               value="{{ old('salary_max', $job->salary_max ?? '') }}">
        @error('salary_max')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="status">Status <span class="text-danger">*</span></label>
        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
            @foreach(['draft' => 'Draft', 'published' => 'Published', 'closed' => 'Closed'] as $value => $label)
                <option value="{{ $value }}" {{ old('status', $job->status ?? 'draft') === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('status')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

@php
    $isGoldPackage = $company->package && $company->package->name === 'gold';
@endphp

@if($isGoldPackage)
<div class="form-group">
    <div class="form-check">
        <input type="checkbox" name="featured" id="featured" value="1" class="form-check-input @error('featured') is-invalid @enderror"
               {{ old('featured', $job->featured ?? false) ? 'checked' : '' }}>
        <label class="form-check-label" for="featured">
            <strong>Make this job featured</strong>
            <small class="text-muted d-block">Featured jobs appear at the top of search results</small>
        </label>
        @error('featured')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>
@endif

<div class="form-group">
    <label for="short_description">Short Description</label>
    <textarea name="short_description" id="short_description" rows="3" class="form-control @error('short_description') is-invalid @enderror">{{ old('short_description', $job->short_description ?? '') }}</textarea>
    @error('short_description')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="description">Full Description</label>
    <textarea name="description" id="description" rows="5" class="form-control @error('description') is-invalid @enderror">{{ old('description', $job->description ?? '') }}</textarea>
    @error('description')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="requirements">Requirements</label>
    <textarea name="requirements" id="requirements" rows="5" class="form-control @error('requirements') is-invalid @enderror">{{ old('requirements', $job->requirements ?? '') }}</textarea>
    @error('requirements')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

