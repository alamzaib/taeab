@php
    $hero = [
        'title' => $company->company_name,
        'description' => 'Track active mandates, meet promising talent, and keep leadership in the loop from one streamlined workspace.',
        'actions' => [
            ['label' => 'Post new job', 'route' => route('company.jobs.create'), 'variant' => 'primary'],
            ['label' => 'View applicants', 'route' => route('company.dashboard', ['tab' => 'applications']), 'variant' => 'ghost'],
        ],
        'stats' => $stats,
    ];
    $activeTab = request()->get('tab');
    if (!$activeTab && request()->has('job')) {
        $activeTab = 'applications';
    }
    $activeTab = $activeTab ?? 'overview';
@endphp

@extends('layouts.company-dashboard')

@section('title', 'Company Dashboard - Job Portal UAE')

@section('dashboard-content')
    @if($activeTab === 'overview')
        @include('company.dashboard.tabs.overview')
    @elseif($activeTab === 'profile')
        @include('company.dashboard.tabs.profile')
    @elseif($activeTab === 'jobs')
        @include('company.dashboard.tabs.jobs')
    @elseif($activeTab === 'applications')
        @include('company.dashboard.tabs.applications')
    @elseif($activeTab === 'messages')
        @include('company.dashboard.tabs.messages')
    @endif
@endsection
