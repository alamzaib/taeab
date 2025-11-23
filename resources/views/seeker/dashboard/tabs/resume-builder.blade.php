@php
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
    $selectedSkills = $seeker->skills ? array_filter(array_map('trim', explode(',', $seeker->skills))) : [];
@endphp

<h2 class="primary-text" style="margin:0 0 24px; font-size:28px;">Build Resume</h2>
<p style="color:#64748b; margin-bottom:24px;">Manage the content recruiters will see when they open your public talent
    profile.</p>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card" style="border:1px solid #e2e8f0; margin-bottom:24px;">
    <div class="card-body">
        <div style="display:flex; flex-wrap:wrap; justify-content:space-between; gap:16px; align-items:center;">
            <div>
                <p style="margin:0; text-transform:uppercase; font-size:11px; letter-spacing:.1em; color:#94a3b8;">
                    Profile ID</p>
                <strong style="font-size:20px;">{{ $seeker->unique_code }}</strong>
            </div>
            <div>
                <a href="{{ route('seeker.resume.preview') }}" target="_blank"
                    class="btn-primary">Resume Preview</a>
            </div>
        </div>
    </div>
</div>

<div class="card" style="border:1px solid #e2e8f0; margin-bottom:24px;">
    <div class="card-body">
        <h3 class="primary-text" style="margin-bottom:16px;">Resume profile</h3>
        <form action="{{ route('seeker.resume.profile') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="resume_bio">Resume bio</label>
                <textarea name="resume_bio" id="resume_bio" rows="4" class="form-control">{{ old('resume_bio', $seeker->resume_bio) }}</textarea>
            </div>

            <div class="auth-dual">
                <div class="form-group">
                    <label for="resume_portfolio_url">Portfolio link</label>
                    <input type="url" id="resume_portfolio_url" name="resume_portfolio_url" class="form-control"
                        value="{{ old('resume_portfolio_url', $seeker->resume_portfolio_url) }}">
                </div>
                <div class="form-group">
                    <label for="linkedin_url">LinkedIn URL</label>
                    <input type="url" id="linkedin_url" name="linkedin_url" class="form-control"
                        value="{{ old('linkedin_url', $seeker->linkedin_url) }}">
                </div>
            </div>

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
                <input type="hidden" name="skills_list" id="skills-hidden"
                    value="{{ implode(',', $selectedSkills) }}">
                <small style="color:#6b7280;">Type to add skills. Use Enter to confirm.</small>
            </div>

            <div style="text-align:right;">
                <button type="submit" class="btn-primary">Save profile</button>
            </div>
        </form>
    </div>
</div>

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

        .edit-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background-color: #235181;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
        }

        .edit-btn:hover {
            background-color: #1a3d63;
            color: white;
        }

        .edit-btn svg {
            flex-shrink: 0;
            stroke: currentColor;
        }

        .edit-btn-incomplete {
            background-color: #dc3545 !important;
            color: white !important;
            border: none !important;
        }

        .edit-btn-incomplete:hover {
            background-color: #c82333 !important;
            color: white !important;
        }

        .edit-btn-incomplete svg {
            stroke: currentColor;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
            font-size: 14px;
        }

        .btn-secondary:hover {
            background-color: #545b62;
            color: white;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
            font-size: 14px;
        }

        .btn-danger:hover {
            background-color: #c82333;
            color: white;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
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

        // Edit functionality for Education, Experience, and References
        document.addEventListener('DOMContentLoaded', function() {
            // Education edit
            document.querySelectorAll('.edit-education-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const item = document.querySelector(`.education-item[data-id="${id}"]`);
                    item.querySelector('.education-view').style.display = 'none';
                    item.querySelector('.education-edit').style.display = 'block';
                });
            });

            document.querySelectorAll('.cancel-edit-education-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const item = document.querySelector(`.education-item[data-id="${id}"]`);
                    item.querySelector('.education-view').style.display = 'block';
                    item.querySelector('.education-edit').style.display = 'none';
                });
            });

            // Experience edit
            document.querySelectorAll('.edit-experience-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const item = document.querySelector(`.experience-item[data-id="${id}"]`);
                    item.querySelector('.experience-view').style.display = 'none';
                    item.querySelector('.experience-edit').style.display = 'block';
                });
            });

            document.querySelectorAll('.cancel-edit-experience-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const item = document.querySelector(`.experience-item[data-id="${id}"]`);
                    item.querySelector('.experience-view').style.display = 'block';
                    item.querySelector('.experience-edit').style.display = 'none';
                });
            });

            // Reference edit
            document.querySelectorAll('.edit-reference-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const item = document.querySelector(`.reference-item[data-id="${id}"]`);
                    item.querySelector('.reference-view').style.display = 'none';
                    item.querySelector('.reference-edit').style.display = 'block';
                });
            });

            document.querySelectorAll('.cancel-edit-reference-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const item = document.querySelector(`.reference-item[data-id="${id}"]`);
                    item.querySelector('.reference-view').style.display = 'block';
                    item.querySelector('.reference-edit').style.display = 'none';
                });
            });
        });
    </script>
@endpush
<div class="card" style="border:1px solid #e2e8f0; margin-bottom:24px;">
    <div class="card-body">
        <div
            style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px; margin-bottom:12px;">
            <h3 class="primary-text" style="margin:0;">Education</h3>
        </div>
        @if ($seeker->educations->isEmpty())
            <p style="color:#94a3b8;">No education entries yet.</p>
        @else
            <div style="display:grid; gap:12px; margin-bottom:16px;">
                @foreach ($seeker->educations as $education)
                    <div class="education-item" data-id="{{ $education->id }}" style="border:1px solid #e5e7eb; border-radius:12px; padding:14px;">
                        <div class="education-view">
                            <div style="display:flex; justify-content:space-between; gap:12px;">
                                <div>
                                    <strong>{{ $education->institution }}</strong>
                                    <p style="margin:4px 0 0; color:#475569;">
                                        {{ $education->degree ?? 'Degree' }}
                                        {{ $education->field_of_study ? '· ' . $education->field_of_study : '' }}
                                    </p>
                                    <p style="margin:4px 0 0; color:#94a3b8; font-size:13px;">
                                        {{ optional($education->start_date)->format('M Y') ?? 'Start' }} -
                                        {{ optional($education->end_date)->format('M Y') ?? 'Present' }}
                                    </p>
                                    <p style="margin:6px 0 0; color:#64748b;">{{ $education->description }}</p>
                                </div>
                                <div style="display:flex; gap:8px; align-items:flex-start;">
                                    <button type="button" class="edit-btn edit-education-btn {{ !$education->institution ? 'edit-btn-incomplete' : '' }}" data-id="{{ $education->id }}" title="Edit education">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M11.333 2.00001C11.5084 1.82445 11.7163 1.68506 11.9447 1.59115C12.1731 1.49723 12.4173 1.45068 12.6637 1.45468C12.9101 1.45868 13.1533 1.51313 13.3778 1.61437C13.6023 1.71561 13.8031 1.8613 13.968 2.04268C14.1329 2.22406 14.2585 2.43719 14.3374 2.66856C14.4163 2.89993 14.4467 3.14462 14.4267 3.38735C14.4067 3.63008 14.3367 3.86552 14.2213 4.07801L6.66667 11.6327L2.66667 12.6667L3.70067 8.66668L11.333 2.00001Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        Edit
                                    </button>
                                    <form action="{{ route('seeker.resume.educations.destroy', $education) }}" method="POST" style="margin:0;" onsubmit="return confirm('Are you sure you want to remove this education?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-danger btn-sm" type="submit" title="Remove education">Remove</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="education-edit" style="display:none;">
                            <form action="{{ route('seeker.resume.educations.update', $education) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="auth-dual">
                                    <div class="form-group">
                                        <label>Institution *</label>
                                        <input type="text" name="institution" class="form-control" value="{{ $education->institution }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Degree</label>
                                        <input type="text" name="degree" class="form-control" value="{{ $education->degree }}">
                                    </div>
                                </div>
                                <div class="auth-dual">
                                    <div class="form-group">
                                        <label>Field of study</label>
                                        <input type="text" name="field_of_study" class="form-control" value="{{ $education->field_of_study }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Start date</label>
                                        <input type="date" name="start_date" class="form-control" value="{{ optional($education->start_date)->format('Y-m-d') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>End date</label>
                                        <input type="date" name="end_date" class="form-control" value="{{ optional($education->end_date)->format('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description" rows="3" class="form-control">{{ $education->description }}</textarea>
                                </div>
                                <div style="display:flex; gap:8px; margin-top:12px;">
                                    <button type="submit" class="btn-primary">Update Education</button>
                                    <button type="button" class="btn-secondary cancel-edit-education-btn" data-id="{{ $education->id }}">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('seeker.resume.educations.store') }}" method="POST">
            @csrf
            <div class="auth-dual">
                <div class="form-group">
                    <label>Institution *</label>
                    <input type="text" name="institution" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Degree</label>
                    <input type="text" name="degree" class="form-control">
                </div>
            </div>
            <div class="auth-dual">
                <div class="form-group">
                    <label>Field of study</label>
                    <input type="text" name="field_of_study" class="form-control">
                </div>
                <div class="form-group">
                    <label>Start date</label>
                    <input type="date" name="start_date" class="form-control">
                </div>
                <div class="form-group">
                    <label>End date</label>
                    <input type="date" name="end_date" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn-primary">Add education</button>
        </form>
    </div>
</div>

<div class="card" style="border:1px solid #e2e8f0; margin-bottom:24px;">
    <div class="card-body">
        <div
            style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px; margin-bottom:12px;">
            <h3 class="primary-text" style="margin:0;">Job history</h3>
        </div>
        @if ($seeker->experiences->isEmpty())
            <p style="color:#94a3b8;">No experiences added.</p>
        @else
            <div style="display:grid; gap:12px; margin-bottom:16px;">
                @foreach ($seeker->experiences as $experience)
                    <div class="experience-item" data-id="{{ $experience->id }}" style="border:1px solid #e5e7eb; border-radius:12px; padding:14px;">
                        <div class="experience-view">
                            <div style="display:flex; justify-content:space-between; gap:12px;">
                                <div>
                                    <strong>{{ $experience->role_title }}</strong>
                                    <p style="margin:4px 0 0; color:#475569;">{{ $experience->company_name }}</p>
                                    <p style="margin:4px 0 0; color:#94a3b8; font-size:13px;">
                                        {{ optional($experience->start_date)->format('M Y') ?? 'Start' }} -
                                        {{ $experience->is_current ? 'Present' : optional($experience->end_date)->format('M Y') ?? 'End' }}
                                    </p>
                                    <p style="margin:6px 0 0; color:#64748b;">{{ $experience->achievements }}</p>
                                </div>
                                <div style="display:flex; gap:8px; align-items:flex-start;">
                                    <button type="button" class="edit-btn edit-experience-btn {{ !$experience->company_name || !$experience->role_title ? 'edit-btn-incomplete' : '' }}" data-id="{{ $experience->id }}" title="Edit experience">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M11.333 2.00001C11.5084 1.82445 11.7163 1.68506 11.9447 1.59115C12.1731 1.49723 12.4173 1.45068 12.6637 1.45468C12.9101 1.45868 13.1533 1.51313 13.3778 1.61437C13.6023 1.71561 13.8031 1.8613 13.968 2.04268C14.1329 2.22406 14.2585 2.43719 14.3374 2.66856C14.4163 2.89993 14.4467 3.14462 14.4267 3.38735C14.4067 3.63008 14.3367 3.86552 14.2213 4.07801L6.66667 11.6327L2.66667 12.6667L3.70067 8.66668L11.333 2.00001Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        Edit
                                    </button>
                                    <form action="{{ route('seeker.resume.experiences.destroy', $experience) }}" method="POST" style="margin:0;" onsubmit="return confirm('Are you sure you want to remove this experience?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-danger btn-sm" type="submit" title="Remove experience">Remove</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="experience-edit" style="display:none;">
                            <form action="{{ route('seeker.resume.experiences.update', $experience) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="auth-dual">
                                    <div class="form-group">
                                        <label>Company *</label>
                                        <input type="text" name="company_name" class="form-control" value="{{ $experience->company_name }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Role title *</label>
                                        <input type="text" name="role_title" class="form-control" value="{{ $experience->role_title }}" required>
                                    </div>
                                </div>
                                <div class="auth-dual">
                                    <div class="form-group">
                                        <label>Start date</label>
                                        <input type="date" name="start_date" class="form-control" value="{{ optional($experience->start_date)->format('Y-m-d') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>End date</label>
                                        <input type="date" name="end_date" class="form-control" value="{{ optional($experience->end_date)->format('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><input type="checkbox" name="is_current" value="1" {{ $experience->is_current ? 'checked' : '' }}> Currently working here</label>
                                </div>
                                <div class="form-group">
                                    <label>Key achievements</label>
                                    <textarea name="achievements" rows="3" class="form-control">{{ $experience->achievements }}</textarea>
                                </div>
                                <div style="display:flex; gap:8px; margin-top:12px;">
                                    <button type="submit" class="btn-primary">Update Experience</button>
                                    <button type="button" class="btn-secondary cancel-edit-experience-btn" data-id="{{ $experience->id }}">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('seeker.resume.experiences.store') }}" method="POST">
            @csrf
            <div class="auth-dual">
                <div class="form-group">
                    <label>Company *</label>
                    <input type="text" name="company_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Role title *</label>
                    <input type="text" name="role_title" class="form-control" required>
                </div>
            </div>
            <div class="auth-dual">
                <div class="form-group">
                    <label>Start date</label>
                    <input type="date" name="start_date" class="form-control">
                </div>
                <div class="form-group">
                    <label>End date</label>
                    <input type="date" name="end_date" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label><input type="checkbox" name="is_current" value="1"> Currently working here</label>
            </div>
            <div class="form-group">
                <label>Key achievements</label>
                <textarea name="achievements" rows="3" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn-primary">Add experience</button>
        </form>
    </div>
</div>

<div class="card" style="border:1px solid #e2e8f0; margin-bottom:24px;">
    <div class="card-body">
        <h3 class="primary-text" style="margin-bottom:12px;">References</h3>
        @if ($seeker->references->isEmpty())
            <p style="color:#94a3b8;">No references added.</p>
        @else
            <div style="display:grid; gap:12px; margin-bottom:16px;">
                @foreach ($seeker->references as $reference)
                    <div class="reference-item" data-id="{{ $reference->id }}" style="border:1px solid #e5e7eb; border-radius:12px; padding:14px;">
                        <div class="reference-view">
                            <div style="display:flex; justify-content:space-between; gap:12px;">
                                <div>
                                    <strong>{{ $reference->name }}</strong>
                                    <p style="margin:2px 0 0; color:#475569;">{{ $reference->title }} @
                                        {{ $reference->company }}</p>
                                    <p style="margin:4px 0 0; color:#64748b; font-size:13px;">
                                        {{ $reference->email }} {{ $reference->phone ? ' · ' . $reference->phone : '' }}
                                    </p>
                                    <p style="margin:6px 0 0; color:#94a3b8;">{{ $reference->notes }}</p>
                                </div>
                                <div style="display:flex; gap:8px; align-items:flex-start;">
                                    <button type="button" class="edit-btn edit-reference-btn {{ !$reference->name ? 'edit-btn-incomplete' : '' }}" data-id="{{ $reference->id }}" title="Edit reference">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M11.333 2.00001C11.5084 1.82445 11.7163 1.68506 11.9447 1.59115C12.1731 1.49723 12.4173 1.45068 12.6637 1.45468C12.9101 1.45868 13.1533 1.51313 13.3778 1.61437C13.6023 1.71561 13.8031 1.8613 13.968 2.04268C14.1329 2.22406 14.2585 2.43719 14.3374 2.66856C14.4163 2.89993 14.4467 3.14462 14.4267 3.38735C14.4067 3.63008 14.3367 3.86552 14.2213 4.07801L6.66667 11.6327L2.66667 12.6667L3.70067 8.66668L11.333 2.00001Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        Edit
                                    </button>
                                    <form action="{{ route('seeker.resume.references.destroy', $reference) }}" method="POST" style="margin:0;" onsubmit="return confirm('Are you sure you want to remove this reference?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-danger btn-sm" type="submit" title="Remove reference">Remove</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="reference-edit" style="display:none;">
                            <form action="{{ route('seeker.resume.references.update', $reference) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="auth-dual">
                                    <div class="form-group">
                                        <label>Name *</label>
                                        <input type="text" name="name" class="form-control" value="{{ $reference->name }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Company</label>
                                        <input type="text" name="company" class="form-control" value="{{ $reference->company }}">
                                    </div>
                                </div>
                                <div class="auth-dual">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" name="title" class="form-control" value="{{ $reference->title }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control" value="{{ $reference->email }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" name="phone" class="form-control" value="{{ $reference->phone }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Notes</label>
                                    <textarea name="notes" rows="2" class="form-control">{{ $reference->notes }}</textarea>
                                </div>
                                <div style="display:flex; gap:8px; margin-top:12px;">
                                    <button type="submit" class="btn-primary">Update Reference</button>
                                    <button type="button" class="btn-secondary cancel-edit-reference-btn" data-id="{{ $reference->id }}">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('seeker.resume.references.store') }}" method="POST">
            @csrf
            <div class="auth-dual">
                <div class="form-group">
                    <label>Name *</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Company</label>
                    <input type="text" name="company" class="form-control">
                </div>
            </div>
            <div class="auth-dual">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control">
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label>Notes</label>
                <textarea name="notes" rows="2" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn-primary">Add reference</button>
        </form>
    </div>
</div>

<div class="card" style="border:1px solid #e2e8f0;">
    <div class="card-body">
        <h3 class="primary-text" style="margin-bottom:12px;">Hobbies & interests</h3>
        @if ($seeker->hobbies->isEmpty())
            <p style="color:#94a3b8;">No hobbies added.</p>
        @else
            <div style="display:grid; gap:12px; margin-bottom:16px;">
                @foreach ($seeker->hobbies as $hobby)
                    <div
                        style="border:1px dashed #e5e7eb; border-radius:12px; padding:12px; display:flex; justify-content:space-between; gap:12px;">
                        <div>
                            <strong>{{ $hobby->name }}</strong>
                            <p style="margin:4px 0 0; color:#64748b;">{{ $hobby->description }}</p>
                        </div>
                        <form action="{{ route('seeker.resume.hobbies.destroy', $hobby) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn-danger btn-sm" type="submit">Remove</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('seeker.resume.hobbies.store') }}" method="POST">
            @csrf
            <div class="auth-dual">
                <div class="form-group">
                    <label>Name *</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <input type="text" name="description" class="form-control">
                </div>
            </div>
            <button type="submit" class="btn-primary">Add hobby</button>
        </form>
    </div>
</div>
