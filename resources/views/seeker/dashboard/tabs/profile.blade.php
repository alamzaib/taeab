@php
    use Illuminate\Support\Facades\Storage;
    $commonSkills = [
        'Project Management',
        'Sales',
        'Marketing',
        'Customer Support',
        'JavaScript',
        'PHP',
        'Laravel',
        'React',
        'UI/UX',
        'Data Analysis',
        'Finance',
        'HR',
        'Operations',
        'Business Development',
        'Cloud Computing',
        'Product Management',
        'Copywriting',
        'DevOps',
        'Cybersecurity',
    ];
    $oldSkills = old('skills');
    if ($oldSkills) {
        $selectedSkills = is_array($oldSkills) ? array_filter(array_map('trim', $oldSkills)) : array_filter(array_map('trim', explode(',', $oldSkills)));
    } else {
        $selectedSkills = $seeker->skills ? array_filter(array_map('trim', explode(',', $seeker->skills))) : [];
    }
@endphp
<h2 class="primary-text" style="margin:0 0 24px; font-size:28px;">My Profile</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('seeker.profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <h3 class="primary-text" style="margin-bottom:10px;">Personal Details</h3>
    <div class="auth-dual">
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $seeker->name) }}" required>
        </div>
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" class="form-control" value="{{ $seeker->email }}" readonly style="background-color: #f5f5f5; cursor: not-allowed;">
            <small style="color: #6b7280; font-size: 12px;">Email address cannot be changed</small>
        </div>
    </div>

    <div class="auth-dual">
        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $seeker->phone) }}" required>
        </div>
        <div class="form-group">
            <label for="whatsapp_number">WhatsApp Number</label>
            <input type="text" id="whatsapp_number" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number', $seeker->whatsapp_number) }}">
        </div>
    </div>

    <div class="auth-dual">
        <div class="form-group">
            <label for="residence_country">Current Residence Country</label>
            <input type="text" id="residence_country" name="residence_country" class="form-control" value="{{ old('residence_country', $seeker->residence_country) }}">
        </div>
        <div class="form-group">
            <label for="nationality">Nationality</label>
            <select id="nationality" name="nationality" class="form-control">
                <option value="">Select Nationality</option>
                @php
                    $countries = [
                        'Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Antigua and Barbuda', 'Argentina', 'Armenia', 'Australia', 'Austria',
                        'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bhutan',
                        'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Brazil', 'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cabo Verde', 'Cambodia',
                        'Cameroon', 'Canada', 'Central African Republic', 'Chad', 'Chile', 'China', 'Colombia', 'Comoros', 'Congo', 'Costa Rica',
                        'Croatia', 'Cuba', 'Cyprus', 'Czech Republic', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'Ecuador', 'Egypt',
                        'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Eswatini', 'Ethiopia', 'Fiji', 'Finland', 'France', 'Gabon',
                        'Gambia', 'Georgia', 'Germany', 'Ghana', 'Greece', 'Grenada', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana',
                        'Haiti', 'Honduras', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq', 'Ireland', 'Israel',
                        'Italy', 'Jamaica', 'Japan', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Korea, North', 'Korea, South', 'Kuwait',
                        'Kyrgyzstan', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg',
                        'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Mauritania', 'Mauritius', 'Mexico',
                        'Micronesia', 'Moldova', 'Monaco', 'Mongolia', 'Montenegro', 'Morocco', 'Mozambique', 'Myanmar', 'Namibia', 'Nauru',
                        'Nepal', 'Netherlands', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'North Macedonia', 'Norway', 'Oman', 'Pakistan',
                        'Palau', 'Palestine', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Poland', 'Portugal', 'Qatar',
                        'Romania', 'Russia', 'Rwanda', 'Saint Kitts and Nevis', 'Saint Lucia', 'Saint Vincent and the Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia',
                        'Senegal', 'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa',
                        'South Sudan', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Sweden', 'Switzerland', 'Syria', 'Taiwan', 'Tajikistan',
                        'Tanzania', 'Thailand', 'Timor-Leste', 'Togo', 'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Tuvalu',
                        'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatican City', 'Venezuela',
                        'Vietnam', 'Yemen', 'Zambia', 'Zimbabwe'
                    ];
                    $selectedNationality = old('nationality', $seeker->nationality);
                @endphp
                @foreach($countries as $country)
                    <option value="{{ $country }}" {{ $selectedNationality === $country ? 'selected' : '' }}>{{ $country }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="auth-dual">
        <div class="form-group">
            <label for="date_of_birth">Date of Birth</label>
            <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $seeker->date_of_birth) }}">
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" id="address" name="address" class="form-control" value="{{ old('address', $seeker->address) }}">
        </div>
    </div>

    <div class="form-group">
        <label for="linkedin_url">LinkedIn Profile</label>
        <input type="url" id="linkedin_url" name="linkedin_url" class="form-control" value="{{ old('linkedin_url', $seeker->linkedin_url) }}">
    </div>

    <h3 class="primary-text" style="margin:20px 0 10px;">Professional Summary</h3>
    <div class="form-group">
        <label for="about">About Me</label>
        <textarea id="about" name="about" rows="4" class="form-control">{{ old('about', $seeker->about) }}</textarea>
    </div>

    <div class="auth-dual">
        <div class="form-group">
            <label for="skills-input">Skills</label>
            <div class="skill-input-wrapper">
                <div id="skills-tags">
                    @foreach ($selectedSkills as $skill)
                        <span class="skill-tag" data-value="{{ $skill }}">{{ $skill }} <button
                                type="button" class="skill-remove">&times;</button></span>
                    @endforeach
                </div>
                <input type="text" id="skills-input" class="skill-input" placeholder="Type a skill...">
            </div>
            <div id="skills-dropdown" class="skills-dropdown"></div>
            <input type="hidden" name="skills" id="skills-hidden"
                value="{{ implode(',', $selectedSkills) }}">
            <small style="color:#6b7280;">Type to add skills. Use Enter to confirm.</small>
        </div>
        <div class="form-group">
            <label for="current_company">Current Company</label>
            <input type="text" id="current_company" name="current_company" class="form-control" value="{{ old('current_company', $seeker->current_company) }}">
        </div>
    </div>

    <div class="auth-dual">
        <div class="form-group">
            <label for="current_salary">Current Salary</label>
            <input type="text" id="current_salary" name="current_salary" class="form-control" value="{{ old('current_salary', $seeker->current_salary) }}">
        </div>
        <div class="form-group">
            <label for="target_salary">Target Salary</label>
            <input type="text" id="target_salary" name="target_salary" class="form-control" value="{{ old('target_salary', $seeker->target_salary) }}">
        </div>
    </div>

    <h3 class="primary-text" style="margin:20px 0 10px;">Media</h3>
    <div class="auth-dual">
        <div class="form-group">
            <label for="profile_photo">Profile Photo</label>
            <input type="file" id="profile_photo" name="profile_photo" class="form-control">
            @if($seeker->profile_photo_path)
                <small><a href="{{ Storage::disk('public')->url($seeker->profile_photo_path) }}" target="_blank">View current photo</a></small>
            @endif
        </div>
        <div class="form-group">
            <label for="profile_cover">Cover Image</label>
            <input type="file" id="profile_cover" name="profile_cover" class="form-control">
            @if($seeker->profile_cover_path)
                <small><a href="{{ Storage::disk('public')->url($seeker->profile_cover_path) }}" target="_blank">View current cover</a></small>
            @endif
        </div>
    </div>

    <h3 class="primary-text" style="margin:20px 0 10px;">Account Security</h3>
    <div class="auth-dual">
        <div class="form-group">
            <label for="password">New Password (optional)</label>
            <input type="password" id="password" name="password" class="form-control">
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
        </div>
    </div>

    <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
        <button type="submit" class="btn-primary">Save Changes</button>
    </div>
</form>

@push('styles')
    <style>
        .skill-input-wrapper {
            border: 1px solid #cbd5f5;
            border-radius: 10px;
            padding: 10px;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            min-height: 48px;
        }

        .skill-input-wrapper:focus-within {
            box-shadow: 0 0 0 3px rgba(35, 81, 129, .2);
        }

        .skill-tag {
            background: #eef2ff;
            color: #1e3a8a;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .skill-tag button {
            border: none;
            background: none;
            color: inherit;
            cursor: pointer;
            font-size: 14px;
            padding: 0;
        }

        .skill-input {
            border: none;
            outline: none;
            min-width: 180px;
            flex: 1;
        }

        .skills-dropdown {
            position: relative;
            margin-top: 4px;
        }

        .skills-dropdown ul {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            box-shadow: 0 15px 30px rgba(15, 23, 42, .1);
            list-style: none;
            padding: 6px 0;
            margin: 0;
            max-height: 220px;
            overflow-y: auto;
        }

        .skills-dropdown li {
            padding: 8px 16px;
            cursor: pointer;
        }

        .skills-dropdown li:hover {
            background: #eef2ff;
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function() {
            const availableSkills = @json($commonSkills);
            const tagsContainer = document.getElementById('skills-tags');
            const input = document.getElementById('skills-input');
            const dropdown = document.getElementById('skills-dropdown');
            const hiddenInput = document.getElementById('skills-hidden');

            let selected = hiddenInput.value ? hiddenInput.value.split(',').map(s => s.trim()).filter(Boolean) : [];

            function renderTags() {
                tagsContainer.innerHTML = '';
                selected.forEach(skill => {
                    const tag = document.createElement('span');
                    tag.className = 'skill-tag';
                    tag.dataset.value = skill;
                    tag.innerHTML = `${skill} <button type="button" class="skill-remove">&times;</button>`;
                    tagsContainer.appendChild(tag);
                });
                hiddenInput.value = selected.join(',');
            }

            function showDropdown(options) {
                if (!options.length) {
                    dropdown.style.display = 'none';
                    dropdown.innerHTML = '';
                    return;
                }

                dropdown.style.display = 'block';
                dropdown.innerHTML = `<ul>${options.map(opt => `<li data-value="${opt}">${opt}</li>`).join('')}</ul>`;
            }

            function addSkill(skill) {
                const trimmed = skill.trim();
                if (!trimmed || selected.includes(trimmed)) return;
                selected.push(trimmed);
                renderTags();
            }

            input.addEventListener('input', () => {
                const value = input.value.toLowerCase();
                const matches = availableSkills.filter(skill => skill.toLowerCase().includes(value) && !selected
                    .includes(skill));
                showDropdown(matches);
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && input.value.trim()) {
                    e.preventDefault();
                    addSkill(input.value);
                    input.value = '';
                    dropdown.style.display = 'none';
                }
            });

            dropdown.addEventListener('click', (e) => {
                const value = e.target.getAttribute('data-value');
                if (value) {
                    addSkill(value);
                    input.value = '';
                    dropdown.style.display = 'none';
                }
            });

            tagsContainer.addEventListener('click', (e) => {
                if (e.target.classList.contains('skill-remove')) {
                    const value = e.target.parentElement.dataset.value;
                    selected = selected.filter(skill => skill !== value);
                    renderTags();
                }
            });

            document.addEventListener('click', (e) => {
                if (!dropdown.contains(e.target) && e.target !== input) {
                    dropdown.style.display = 'none';
                }
            });
        })();
    </script>
@endpush

