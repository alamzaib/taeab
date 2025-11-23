@extends('layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', $seeker->name . ' - Talent Profile')

@section('content')
    @php
        $skills = $skills ?? [];
        $profilePhoto = $seeker->profile_photo_url ?? asset('images/avatar-placeholder.svg');
        $coverStyle = $seeker->profile_cover_style ?? 'linear-gradient(135deg,#235181,#1a3d63)';
    @endphp
    <div class="container" style="max-width:1200px; margin-bottom:60px;">

        <div class="card talent-export-card" style="padding:0; overflow:hidden;">
            <div class="talent-hero" style="background:{{ $coverStyle }}; padding:40px; position:relative;">
                <div
                    style="position:absolute; inset:0; background:linear-gradient(120deg,rgba(20,35,53,.85),rgba(35,81,129,.65));">
                </div>
                <div style="position:relative; z-index:2; color:white;">
                    <div class="hero-actions hero-actions-top">
                        @if ($seeker->linkedin_url)
                            <a href="{{ $seeker->linkedin_url }}" target="_blank" rel="noopener"
                                class="hero-action-button hero-action-outline">LinkedIn</a>
                        @endif
                        @if ($defaultResume)
                            <a href="{{ Storage::disk('public')->url($defaultResume->file_path) }}" target="_blank"
                                rel="noopener" class="hero-action-button hero-action-light">Download CV</a>
                        @endif
                        <button type="button" id="downloadPublicProfile"
                            class="hero-action-button hero-action-primary">Resume by Taeab</button>
                    </div>
                    <div class="hero-content">
                        <div
                            style="width:110px; height:110px; border-radius:24px; border:4px solid rgba(255,255,255,.4); overflow:hidden; background:white;">
                            <img src="{{ $profilePhoto }}" alt="{{ $seeker->name }}"
                                style="width:100%; height:100%; object-fit:cover;">
                        </div>
                        <div>
                            <p
                                style="margin:0; letter-spacing:.08em; text-transform:uppercase; font-size:12px; opacity:.85;">
                                Talent profile</p>
                            <h1 style="margin:8px 0 10px; font-size:34px;">{{ $seeker->name }}</h1>
                            <div
                                style="margin-top:10px; display:flex; gap:20px; flex-wrap:wrap; font-size:13px; text-transform:uppercase; letter-spacing:.08em;">
                                <span>Profile ID: <strong>{{ $seeker->unique_code }}</strong></span>
                                @if ($seeker->residence_country)
                                    <span>Location: <strong>{{ $seeker->residence_country }}</strong></span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="public-profile-content">
                <section class="card summary-card">
                    <div class="card-body">
                        <h2 class="primary-text" style="margin-bottom:12px;">Professional Summary</h2>
                        <p style="margin:0; color:#475569; line-height:1.6;">
                            {{ $seeker->resume_bio ?? ($seeker->about ?? 'This talent has not added a summary yet.') }}</p>
                        <div class="snapshot-inline">
                            <div>
                                <span class="snapshot-label">Current Company</span>
                                <strong>{{ $seeker->current_company ?? 'Not specified' }}</strong>
                            </div>
                            <div>
                                <span class="snapshot-label">Current Salary</span>
                                <strong>{{ $seeker->current_salary ?? 'Not specified' }}</strong>
                            </div>
                            <div>
                                <span class="snapshot-label">Target Salary</span>
                                <strong>{{ $seeker->target_salary ?? 'Not specified' }}</strong>
                            </div>
                            <div>
                                <span class="snapshot-label">Location</span>
                                <strong>{{ $seeker->residence_country ?? 'Not specified' }}</strong>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="public-columns">
                    <div class="public-column-left">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="primary-text" style="margin-bottom:12px;">Contact</h2>
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
                                    @if ($seeker->resume_portfolio_url)
                                        <div class="contact-item">
                                            <span class="icon-chip">🌐</span>
                                            <a href="{{ $seeker->resume_portfolio_url }}"
                                                target="_blank">{{ $seeker->resume_portfolio_url }}</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if (!empty($skills))
                            <div class="card">
                                <div class="card-body">
                                    <h2 class="primary-text" style="margin-bottom:12px;">Skills</h2>
                                    <div class="skills-stack">
                                        @foreach ($skills as $skill)
                                            <span class="skill-pill">{{ $skill }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($seeker->hobbies->isNotEmpty())
                            <div class="card">
                                <div class="card-body">
                                    <h2 class="primary-text" style="margin-bottom:12px;">Hobbies</h2>
                                    <div class="skills-stack">
                                        @foreach ($seeker->hobbies as $hobby)
                                            <span class="skill-pill">
                                                {{ $hobby->name }}{{ $hobby->description ? ' – ' . $hobby->description : '' }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="public-column-right">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="primary-text" style="margin-bottom:12px;">Job History</h2>
                                <div class="stacked-blocks">
                                    @forelse ($seeker->experiences as $experience)
                                        <div class="block">
                                            <strong>{{ $experience->role_title }}</strong>
                                            <p class="meta">{{ $experience->company_name }}</p>
                                            <p class="sub-meta">
                                                {{ optional($experience->start_date)->format('M Y') ?? 'Start' }} –
                                                {{ $experience->is_current ? 'Present' : optional($experience->end_date)->format('M Y') ?? 'End' }}
                                            </p>
                                            @if ($experience->achievements)
                                                <p style="white-space:pre-line;">{{ $experience->achievements }}</p>
                                            @endif
                                        </div>
                                    @empty
                                        <p class="text-muted">No job history added.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h2 class="primary-text" style="margin-bottom:12px;">Education</h2>
                                <div class="stacked-blocks">
                                    @forelse ($seeker->educations as $education)
                                        <div class="block">
                                            <strong>{{ $education->institution }}</strong>
                                            <p class="meta">
                                                {{ $education->degree ?? 'Degree' }}
                                                {{ $education->field_of_study ? ' · ' . $education->field_of_study : '' }}
                                            </p>
                                            <p class="sub-meta">
                                                {{ optional($education->start_date)->format('M Y') ?? 'Start' }} –
                                                {{ optional($education->end_date)->format('M Y') ?? 'Present' }}
                                            </p>
                                            @if ($education->description)
                                                <p>{{ $education->description }}</p>
                                            @endif
                                        </div>
                                    @empty
                                        <p class="text-muted">No education added.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>


                        @if ($seeker->references->isNotEmpty())
                            <div class="card">
                                <div class="card-body">
                                    <h2 class="primary-text" style="margin-bottom:12px;">References</h2>
                                    <div class="stacked-blocks">
                                        @foreach ($seeker->references as $reference)
                                            <div class="block">
                                                <strong>{{ $reference->name }}</strong>
                                                <p class="meta">{{ $reference->title }} @ {{ $reference->company }}</p>
                                                <p class="sub-meta">
                                                    {{ $reference->email }}
                                                    @if ($reference->phone)
                                                        · {{ $reference->phone }}
                                                    @endif
                                                </p>
                                                @if ($reference->notes)
                                                    <p>{{ $reference->notes }}</p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .public-profile-content {
            padding: 32px;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .snapshot-inline {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px 18px;
            margin-top: 14px;
        }

        .snapshot-inline div {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .snapshot-label {
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: .08em;
            color: #94a3b8;
        }

        .public-columns {
            display: grid;
            grid-template-columns: minmax(220px, 28%) 1fr;
            gap: 18px;
        }

        .public-column-left,
        .public-column-right {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .contact-stack {
            display: flex;
            flex-direction: column;
            gap: 10px;
            color: #475569;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .icon-chip {
            width: 28px;
            height: 28px;
            border-radius: 10px;
            border: 1px solid #dbeafe;
            background: #eff6ff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #1d4ed8;
        }

        .skills-stack {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .skill-pill {
            padding: 6px 14px;
            border-radius: 999px;
            background: #eef2ff;
            color: #1e3a8a;
            font-weight: 600;
            font-size: 13px;
        }

        .stacked-blocks {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .stacked-blocks .block {
            border-left: 3px solid #2563eb;
            padding-left: 16px;
        }

        .stacked-blocks .meta {
            margin: 4px 0 0;
            color: #475569;
        }

        .stacked-blocks .sub-meta {
            margin: 0;
            color: #94a3b8;
            font-size: 13px;
        }

        @media print {
            body {
                background: #fff;
            }

            .container {
                max-width: 1020px !important;
            }

            .public-profile-content {
                padding: 24px;
                gap: 16px;
            }

            .public-columns {
                grid-template-columns: minmax(220px, 0.32fr) 1fr;
            }
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            justify-content: center;
        }

        .hero-actions-top {
            width: 100%;
            margin-bottom: 22px;
        }

        .hero-content {
            display: flex;
            gap: 20px;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .hero-action-button {
            padding: 10px 20px;
            border-radius: 999px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .05em;
            font-size: 13px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            text-decoration: none;
            transition: background .2s ease, color .2s ease, border .2s ease;
        }

        .hero-action-outline {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .hero-action-outline:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .hero-action-light {
            background: #fff;
            color: #1e3a8a;
            border-color: #fff;
        }

        .hero-action-light:hover {
            background: #f8fafc;
        }

        .hero-action-primary {
            background: #ffb347;
            color: #1a2a44;
            border-color: #ffb347;
        }

        .hero-action-primary:hover {
            background: #ff9f1c;
            border-color: #ff9f1c;
        }

        @media screen and (max-width: 992px) {
            .public-columns {
                grid-template-columns: 1fr;
            }

            .hero-content {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const downloadBtn = document.getElementById('downloadPublicProfile');
            if (!downloadBtn) {
                return;
            }

            downloadBtn.addEventListener('click', function() {
                const exportCard = document.querySelector('.talent-export-card');
                if (!exportCard) {
                    return;
                }

                const printWindow = window.open('', '_blank');
                if (!printWindow) {
                    alert('Please allow pop-ups to download the resume.');
                    return;
                }

                const styles = Array.from(document.querySelectorAll('link[rel="stylesheet"], style'))
                    .map(node => node.outerHTML)
                    .join('');

                const exportHtml = `
                    <html>
                        <head>
                            <title>Resume by Taeab - {{ $seeker->name }}</title>
                            <link rel="preconnect" href="https://fonts.googleapis.com">
                            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                            <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
                            ${styles}
                            <style>
                                body {
                                    font-family: 'Roboto', sans-serif;
                                    margin: 0;
                                    background: #f8fafc;
                                    padding: 32px;
                                }
                                .talent-export-card {
                                    box-shadow: none;
                                    border: none;
                                    width: 100%;
                                    max-width: 1050px;
                                    margin: 0 auto;
                                }
                                .talent-export-card > .public-profile-content {
                                    padding: 0px 32px;
                                }
                                .hero-actions {
                                    display: none !important;
                                }
                                @media print {
                                    body {
                                        padding: 0;
                                        background: #fff;
                                    }
                                    .talent-export-card {
                                        box-shadow: none !important;
                                        border: none !important;
                                        width: 100%;
                                        max-width: 960px;
                                        margin: 0 auto;
                                    }
                                }
                                @page {
                                    size: A4;
                                    margin: 10mm 10mm;
                                }
                                .public-columns {
                                    grid-template-columns: minmax(220px, 0.32fr) 1fr !important;
                                }
                            </style>
                        </head>
                        <body>
                            ${exportCard.outerHTML}
                            <script>
                                window.addEventListener('load', () => {
                                    window.print();
                                    window.addEventListener('afterprint', () => window.close());
                                });
                            <\/script>
                        </body>
                    </html>
                `;

                printWindow.document.open();
                printWindow.document.write(exportHtml);
                printWindow.document.close();
            });
        });
    </script>
@endpush
