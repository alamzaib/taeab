@php
    use Illuminate\Support\Facades\Storage;

    $heroTitle = $heroTitle ?? $company->company_name;
    $heroDescription = $heroDescription ?? 'Track active mandates, meet promising talent, and keep leadership in the loop from one streamlined workspace.';
    $heroActions = $heroActions ?? [
        ['label' => 'Post new job', 'route' => route('company.jobs.create'), 'variant' => 'primary'],
        ['label' => 'View applicants', 'route' => route('company.applications.index'), 'variant' => 'ghost'],
    ];
    $stats = $stats ?? [];
    $activeJobsCount = $stats['active_jobs'] ?? $company->jobs()->where('status', 'published')->count();
@endphp

<div style="position:relative; background:{{ $company->banner_path ? 'url('.Storage::disk('public')->url($company->banner_path).') center/cover' : 'linear-gradient(135deg,#0f4c75,#073046)' }}; padding:32px;">
    <div style="position:absolute; inset:0; background:linear-gradient(120deg, rgba(8,47,73,.95), rgba(15,76,117,.7));"></div>
    <div style="position:relative; z-index:2; display:flex; flex-wrap:wrap; gap:24px; align-items:flex-end; color:white;">
        <div style="flex:1 1 320px;">
            <p style="margin:0; text-transform:uppercase; font-size:11px; letter-spacing:.1em; opacity:.85;">Company Mission Control</p>
            <h1 style="margin:10px 0 12px; font-size:34px;">{{ $heroTitle }}</h1>
            <p style="max-width:520px; color:rgba(255,255,255,.85);">{{ $heroDescription }}</p>
            <div style="margin-top:18px; display:flex; gap:12px; flex-wrap:wrap;">
                @foreach($heroActions as $action)
                    @php
                        $isPrimary = ($action['variant'] ?? 'primary') === 'primary';
                    @endphp
                    <a href="{{ $action['route'] }}"
                        class="{{ $isPrimary ? 'btn-primary' : 'btn btn-light' }}"
                        style="{{ $isPrimary ? 'background:white;color:#0f4c75;' : 'background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.4);' }}">
                        {{ $action['label'] }}
                    </a>
                @endforeach
            </div>
            <div style="margin-top:14px; font-size:13px; letter-spacing:.08em; text-transform:uppercase;">
                Profile ID: <strong>{{ $company->unique_code }}</strong>
            </div>
        </div>
        <div style="flex:0 0 260px; background:rgba(15,23,42,.55); border-radius:18px; padding:20px;">
            <p style="margin:0; text-transform:uppercase; letter-spacing:.1em; font-size:11px; color:#bae6fd;">Hiring snapshot</p>
            <div style="display:flex; gap:16px; align-items:center; margin-top:14px;">
                <div style="width:78px; height:78px; border-radius:16px; overflow:hidden; background:white;">
                    @if($company->logo_path)
                        <img src="{{ Storage::disk('public')->url($company->logo_path) }}" alt="Logo" style="width:100%; height:100%; object-fit:contain;">
                    @else
                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#0f4c75;">Logo</div>
                    @endif
                </div>
                <div>
                    <strong style="display:block; font-size:22px;">{{ $company->company_name }}</strong>
                    <span style="font-size:13px; color:#cbd5f5;">{{ $company->industry ?? 'Set industry' }}</span>
                </div>
            </div>
            <div style="margin-top:16px;">
                <div style="display:flex; justify-content:space-between; font-size:12px; color:#e2e8f0;">
                    <span>Active jobs</span>
                    <span>{{ $activeJobsCount }}</span>
                </div>
                <div style="height:6px; border-radius:999px; background:rgba(255,255,255,.25); overflow:hidden; margin-top:6px;">
                    <div style="width:65%; background:#38bdf8; height:100%;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@once
    <style>
        .company-dashboard a {
            color: #0f4c75;
            text-decoration: none;
        }

        .company-dashboard a:hover {
            color: #0a3658;
        }
    </style>
@endonce

