@extends('layouts.app')

@section('title', 'Applicant Conversation - Company Dashboard')

@section('content')
<div class="container company-dashboard">
    <div class="card" style="padding:0; overflow:hidden;">
        @include('company.components.hero', [
            'heroTitle' => 'Applicant: ' . $application->seeker->name,
            'heroDescription' => 'You are viewing the conversation for ' . $application->job->title . '.',
            'heroActions' => [
                ['label' => 'All applicants', 'route' => route('company.applications.index'), 'variant' => 'ghost'],
                ['label' => 'Manage jobs', 'route' => route('company.jobs.index'), 'variant' => 'ghost'],
            ],
            'stats' => ['active_jobs' => $company->jobs()->where('status', 'published')->count()]
        ])
        <div style="display:flex; flex-wrap:wrap;">
            @include('company.components.sidebar')
            <div class="card-body" style="flex:1; padding:32px;">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="conversation-box mb-4" style="max-height: 420px; overflow-y: auto; border:1px solid #e5e7eb; border-radius:12px; padding:20px; background:#f9fafb;">
                @forelse($messages as $message)
                    <div style="margin-bottom:18px;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                            <strong>{{ $message->sender_type === 'company' ? $company->company_name : $application->seeker->name }}</strong>
                            <small class="text-muted">{{ $message->created_at->format('M d, H:i') }}</small>
                        </div>
                        <div style="background:{{ $message->sender_type === 'company' ? '#235181' : 'white' }}; color:{{ $message->sender_type === 'company' ? 'white' : '#1f2937' }}; padding:12px 16px; border-radius:12px;">
                            {{ $message->message }}
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">No messages yet. Break the ice with the applicant.</p>
                @endforelse
            </div>

            <form action="{{ route('company.applications.messages.store', $application) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="message" class="form-label">Send a message</label>
                    <textarea id="message" name="message" rows="4" class="form-control" required>{{ old('message') }}</textarea>
                    @error('message')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="text-end">
                    <button type="submit" class="btn-primary">Send Message</button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
@endsection

