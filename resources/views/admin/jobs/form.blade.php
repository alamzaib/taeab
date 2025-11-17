@php
    $job = $job ?? new \App\Models\Job();
@endphp
@csrf
<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="title">Job Title <span class="text-danger">*</span></label>
            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $job->title ?? '') }}" required>
            @error('title')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="status">Status <span class="text-danger">*</span></label>
            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                @foreach(['draft' => 'Draft', 'published' => 'Published', 'closed' => 'Closed'] as $value => $label)
                    <option value="{{ $value }}" {{ old('status', $job->status ?? 'draft') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('status')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="company_id">Company <span class="text-danger">*</span></label>
            <select name="company_id" id="company_id" class="form-control @error('company_id') is-invalid @enderror" required>
                <option value="">Select Company</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ (string)old('company_id', $job->company_id ?? '') === (string)$company->id ? 'selected' : '' }}>
                        {{ $company->company_name }} ({{ $company->email }})
                    </option>
                @endforeach
            </select>
            @error('company_id')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="agent_id">Assign Agent</label>
            <select name="agent_id" id="agent_id" class="form-control @error('agent_id') is-invalid @enderror">
                <option value="">No Agent</option>
                @foreach($agents as $agent)
                    <option value="{{ $agent->id }}" {{ (string)old('agent_id', $job->agent_id ?? '') === (string)$agent->id ? 'selected' : '' }}>
                        {{ $agent->name }} ({{ $agent->email }})
                    </option>
                @endforeach
            </select>
            @error('agent_id')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror"
                   value="{{ old('location', $job->location ?? '') }}">
            @error('location')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="job_type">Job Type</label>
            <input type="text" name="job_type" id="job_type" class="form-control @error('job_type') is-invalid @enderror"
                   value="{{ old('job_type', $job->job_type ?? '') }}" placeholder="Full-time, Contract, etc.">
            @error('job_type')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="experience_level">Experience Level</label>
            <input type="text" name="experience_level" id="experience_level" class="form-control @error('experience_level') is-invalid @enderror"
                   value="{{ old('experience_level', $job->experience_level ?? '') }}" placeholder="Mid, Senior, etc.">
            @error('experience_level')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label for="salary_min">Salary Min</label>
            <input type="number" step="0.01" name="salary_min" id="salary_min" class="form-control @error('salary_min') is-invalid @enderror"
                   value="{{ old('salary_min', $job->salary_min ?? '') }}">
            @error('salary_min')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="salary_max">Salary Max</label>
            <input type="number" step="0.01" name="salary_max" id="salary_max" class="form-control @error('salary_max') is-invalid @enderror"
                   value="{{ old('salary_max', $job->salary_max ?? '') }}">
            @error('salary_max')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="short_description">Short Description</label>
            <textarea name="short_description" id="short_description" rows="3" class="form-control @error('short_description') is-invalid @enderror">{{ old('short_description', $job->short_description ?? '') }}</textarea>
            @error('short_description')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
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

