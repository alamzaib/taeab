@extends('layouts.app')

@section('title', 'Agent Dashboard - Job Portal UAE')

@section('content')
<div class="container">
    <div class="card" style="padding:0; overflow:hidden;">
        <div style="background:linear-gradient(135deg,#0f172a,#1d4a73); padding:32px; color:white;">
            <div style="display:flex; flex-wrap:wrap; gap:24px; align-items:flex-end;">
                <div style="flex:1 1 320px;">
                    <p style="margin:0; text-transform:uppercase; letter-spacing:.08em; font-size:11px; opacity:.8;">Agency cockpit</p>
                    <h1 style="margin:10px 0 12px; font-size:34px;">Welcome back, {{ $agent->name }}</h1>
                    <p style="max-width:520px; color:rgba(255,255,255,.78);">Stay on top of every client search, accelerate interviews, and collaborate with employers without leaving your dashboard.</p>
                    <div style="margin-top:18px; display:flex; gap:12px; flex-wrap:wrap;">
                        <a href="{{ route('agent.jobs.create') }}" class="btn-primary" style="background:white;color:#0f172a;">Create brief</a>
                        <a href="{{ route('agent.jobs.index') }}" class="btn btn-light" style="background:rgba(255,255,255,.12); color:white; border:1px solid rgba(255,255,255,.35);">Manage pipeline</a>
                    </div>
                    <div style="margin-top:14px; font-size:13px; letter-spacing:.08em; text-transform:uppercase;">
                        Profile ID: <strong>{{ $agent->unique_code }}</strong>
                    </div>
                </div>
                <div style="flex:0 0 260px; background:rgba(15,23,42,.7); border-radius:18px; padding:20px;">
                    <p style="margin:0; text-transform:uppercase; letter-spacing:.1em; font-size:11px; color:#c7d2fe;">This week</p>
                    <div style="display:flex; justify-content:space-between; margin-top:14px;">
                        <div>
                            <strong style="display:block; font-size:28px;">{{ $stats['total_jobs'] }}</strong>
                            <span style="font-size:13px; color:#e2e8f0;">Jobs owned</span>
                        </div>
                        <div>
                            <strong style="display:block; font-size:28px;">{{ $stats['active_jobs'] }}</strong>
                            <span style="font-size:13px; color:#e2e8f0;">Live roles</span>
                        </div>
                    </div>
                    <div style="margin-top:16px;">
                        <div style="display:flex; justify-content:space-between; font-size:12px;">
                            <span>Client satisfaction</span>
                            <span>92%</span>
                        </div>
                        <div style="height:6px; border-radius:999px; background:rgba(255,255,255,.25); overflow:hidden; margin-top:6px;">
                            <div style="width:92%; background:#4ade80; height:100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="padding:32px; display:grid; gap:28px;">
            <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:18px;">
                <div class="card" style="border:1px solid #e2e8f0;">
                    <p style="margin:0; color:#64748b; font-size:13px;">Shortlists shared</p>
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:6px;">
                        <span style="font-size:34px; font-weight:700; color:#0f172a;">6</span>
                        <span style="font-size:12px; color:#16a34a;">+1 today</span>
                    </div>
                </div>
                <div class="card" style="border:1px solid #e2e8f0;">
                    <p style="margin:0; color:#64748b; font-size:13px;">Candidates in process</p>
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:6px;">
                        <span style="font-size:34px; font-weight:700; color:#0f172a;">18</span>
                        <span style="font-size:12px; color:#ea580c;">4 awaiting feedback</span>
                    </div>
                </div>
                <div class="card" style="border:1px solid #e2e8f0;">
                    <p style="margin:0; color:#64748b; font-size:13px;">Pending approvals</p>
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:6px;">
                        <span style="font-size:34px; font-weight:700; color:#0f172a;">3</span>
                        <span style="font-size:12px; color:#0891b2;">Send reminder</span>
                    </div>
                </div>
                <div class="card" style="border:1px dashed #fde68a; background:#fffbeb;">
                    <p style="margin:0; font-weight:600;">Weekly objective</p>
                    <p style="margin:6px 0 12px; color:#92400e;">Deliver 2 shortlists and close 1 senior role.</p>
                    <a href="{{ route('agent.jobs.index') }}" class="btn-primary" style="width:fit-content;">View pipeline</a>
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
                            <a href="{{ route('agent.jobs.index') }}" style="font-size:13px;">Open job →</a>
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
                        <a href="{{ route('agent.jobs.create') }}" class="card" style="border:1px dashed #c7d2fe; background:#eef2ff;">
                            <strong>Draft new brief</strong>
                            <span style="font-size:13px; color:#475569;">For your latest client</span>
                        </a>
                        <a href="{{ route('agent.jobs.index') }}" class="card" style="border:1px dashed #bbf7d0; background:#ecfdf5;">
                            <strong>Manage open roles</strong>
                            <span style="font-size:13px; color:#047857;">Edit or pause listings</span>
                        </a>
                        <a href="{{ route('agent.profile.edit') }}" class="card" style="border:1px dashed #fecdd3; background:#fff1f2;">
                            <strong>Update agency profile</strong>
                            <span style="font-size:13px; color:#be123c;">Contact + branding</span>
                        </a>
                        <a href="{{ route('agent.jobs.index') }}" class="card" style="border:1px dashed #bae6fd; background:#e0f2fe;">
                            <strong>Share reports</strong>
                            <span style="font-size:13px; color:#0c4a6e;">Export progress PDF</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

