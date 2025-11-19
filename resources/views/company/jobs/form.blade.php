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
    <div class="form-group col-md-3">
        <label for="country">Country</label>
        <select name="country" id="country" class="form-control @error('country') is-invalid @enderror">
            <option value="">Select Country</option>
            @php
                $selectedCountry = old('country', '');
                if (!$selectedCountry && $job->location) {
                    $locationParts = explode(',', trim($job->location));
                    if (count($locationParts) >= 2) {
                        $selectedCountry = trim($locationParts[1]);
                    }
                }
            @endphp
            @foreach(\App\Models\Country::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get() as $country)
                <option value="{{ $country->id }}" data-name="{{ $country->name }}" {{ $selectedCountry == $country->id || $selectedCountry === $country->name ? 'selected' : '' }}>
                    {{ $country->name }}
                </option>
            @endforeach
        </select>
        @error('country')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group col-md-3">
        <label for="city">City</label>
        <select name="city" id="city" class="form-control @error('city') is-invalid @enderror">
            <option value="">Select City</option>
            @php
                $selectedCity = old('city', '');
                if (!$selectedCity && $job->location) {
                    $locationParts = explode(',', trim($job->location));
                    if (count($locationParts) >= 1) {
                        $selectedCity = trim($locationParts[0]);
                    }
                }
                $selectedCountryId = old('country', '');
                if (!$selectedCountryId && $job->location) {
                    $locationParts = explode(',', trim($job->location));
                    if (count($locationParts) >= 2) {
                        $countryName = trim($locationParts[1]);
                        $countryModel = \App\Models\Country::where('name', $countryName)->first();
                        if ($countryModel) {
                            $selectedCountryId = $countryModel->id;
                        }
                    }
                }
                if ($selectedCountryId) {
                    $cities = \App\Models\City::where('country_id', $selectedCountryId)->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
                }
            @endphp
            @if(isset($cities))
                @foreach($cities as $city)
                    <option value="{{ $city->id }}" data-name="{{ $city->name }}" {{ $selectedCity == $city->id || $selectedCity === $city->name ? 'selected' : '' }}>
                        {{ $city->name }}
                    </option>
                @endforeach
            @endif
        </select>
        @error('city')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group col-md-3">
        <label for="job_type">Job Type</label>
        <select name="job_type" id="job_type" class="form-control @error('job_type') is-invalid @enderror">
            <option value="">Select Job Type</option>
            @foreach(\App\Models\JobType::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get() as $jobType)
                <option value="{{ $jobType->name }}" {{ old('job_type', $job->job_type ?? '') === $jobType->name ? 'selected' : '' }}>
                    {{ $jobType->name }}
                </option>
            @endforeach
        </select>
        @error('job_type')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group col-md-3">
        <label for="experience_level">Experience Level</label>
        <select name="experience_level" id="experience_level" class="form-control @error('experience_level') is-invalid @enderror">
            <option value="">Select Experience Level</option>
            @foreach(\App\Models\ExperienceLevel::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get() as $experienceLevel)
                <option value="{{ $experienceLevel->name }}" {{ old('experience_level', $job->experience_level ?? '') === $experienceLevel->name ? 'selected' : '' }}>
                    {{ $experienceLevel->name }}
                </option>
            @endforeach
        </select>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const countrySelect = document.getElementById('country');
    const citySelect = document.getElementById('city');
    
    if (countrySelect && citySelect) {
        countrySelect.addEventListener('change', function() {
            const countryId = this.value;
            citySelect.innerHTML = '<option value="">Select City</option>';
            
            if (countryId) {
                fetch(`/company/api/cities?country_id=${countryId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.id;
                            option.setAttribute('data-name', city.name);
                            option.textContent = city.name;
                            citySelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
        
        // Trigger change on load if country is selected
        if (countrySelect.value) {
            setTimeout(() => {
                countrySelect.dispatchEvent(new Event('change'));
            }, 100);
        }
    }
});
</script>
@endpush

