@extends('layouts.app')

@section('title', 'Resume by Taeab - ' . $seeker->name)

@section('content')
    @php
        $profilePhoto = $seeker->profile_photo_url ?? asset('images/avatar-placeholder.svg');
        $coverStyle = $seeker->profile_cover_style ?? 'linear-gradient(135deg,#235181,#1a3d63)';
    @endphp

    <div class="container" style="max-width:900px; padding:30px 0;">
        <div style="display:flex; justify-content:flex-end; margin-bottom:15px; gap:10px;">
            <a href="{{ route('seeker.dashboard', ['tab' => 'resume-builder']) }}" class="btn btn-secondary">← Back to
                builder</a>
            <button class="btn-primary" onclick="window.print()">Resume by Taeab</button>
        </div>
        <div class="card resume-print" style="padding:0; overflow:hidden;">
            <div style="background:{{ $coverStyle }}; padding:32px; color:white;">
                <div style="display:flex; gap:20px; flex-wrap:wrap; align-items:center;">
                    <div
                        style="width:110px; height:110px; border-radius:24px; border:4px solid rgba(255,255,255,.4); overflow:hidden; background:white;">
                        <img src="{{ $profilePhoto }}" alt="{{ $seeker->name }}"
                            style="width:100%; height:100%; object-fit:cover;">
                    </div>
                    <div style="flex:1;">
                        <p style="margin:0; text-transform:uppercase; letter-spacing:.1em; font-size:12px;">Resume by Taeab
                        </p>
                        <h1 style="margin:4px 0 6px; font-size:32px;">{{ $seeker->name }}</h1>
                        <p style="margin:0; color:rgba(255,255,255,.85); font-size:16px;">
                            {{ $seeker->current_title ?? 'Open to new opportunities' }}
                        </p>
                        <p style="margin:6px 0 0; color:rgba(255,255,255,.75); font-size:14px;">
                            {{ $seeker->email }}
                            @if ($seeker->phone)
                                · {{ $seeker->phone }}
                            @endif
                            @if ($seeker->linkedin_url)
                                · {{ $seeker->linkedin_url }}
                            @endif
                        </p>
                    </div>
                    <div style="text-align:right;">
                        <p style="margin:0; text-transform:uppercase; font-size:11px; letter-spacing:.1em;">Profile ID</p>
                        <strong style="font-size:20px;">{{ $seeker->unique_code }}</strong>
                    </div>
                </div>
            </div>

            <div style="padding:32px; display:grid; grid-template-columns:260px 1fr; gap:32px;">
                <div style="background:#f8fafc; border-radius:16px; padding:24px;">
                    <section>
                        <h2 class="primary-text" style="font-size:18px; margin-bottom:8px;">Professional Summary</h2>
                        <p style="margin:0; color:#475569; line-height:1.6;">
                            {{ $seeker->resume_bio ?? 'Add a resume bio from Build Resume tab.' }}</p>
                    </section>

                    <section style="margin-top:24px;">
                        <div class="contact-stack">
                            <div class="contact-item">
                                <span class="icon-chip">@</span>
                                <span>{{ $seeker->email }}</span>
                            </div>
                            @if ($seeker->phone)
                                <div class="contact-item">
                                    <span class="icon-chip">☎</span>
                                    <span>{{ $seeker->phone }}</span>
                                </div>
                            @endif
                            @if ($seeker->whatsapp_number)
                                <div class="contact-item">
                                    <span class="icon-chip">💬</span>
                                    <span>{{ $seeker->whatsapp_number }}</span>
                                </div>
                            @endif
                            @if ($seeker->address)
                                <div class="contact-item">
                                    <span class="icon-chip">📍</span>
                                    <span>{{ $seeker->address }}</span>
                                </div>
                            @endif
                        </div>
                    </section>

                    <section style="margin-top:24px;">
                        <h3 class="primary-text" style="font-size:16px; margin-bottom:8px;">Skills</h3>
                        @if (empty($skills))
                            <p style="margin:0; color:#94a3b8;">No skills added yet.</p>
                        @else
                            <div style="display:flex; flex-direction:column; gap:6px;">
                                @foreach ($skills as $skill)
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        <span
                                            style="display:inline-block; width:6px; height:6px; border-radius:50%; background:#235181;"></span>
                                        <span>{{ $skill }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </section>

                    <section style="margin-top:24px;">
                        <h3 class="primary-text" style="font-size:16px; margin-bottom:8px;">Hobbies</h3>
                        @if ($seeker->hobbies->isEmpty())
                            <p style="margin:0; color:#94a3b8;">No hobbies added.</p>
                        @else
                            <ul style="padding-left:0; margin:0; list-style:none;">
                                @foreach ($seeker->hobbies as $hobby)
                                    <li style="margin-bottom:4px;">{{ $hobby->name }}
                                        {{ $hobby->description ? '– ' . $hobby->description : '' }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </section>
                </div>

                <div style="display:grid; gap:24px;">
                    <section>
                        <h3 class="primary-text" style="font-size:16px; margin-bottom:8px;">Education</h3>
                        @forelse($seeker->educations as $education)
                            <div style="margin-bottom:18px; border-bottom:1px solid #e5e7eb; padding-bottom:12px;">
                                <strong>{{ $education->institution }}</strong>
                                <p style="margin:4px 0 0; color:#475569;">
                                    {{ $education->degree ?? 'Degree' }}
                                    {{ $education->field_of_study ? '· ' . $education->field_of_study : '' }}
                                </p>
                                <p style="margin:0; color:#94a3b8; font-size:13px;">
                                    {{ optional($education->start_date)->format('M Y') ?? 'Start' }} –
                                    {{ optional($education->end_date)->format('M Y') ?? 'Present' }}
                                </p>
                                <p style="margin:6px 0 0; color:#64748b;">{{ $education->description }}</p>
                            </div>
                        @empty
                            <p style="color:#94a3b8;">No education added.</p>
                        @endforelse
                    </section>

                    <section>
                        <h3 class="primary-text" style="font-size:16px; margin-bottom:8px;">Experience</h3>
                        @forelse($seeker->experiences as $experience)
                            <div style="margin-bottom:18px; border-bottom:1px solid #e5e7eb; padding-bottom:12px;">
                                <strong>{{ $experience->role_title }}</strong>
                                <p style="margin:4px 0 0; color:#475569;">{{ $experience->company_name }}</p>
                                <p style="margin:0; color:#94a3b8; font-size:13px;">
                                    {{ optional($experience->start_date)->format('M Y') ?? 'Start' }} –
                                    {{ $experience->is_current ? 'Present' : optional($experience->end_date)->format('M Y') ?? 'End' }}
                                </p>
                                <p style="margin:6px 0 0; color:#64748b; white-space:pre-line;">
                                    {{ $experience->achievements }}</p>
                            </div>
                        @empty
                            <p style="color:#94a3b8;">No experiences added.</p>
                        @endforelse
                    </section>

                    <section>
                        <h3 class="primary-text" style="font-size:16px; margin-bottom:8px;">References</h3>
                        @forelse($seeker->references as $reference)
                            <div style="margin-bottom:14px;">
                                <strong>{{ $reference->name }}</strong>
                                <p style="margin:2px 0; color:#475569;">{{ $reference->title }} @
                                    {{ $reference->company }}</p>
                                <p style="margin:0; color:#94a3b8; font-size:13px;">
                                    {{ $reference->email }} {{ $reference->phone ? '· ' . $reference->phone : '' }}
                                </p>
                                <p style="margin:6px 0 0; color:#64748b;">{{ $reference->notes }}</p>
                            </div>
                        @empty
                            <p style="color:#94a3b8;">No references added.</p>
                        @endforelse
                    </section>
                </div>
            </div>
        </div>
    </div>

    <style>
        .contact-stack {
            display: flex;
            flex-direction: column;
            gap: 6px;
            color: #475569;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .icon-chip {
            width: 26px;
            height: 26px;
            border-radius: 8px;
            border: 1px solid #cbd5f5;
            background: #f1f5f9;
            color: #475569;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            .resume-print,
            .resume-print * {
                visibility: visible;
            }

            .resume-print {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .btn,
            button,
            a {
                display: none !important;
            }
        }
    </style>
@endsection
