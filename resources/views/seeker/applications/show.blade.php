@extends('layouts.app')

@section('title', 'Conversation - Seeker Dashboard')

@section('content')
<div class="container">
    <div style="margin-bottom:15px; display:flex; gap:10px; flex-wrap:wrap;">
        <a href="{{ route('seeker.dashboard') }}" class="btn btn-secondary">← Back to dashboard</a>
        <a href="{{ route('seeker.applications.index') }}" class="btn btn-secondary">← Back to applications</a>
    </div>
    <div class="card">
        <div class="card-header">
            <div>
                <p class="text-muted mb-1">Company</p>
                <h2 class="primary-text mb-1">{{ $application->job->company->company_name ?? $application->job->company->name }}</h2>
                <p class="mb-0">Role: <strong>{{ $application->job->title }}</strong></p>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="conversation-box mb-4" style="max-height:420px; overflow-y:auto; border:1px solid #e5e7eb; border-radius:12px; padding:20px; background:#f9fafb;">
                @forelse($messages as $message)
                    <div style="margin-bottom:18px;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                            <strong>{{ $message->sender_type === 'seeker' ? $seeker->name : ($application->job->company->company_name ?? $application->job->company->name) }}</strong>
                            <small class="text-muted">{{ $message->created_at->format('M d, H:i') }}</small>
                        </div>
                        <div style="background:{{ $message->sender_type === 'seeker' ? '#235181' : 'white' }}; color:{{ $message->sender_type === 'seeker' ? 'white' : '#1f2937' }}; padding:12px 16px; border-radius:12px;">
                            {{ $message->message }}
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">No messages yet. Send a quick update to the company.</p>
                @endforelse
            </div>

            <form action="{{ route('seeker.applications.messages.store', $application) }}" method="POST">
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
@endsection

