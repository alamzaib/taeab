@php
    use Illuminate\Support\Facades\Storage;
@endphp
<h2 class="primary-text" style="margin:0 0 24px; font-size:28px;">Overview</h2>

<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:18px; margin-bottom:28px;">
    <div class="card" style="border:1px solid #e2e8f0;">
        <p style="margin:0; color:#64748b; font-size:13px;">Applications sent</p>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:6px;">
            <span style="font-size:36px; font-weight:700; color:#0f172a;">{{ $stats['applications'] }}</span>
            <span style="font-size:12px; color:#16a34a;">+4 this week</span>
        </div>
    </div>
    <div class="card" style="border:1px solid #e2e8f0;">
        <p style="margin:0; color:#64748b; font-size:13px;">Saved roles</p>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:6px;">
            <span style="font-size:36px; font-weight:700; color:#0f172a;">{{ $stats['favorites'] }}</span>
            <span style="font-size:12px; color:#ea580c;">3 expiring soon</span>
        </div>
    </div>
    <div class="card" style="border:1px solid #e2e8f0;">
        <p style="margin:0; color:#64748b; font-size:13px;">Documents on file</p>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:6px;">
            <span style="font-size:36px; font-weight:700; color:#0f172a;">{{ $seeker->documents()->count() }}</span>
            <span style="font-size:12px; color:#0891b2;">Keep CVs fresh</span>
        </div>
    </div>
    <div class="card" style="border:1px dashed #c7d2fe; background:#f8fafc;">
        <p style="margin:0; font-weight:600; color:#0f172a;">Goal this week</p>
        <p style="margin:6px 0 12px; color:#475569;">Apply to 5 curated roles & reach out to 2 recruiters.</p>
        <a href="{{ route('jobs.index') }}" class="btn-primary" style="width:fit-content;">Start now</a>
    </div>
</div>

<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:20px; margin-bottom:28px;">
    <div class="card" style="border:1px solid #e2e8f0;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
            <h3 class="primary-text" style="margin:0;">Quick actions</h3>
            <span style="font-size:12px; color:#94a3b8;">Stay ahead</span>
        </div>
        <div style="display:grid; gap:12px;">
            <a href="{{ route('jobs.index') }}" class="card" style="border:1px dashed #c7d2fe; background:#eef2ff; text-decoration:none; color:inherit;">
                <strong>Browse curated jobs</strong>
                <span style="font-size:13px; color:#475569;">AI-filtered for your skills</span>
            </a>
            <a href="{{ route('seeker.dashboard', ['tab' => 'resume']) }}" class="card" style="border:1px dashed #fcd34d; background:#fffbeb; text-decoration:none; color:inherit;">
                <strong>Refresh CV & cover letter</strong>
                <span style="font-size:13px; color:#654321;">Upload tailored versions</span>
            </a>
            <a href="{{ route('seeker.dashboard', ['tab' => 'profile']) }}" class="card" style="border:1px dashed #6ee7b7; background:#ecfdf5; text-decoration:none; color:inherit;">
                <strong>Complete profile fields</strong>
                <span style="font-size:13px; color:#065f46;">Add salary expectations</span>
            </a>
            <a href="{{ route('seeker.dashboard', ['tab' => 'applications']) }}" class="card" style="border:1px dashed #bae6fd; background:#e0f2fe; text-decoration:none; color:inherit;">
                <strong>Follow up on applications</strong>
                <span style="font-size:13px; color:#0c4a6e;">Send a quick update</span>
            </a>
        </div>
    </div>

    <div class="card" style="border:1px solid #e2e8f0;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
            <h3 class="primary-text" style="margin:0;">Recent applications</h3>
            <a href="{{ route('seeker.dashboard', ['tab' => 'applications']) }}" style="font-size:12px;">View all</a>
        </div>
        @if ($recentApplications->isEmpty())
            <div style="text-align:center; padding:30px 10px; color:#94a3b8;">
                <p style="margin-bottom:4px;">No applications yet.</p>
                <a href="{{ route('jobs.index') }}" class="btn btn-sm btn-primary mt-2">Browse jobs</a>
            </div>
        @else
            <div style="display:grid; gap:16px;">
                @foreach ($recentApplications->take(3) as $application)
                    @php
                        $status = $application->status ?? 'submitted';
                        $statusColors = [
                            'submitted' => ['bg' => '#0dcaf0', 'text' => '#fff'],
                            'reviewed' => ['bg' => '#0d6efd', 'text' => '#fff'],
                            'shortlisted' => ['bg' => '#ffc107', 'text' => '#000'],
                            'interviewed' => ['bg' => '#6c757d', 'text' => '#fff'],
                            'accepted' => ['bg' => '#198754', 'text' => '#fff'],
                            'rejected' => ['bg' => '#dc3545', 'text' => '#fff'],
                        ];
                        $color = $statusColors[$status] ?? ['bg' => '#f8f9fa', 'text' => '#000'];
                    @endphp
                    <div style="border:1px solid #e2e8f0; border-radius:12px; padding:14px 16px;">
                        <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px;">
                            <div style="flex:1;">
                                <a href="{{ route('jobs.show', $application->job->slug) }}" style="font-weight:600; color:#0f172a; display:block; margin-bottom:4px; text-decoration:none;">
                                    {{ $application->job->title }}
                                </a>
                                <p style="margin:0; color:#64748b; font-size:13px;">
                                    {{ $application->job->company->company_name ?? 'Company' }} • {{ $application->job->location ?? 'UAE' }}
                                </p>
                                <p style="margin:4px 0 0; color:#94a3b8; font-size:12px;">
                                    Applied {{ $application->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <span class="badge" style="background-color:{{ $color['bg'] }}; color:{{ $color['text'] }}; font-weight:500; padding:4px 10px;">
                                {{ ucfirst($status) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

