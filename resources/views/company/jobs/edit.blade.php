@extends('layouts.app')

@section('title', 'Edit Job - Company Dashboard')

@section('content')
<div class="container company-dashboard">
    <div class="card" style="padding:0; overflow:hidden;">
        @include('company.components.hero', [
            'heroTitle' => 'Edit Job',
            'heroDescription' => 'Fine tune the listing for better candidate fit.',
            'heroActions' => [
                ['label' => 'Manage jobs', 'route' => route('company.jobs.index'), 'variant' => 'ghost'],
                ['label' => 'Applicants inbox', 'route' => route('company.applications.index'), 'variant' => 'ghost'],
            ],
            'stats' => ['active_jobs' => $company->jobs()->where('status', 'published')->count()]
        ])
        <div style="display:flex; flex-wrap:wrap;">
            @include('company.components.sidebar')
            <div style="flex:1; padding:32px;">
                <form action="{{ route('company.jobs.update', $job) }}" method="POST">
                    @method('PUT')
                    <div class="card-body" style="padding:0;">
                        @include('company.jobs.form', ['job' => $job])
                    </div>
                    <div class="card-footer" style="display:flex; gap:10px; padding:0; margin-top:20px;">
                        <button type="submit" class="btn-primary">Save Changes</button>
                        <a href="{{ route('company.jobs.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

