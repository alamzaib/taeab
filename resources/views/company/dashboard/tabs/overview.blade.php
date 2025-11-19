<h2 class="primary-text" style="margin:0 0 24px; font-size:28px;">Overview</h2>

<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:18px; margin-bottom:28px;">
    <div class="card" style="border:1px solid #e2e8f0;">
        <p style="margin:0; color:#64748b; font-size:13px;">Active jobs</p>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:6px;">
            <span style="font-size:36px; font-weight:700; color:#0f172a;">{{ $stats['active_jobs'] }}</span>
            <span style="font-size:12px; color:#16a34a;">+2 launched</span>
        </div>
    </div>
    <div class="card" style="border:1px solid #e2e8f0;">
        <p style="margin:0; color:#64748b; font-size:13px;">Talent awaiting review</p>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:6px;">
            @php
                $pendingCount = \App\Models\JobApplication::whereHas('job', function($q) use ($company) {
                        $q->where('company_id', $company->id);
                    })
                    ->whereIn('status', ['submitted', 'reviewing'])
                    ->count();
            @endphp
            <span style="font-size:36px; font-weight:700; color:#0f172a;">{{ $pendingCount }}</span>
            <span style="font-size:12px; color:#ea580c;">Action needed</span>
        </div>
    </div>
    <div class="card" style="border:1px solid #e2e8f0;">
        <p style="margin:0; color:#64748b; font-size:13px;">Interviews scheduled</p>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:6px;">
            <span style="font-size:36px; font-weight:700; color:#0f172a;">5</span>
            <span style="font-size:12px; color:#0891b2;">3 this week</span>
        </div>
    </div>
    <div class="card" style="border:1px dashed #fcd34d; background:#fffbeb;">
        <p style="margin:0; font-weight:600;">Employer brand health</p>
        <p style="margin:6px 0 12px; color:#92400e;">Add fresh photos & testimonials to attract better talent.</p>
        <a href="{{ route('company.dashboard', ['tab' => 'profile']) }}" class="btn-primary" style="width:fit-content;">Refresh profile</a>
    </div>
    <div class="card" style="border:1px solid #e2e8f0;">
        <p style="margin:0; color:#64748b; font-size:13px;">Current Package</p>
        <div style="margin-top:6px;">
            @if($company->package)
                <div style="display:flex; align-items:center; gap:8px;">
                    <span style="font-size:24px; font-weight:700; color:#0f172a;">{{ $company->package->display_name }}</span>
                    @if($company->package->price > 0)
                        <span style="font-size:14px; color:#64748b;">${{ number_format($company->package->price, 2) }}/mo</span>
                    @else
                        <span style="font-size:14px; color:#64748b;">Free</span>
                    @endif
                </div>
                @if($company->package->features)
                    <p style="margin:8px 0 0; font-size:12px; color:#64748b;">
                        {{ implode(', ', array_slice($company->package->features, 0, 2)) }}
                        @if(count($company->package->features) > 2)
                            <span>+{{ count($company->package->features) - 2 }} more</span>
                        @endif
                    </p>
                @endif
            @else
                <span style="font-size:18px; font-weight:600; color:#64748b;">No package assigned</span>
            @endif
        </div>
        <a href="{{ route('company.packages.index') }}" class="btn btn-sm btn-light" style="margin-top:12px; width:fit-content;">Manage Package</a>
    </div>
</div>

<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:22px; margin-bottom:28px;">
    <div class="card" style="border:1px solid #e2e8f0;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
            <h3 class="primary-text" style="margin:0;">Pipeline priorities</h3>
            <span style="font-size:12px; color:#94a3b8;">Today</span>
        </div>
        <div style="display:grid; gap:12px;">
            <div style="border:1px dashed #e5e7eb; border-radius:12px; padding:14px;">
                <strong>Review new applicants</strong>
                <p style="margin:6px 0 0; color:#6b7280;">Check your latest submissions</p>
                <a href="{{ route('company.dashboard', ['tab' => 'applications']) }}" style="font-size:13px;">Open applicant tray →</a>
            </div>
            <div style="border:1px dashed #e5e7eb; border-radius:12px; padding:14px;">
                <strong>Schedule interviews</strong>
                <p style="margin:6px 0 0; color:#6b7280;">Connect with promising candidates</p>
                <span style="font-size:13px; color:#ea580c;">3 candidates waiting</span>
            </div>
            <div style="border:1px dashed #e5e7eb; border-radius:12px; padding:14px;">
                <strong>Refresh expiring roles</strong>
                <p style="margin:6px 0 0; color:#6b7280;">2 listings close in 48h</p>
                <a href="{{ route('company.dashboard', ['tab' => 'jobs']) }}" style="font-size:13px;">Review jobs →</a>
            </div>
        </div>
    </div>

    <div class="card" style="border:1px solid #e2e8f0;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
            <h3 class="primary-text" style="margin:0;">Action center</h3>
            <span style="font-size:12px; color:#94a3b8;">Shortcuts</span>
        </div>
        <div style="display:grid; gap:12px;">
            <a href="{{ route('company.jobs.create') }}" class="card" style="border:1px dashed #c7d2fe; background:#eef2ff; text-decoration:none; color:inherit;">
                <strong>Launch a new requisition</strong>
                <span style="font-size:13px; color:#475569;">Leverage saved templates</span>
            </a>
            <a href="{{ route('company.dashboard', ['tab' => 'jobs']) }}" class="card" style="border:1px dashed #bfdbfe; background:#eff6ff; text-decoration:none; color:inherit;">
                <strong>Manage all openings</strong>
                <span style="font-size:13px; color:#1d4ed8;">Edit, pause, or close roles</span>
            </a>
            <a href="{{ route('company.dashboard', ['tab' => 'applications']) }}" class="card" style="border:1px dashed #fecdd3; background:#fff1f2; text-decoration:none; color:inherit;">
                <strong>Conversations inbox</strong>
                <span style="font-size:13px; color:#be123c;">Chat with seekers</span>
            </a>
            <a href="{{ route('company.dashboard', ['tab' => 'profile']) }}" class="card" style="border:1px dashed #bbf7d0; background:#ecfdf5; text-decoration:none; color:inherit;">
                <strong>Update company profile</strong>
                <span style="font-size:13px; color:#047857;">Logo, socials, coordinates</span>
            </a>
        </div>
    </div>

    <div class="card" style="border:1px solid #e2e8f0;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
            <h3 class="primary-text" style="margin:0;">Messages</h3>
            <a href="{{ route('company.dashboard', ['tab' => 'messages']) }}" style="font-size:12px;">Open inbox</a>
        </div>
        @if(isset($recentMessages) && $recentMessages->isNotEmpty())
            <div style="display:grid; gap:12px;">
                @foreach($recentMessages->take(3) as $message)
                    <div style="border:1px dashed #e5e7eb; border-radius:12px; padding:14px;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                            <strong>{{ $message->application->seeker->name }}</strong>
                            <span style="font-size:12px; color:#94a3b8;">{{ $message->created_at->diffForHumans() }}</span>
                        </div>
                        <p style="margin:0 0 6px; color:#475569;">{{ $message->application->job->title }}</p>
                        <p style="margin:0 0 10px; color:#6b7280;">"{{ \Illuminate\Support\Str::limit($message->message, 120) }}"</p>
                        <a href="{{ route('company.applications.show', $message->application) }}" class="btn btn-sm btn-primary">Reply</a>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align:center; padding:30px 10px; color:#94a3b8;">
                No conversations yet. Open an applicant to start chatting.
            </div>
        @endif
    </div>
</div>

