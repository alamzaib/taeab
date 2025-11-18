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
                <p style="margin:0; text-transform:uppercase; font-size:11px; letter-spacing:.1em; color:#94a3b8;">Resume
                    preview</p>
                <h3 style="margin:8px 0 4px;">{{ $seeker->name }}</h3>
                <p style="margin:0; color:#64748b;">{{ $seeker->current_company ?? 'Ready for new challenge' }}</p>
            </div>
            <div style="text-align:right;">
                <p style="margin:0; text-transform:uppercase; font-size:11px; letter-spacing:.1em; color:#94a3b8;">
                    Profile ID</p>
                <strong style="font-size:20px;">{{ $seeker->unique_code }}</strong>
                <div style="margin-top:6px;">
                    <a href="{{ route('seeker.resume.preview') }}" target="_blank"
                        class="btn btn-sm btn-outline-primary">Open Resume</a>
                </div>
            </div>
        </div>
        <hr>
        <p style="margin:0; color:#475569; line-height:1.6;">
            {{ $seeker->resume_bio ?? 'Add a resume bio to tell hiring teams what you bring to the table.' }}</p>
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
                    <div style="border:1px solid #e5e7eb; border-radius:12px; padding:14px;">
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
                            <form action="{{ route('seeker.resume.educations.destroy', $education) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Remove</button>
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
            <button type="submit" class="btn btn-outline-primary">Add education</button>
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
                    <div style="border:1px solid #e5e7eb; border-radius:12px; padding:14px;">
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
                            <form action="{{ route('seeker.resume.experiences.destroy', $experience) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Remove</button>
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
            <button type="submit" class="btn btn-outline-primary">Add experience</button>
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
                    <div
                        style="border:1px solid #e5e7eb; border-radius:12px; padding:14px; display:flex; justify-content:space-between; gap:12px;">
                        <div>
                            <strong>{{ $reference->name }}</strong>
                            <p style="margin:2px 0 0; color:#475569;">{{ $reference->title }} @
                                {{ $reference->company }}</p>
                            <p style="margin:4px 0 0; color:#64748b; font-size:13px;">
                                {{ $reference->email }} {{ $reference->phone ? ' · ' . $reference->phone : '' }}
                            </p>
                            <p style="margin:6px 0 0; color:#94a3b8;">{{ $reference->notes }}</p>
                        </div>
                        <form action="{{ route('seeker.resume.references.destroy', $reference) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">Remove</button>
                        </form>
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
            <button type="submit" class="btn btn-outline-primary">Add reference</button>
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
                            <button class="btn btn-sm btn-outline-danger" type="submit">Remove</button>
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
            <button type="submit" class="btn btn-outline-primary">Add hobby</button>
        </form>
    </div>
</div>
