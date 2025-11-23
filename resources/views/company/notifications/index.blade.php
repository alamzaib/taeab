@extends('layouts.company-dashboard')

@section('title', 'Notifications - Job Portal UAE')

@section('dashboard-content')
<div style="margin-bottom:24px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <h1 class="primary-text" style="margin:0; font-size:28px;">Notifications</h1>
        @if($notifications->where('is_read', false)->count() > 0)
            <form action="{{ route('company.notifications.read-all') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn-secondary">Mark All as Read</button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card" style="border:1px solid #e2e8f0;">
        <div class="card-body" style="padding:0;">
            @forelse($notifications as $notification)
                <div style="padding:20px; border-bottom:1px solid #e2e8f0; {{ !$notification->is_read ? 'background:#f0f9ff;' : '' }}">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:16px;">
                        <div style="flex:1;">
                            <div style="display:flex; align-items:center; gap:12px; margin-bottom:8px;">
                                @if(!$notification->is_read)
                                    <span style="display:inline-block; width:8px; height:8px; background:#235181; border-radius:50%;"></span>
                                @endif
                                <strong style="color:#1f2937; font-size:16px;">{{ $notification->title }}</strong>
                            </div>
                            <p style="margin:0 0 8px 0; color:#4b5563; line-height:1.6;">{{ $notification->message }}</p>
                            <div style="display:flex; gap:16px; font-size:13px; color:#6b7280;">
                                <span>{{ $notification->created_at->diffForHumans() }}</span>
                                @if($notification->jobApplication)
                                    <a href="{{ route('company.applications.show', $notification->jobApplication) }}" style="color:#235181; text-decoration:none;">View Application</a>
                                @endif
                            </div>
                        </div>
                        @if(!$notification->is_read)
                            <form action="{{ route('company.notifications.read', $notification) }}" method="POST" style="display:inline;" class="mark-read-form">
                                @csrf
                                <button type="submit" class="btn btn-sm mark-read-btn" style="background:#f3f4f6; border:1px solid #d1d5db; padding:6px 12px; border-radius:6px; font-size:12px;">Mark as Read</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div style="padding:40px; text-align:center; color:#6b7280;">
                    <p style="margin:0; font-size:16px;">No notifications yet.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div style="margin-top:24px;">
        {{ $notifications->links() }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle mark as read with AJAX
        document.querySelectorAll('.mark-read-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const button = this.querySelector('.mark-read-btn');
                const notificationDiv = this.closest('div[style*="padding:20px"]');
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the mark as read button
                        this.remove();
                        
                        // Remove unread indicator
                        const indicator = notificationDiv.querySelector('span[style*="width:8px"]');
                        if (indicator) indicator.remove();
                        
                        // Remove background highlight
                        notificationDiv.style.background = '';
                        
                        // Update header counter
                        updateNotificationCounter();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Fallback to normal form submission
                    this.submit();
                });
            });
        });

        // Handle mark all as read
        const markAllForm = document.querySelector('form[action*="read-all"]');
        if (markAllForm) {
            markAllForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.submit();
                });
            });
        }

        // Function to update notification counter
        function updateNotificationCounter() {
            fetch('{{ route("company.notifications.count") }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (window.updateNotificationCount) {
                    window.updateNotificationCount(data.count);
                }
                // Also update localStorage for cross-tab communication
                localStorage.setItem('notificationCount', data.count);
            });
        }

        // Update counter on page load
        updateNotificationCounter();
    });
</script>
@endsection

