@php
    $hero = [
        'title' => 'Post a New Job',
        'description' => 'Share the role’s story and reach the right talent.',
        'actions' => [
            ['label' => 'Manage jobs', 'route' => route('company.dashboard', ['tab' => 'jobs']), 'variant' => 'ghost'],
            ['label' => 'Applicants inbox', 'route' => route('company.dashboard', ['tab' => 'applications']), 'variant' => 'ghost'],
        ],
        'stats' => ['active_jobs' => $company->jobs()->where('status', 'published')->count()],
    ];
    $currentTab = 'jobs';
@endphp

@extends('layouts.company-dashboard')

@section('title', 'Post Job - Company Dashboard')

@section('dashboard-content')
    <form action="{{ route('company.jobs.store') }}" method="POST">
        <div class="card-body" style="padding:0;">
            @include('company.jobs.form', ['job' => $job])
        </div>
        <div class="card-footer" style="display:flex; gap:10px; padding:0; margin-top:20px;">
            <button type="submit" class="btn-primary">Publish Job</button>
            <a href="{{ route('company.dashboard', ['tab' => 'jobs']) }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
@endsection

