<h2 class="primary-text" style="margin:0 0 24px; font-size:28px;">Overview</h2>

<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:18px; margin-bottom:28px;">
    <div class="card" style="border:1px solid #e2e8f0;">
        <p style="margin:0; color:#64748b; font-size:13px;">Shortlists shared</p>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:6px;">
            <span style="font-size:36px; font-weight:700; color:#0f172a;">6</span>
            <span style="font-size:12px; color:#16a34a;">+1 today</span>
        </div>
    </div>
    <div class="card" style="border:1px solid #e2e8f0;">
        <p style="margin:0; color:#64748b; font-size:13px;">Candidates in process</p>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:6px;">
            <span style="font-size:36px; font-weight:700; color:#0f172a;">18</span>
            <span style="font-size:12px; color:#ea580c;">4 awaiting feedback</span>
        </div>
    </div>
    <div class="card" style="border:1px solid #e2e8f0;">
        <p style="margin:0; color:#64748b; font-size:13px;">Pending approvals</p>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:6px;">
            <span style="font-size:36px; font-weight:700; color:#0f172a;">3</span>
            <span style="font-size:12px; color:#0891b2;">Send reminder</span>
        </div>
    </div>
    <div class="card" style="border:1px dashed #fde68a; background:#fffbeb;">
        <p style="margin:0; font-weight:600;">Weekly objective</p>
        <p style="margin:6px 0 12px; color:#92400e;">Deliver 2 shortlists and close 1 senior role.</p>
        <a href="{{ route('agent.dashboard', ['tab' => 'jobs']) }}" class="btn-primary" style="width:fit-content;">View pipeline</a>
    </div>
</div>

<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:22px;">
    <div class="card" style="border:1px solid #e2e8f0;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
            <h3 class="primary-text" style="margin:0;">Actionable queue</h3>
            <span style="font-size:12px; color:#94a3b8;">Today</span>
        </div>
        <div style="display:grid; gap:12px;">
            <div style="border:1px dashed #e5e7eb; border-radius:12px; padding:14px;">
                <strong>Submit shortlist to Alpha Holdings</strong>
                <p style="margin:6px 0 0; color:#6b7280;">Head of Sales • Dubai</p>
                <a href="{{ route('agent.dashboard', ['tab' => 'jobs']) }}" style="font-size:13px;">Open job →</a>
            </div>
            <div style="border:1px dashed #e5e7eb; border-radius:12px; padding:14px;">
                <strong>Follow-up on 3 interviews</strong>
                <p style="margin:6px 0 0; color:#6b7280;">Fintech client • Remote</p>
                <span style="font-size:13px; color:#ea580c;">Waiting 2 days</span>
            </div>
            <div style="border:1px dashed #e5e7eb; border-radius:12px; padding:14px;">
                <strong>Refresh paused roles</strong>
                <p style="margin:6px 0 0; color:#6b7280;">2 opportunities ready for relaunch</p>
            </div>
        </div>
    </div>

    <div class="card" style="border:1px solid #e2e8f0;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
            <h3 class="primary-text" style="margin:0;">Quick actions</h3>
            <span style="font-size:12px; color:#94a3b8;">Shortcuts</span>
        </div>
        <div style="display:grid; gap:12px;">
            <a href="{{ route('agent.jobs.create') }}" class="card" style="border:1px dashed #c7d2fe; background:#eef2ff; text-decoration:none; color:inherit;">
                <strong>Draft new brief</strong>
                <span style="font-size:13px; color:#475569;">For your latest client</span>
            </a>
            <a href="{{ route('agent.dashboard', ['tab' => 'jobs']) }}" class="card" style="border:1px dashed #bbf7d0; background:#ecfdf5; text-decoration:none; color:inherit;">
                <strong>Manage open roles</strong>
                <span style="font-size:13px; color:#047857;">Edit or pause listings</span>
            </a>
            <a href="{{ route('agent.dashboard', ['tab' => 'profile']) }}" class="card" style="border:1px dashed #fecdd3; background:#fff1f2; text-decoration:none; color:inherit;">
                <strong>Update agency profile</strong>
                <span style="font-size:13px; color:#be123c;">Contact + branding</span>
            </a>
        </div>
    </div>
</div>

