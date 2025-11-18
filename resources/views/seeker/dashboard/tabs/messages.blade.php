@php
    $applicationsWithMessages = $seeker->applications()
        ->whereHas('messages')
        ->with(['job.company', 'messages' => function($query) {
            $query->latest()->limit(1);
        }])
        ->latest()
        ->paginate(10);
    
    $allApplications = $seeker->applications()
        ->with('job.company')
        ->latest()
        ->get();
@endphp

<h2 class="primary-text" style="margin:0 0 24px; font-size:28px;">Messages</h2>
<p style="color:#64748b; margin-bottom:24px;">Communicate with employers about your applications.</p>

@if($applicationsWithMessages->isEmpty() && $allApplications->isEmpty())
    <div class="card" style="border:1px solid #e2e8f0; padding:40px; text-align:center;">
        <p style="color:#94a3b8; margin-bottom:16px;">No applications yet.</p>
        <a href="{{ route('jobs.index') }}" class="btn btn-primary">Browse Jobs</a>
    </div>
@else
    <div style="display:grid; gap:16px;">
        @foreach($allApplications as $application)
            @php
                $latestMessage = $application->messages()->latest()->first();
                $hasMessages = $application->messages()->exists();
            @endphp
            <div class="card" style="border:1px solid #e2e8f0; padding:20px;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:16px; flex-wrap:wrap;">
                    <div style="flex:1;">
                        <h3 style="margin:0 0 8px; font-size:18px;">
                            <a href="{{ route('jobs.show', $application->job->slug) }}" style="color:#0f172a; text-decoration:none;">
                                {{ $application->job->title }}
                            </a>
                        </h3>
                        <p style="margin:0 0 8px; color:#64748b;">
                            {{ $application->job->company->company_name ?? 'Company' }}
                        </p>
                        @if($latestMessage)
                            <p style="margin:0; color:#475569; font-size:14px;">
                                {{ \Illuminate\Support\Str::limit($latestMessage->message, 150) }}
                            </p>
                            <p style="margin:8px 0 0; color:#94a3b8; font-size:12px;">
                                {{ $latestMessage->created_at->diffForHumans() }}
                            </p>
                        @else
                            <p style="margin:0; color:#94a3b8; font-size:14px; font-style:italic;">
                                No messages yet. Start a conversation!
                            </p>
                        @endif
                    </div>
                    <div>
                        <button class="btn btn-primary open-chat-btn" data-application-id="{{ $application->id }}" data-job-title="{{ $application->job->title }}" data-company-name="{{ $application->job->company->company_name ?? 'Company' }}">
                            {{ $hasMessages ? 'Open Conversation' : 'Start Conversation' }}
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @if($applicationsWithMessages->hasPages())
        <div class="mt-3">
            {{ $applicationsWithMessages->links('components.pagination') }}
        </div>
    @endif
@endif

<!-- Chat Modal -->
<div id="chatModal" class="chat-modal" style="display:none;">
    <div class="chat-modal-overlay"></div>
    <div class="chat-modal-container">
        <div class="chat-modal-header">
            <div>
                <h3 id="chatJobTitle" class="primary-text" style="margin:0; font-size:20px;"></h3>
                <p id="chatCompanyName" style="margin:4px 0 0; color:#64748b; font-size:14px;"></p>
            </div>
            <button class="chat-close-btn" onclick="closeChatModal()">×</button>
        </div>
        <div class="chat-modal-body" id="chatMessages">
            <div style="text-align:center; padding:40px; color:#94a3b8;">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
        <div class="chat-modal-footer">
            <form id="chatMessageForm" onsubmit="sendChatMessage(event)">
                @csrf
                <div style="display:flex; gap:10px;">
                    <textarea id="chatMessageInput" rows="3" class="form-control" placeholder="Type your message..." required style="resize:none;"></textarea>
                    <button type="submit" class="btn-primary" style="align-self:flex-end; padding:12px 24px;">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.chat-modal {
    position:fixed;
    top:0;
    left:0;
    right:0;
    bottom:0;
    z-index:9999;
}
.chat-modal-overlay {
    position:absolute;
    inset:0;
    background:rgba(0,0,0,0.5);
    backdrop-filter:blur(4px);
    animation:fadeIn 0.3s ease;
}
.chat-modal-container {
    position:absolute;
    right:0;
    top:0;
    bottom:0;
    width:100%;
    max-width:600px;
    background:white;
    display:flex;
    flex-direction:column;
    box-shadow:-4px 0 20px rgba(0,0,0,0.15);
    animation:slideInRight 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}
.chat-modal-header {
    padding:20px 24px;
    border-bottom:1px solid #e2e8f0;
    display:flex;
    justify-content:space-between;
    align-items:center;
    background:#f8fafc;
}
.chat-close-btn {
    background:none;
    border:none;
    font-size:32px;
    color:#64748b;
    cursor:pointer;
    padding:0;
    width:36px;
    height:36px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:8px;
    transition:all 0.2s;
}
.chat-close-btn:hover {
    background:#e2e8f0;
    color:#0f172a;
}
.chat-modal-body {
    flex:1;
    overflow-y:auto;
    padding:24px;
    background:#f9fafb;
}
.chat-modal-footer {
    padding:20px 24px;
    border-top:1px solid #e2e8f0;
    background:white;
}
@keyframes fadeIn {
    from { opacity:0; }
    to { opacity:1; }
}
@keyframes slideInRight {
    from {
        transform:translateX(100%);
        opacity:0;
    }
    to {
        transform:translateX(0);
        opacity:1;
    }
}
.chat-message {
    margin-bottom:18px;
    animation:messageSlideIn 0.3s ease;
}
@keyframes messageSlideIn {
    from {
        opacity:0;
        transform:translateY(10px);
    }
    to {
        opacity:1;
        transform:translateY(0);
    }
}
.chat-message-seeker {
    display:flex;
    justify-content:flex-end;
}
.chat-message-company {
    display:flex;
    justify-content:flex-start;
}
.chat-message-bubble {
    max-width:75%;
    padding:12px 16px;
    border-radius:16px;
    word-wrap:break-word;
}
.chat-message-seeker .chat-message-bubble {
    background:#235181;
    color:white;
    border-bottom-right-radius:4px;
}
.chat-message-company .chat-message-bubble {
    background:white;
    color:#1f2937;
    border-bottom-left-radius:4px;
    border:1px solid #e2e8f0;
}
.chat-message-time {
    font-size:11px;
    color:#94a3b8;
    margin-top:4px;
    text-align:right;
}
.chat-message-company .chat-message-time {
    text-align:left;
}
</style>

<script>
let currentApplicationId = null;

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.open-chat-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const applicationId = this.getAttribute('data-application-id');
            const jobTitle = this.getAttribute('data-job-title');
            const companyName = this.getAttribute('data-company-name');
            openChatModal(applicationId, jobTitle, companyName);
        });
    });
});

function openChatModal(applicationId, jobTitle, companyName) {
    currentApplicationId = applicationId;
    document.getElementById('chatJobTitle').textContent = jobTitle;
    document.getElementById('chatCompanyName').textContent = companyName;
    document.getElementById('chatModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
    
    loadChatMessages(applicationId);
    
    // Auto-scroll to bottom
    setTimeout(() => {
        const chatBody = document.getElementById('chatMessages');
        chatBody.scrollTop = chatBody.scrollHeight;
    }, 100);
}

function closeChatModal() {
    document.getElementById('chatModal').style.display = 'none';
    document.body.style.overflow = '';
    currentApplicationId = null;
    document.getElementById('chatMessages').innerHTML = '<div style="text-align:center; padding:40px; color:#94a3b8;"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>';
    document.getElementById('chatMessageInput').value = '';
}

function loadChatMessages(applicationId) {
    fetch(`/seeker/applications/${applicationId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const chatBody = document.getElementById('chatMessages');
        if (data.messages && data.messages.length > 0) {
            chatBody.innerHTML = data.messages.map(msg => {
                const isSeeker = msg.sender_type === 'seeker';
                const time = new Date(msg.created_at).toLocaleString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                return `
                    <div class="chat-message ${isSeeker ? 'chat-message-seeker' : 'chat-message-company'}">
                        <div>
                            <div class="chat-message-bubble">${escapeHtml(msg.message)}</div>
                            <div class="chat-message-time">${time}</div>
                        </div>
                    </div>
                `;
            }).join('');
        } else {
            chatBody.innerHTML = '<p style="text-align:center; color:#94a3b8; padding:40px;">No messages yet. Start the conversation!</p>';
        }
        
        // Auto-scroll to bottom
        setTimeout(() => {
            chatBody.scrollTop = chatBody.scrollHeight;
        }, 50);
    })
    .catch(error => {
        console.error('Error loading messages:', error);
        document.getElementById('chatMessages').innerHTML = '<p style="text-align:center; color:#dc2626; padding:40px;">Error loading messages. Please refresh the page.</p>';
    });
}

function sendChatMessage(event) {
    event.preventDefault();
    
    if (!currentApplicationId) return;
    
    const messageInput = document.getElementById('chatMessageInput');
    const message = messageInput.value.trim();
    
    if (!message) return;
    
    const formData = new FormData();
    formData.append('message', message);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}');
    
    fetch(`/seeker/applications/${currentApplicationId}/messages`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            messageInput.value = '';
            loadChatMessages(currentApplicationId);
        } else {
            alert(data.message || 'Failed to send message');
        }
    })
    .catch(error => {
        console.error('Error sending message:', error);
        alert('Failed to send message. Please try again.');
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Close modal when clicking overlay
document.addEventListener('click', function(e) {
    const modal = document.getElementById('chatModal');
    if (e.target.classList.contains('chat-modal-overlay')) {
        closeChatModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.getElementById('chatModal').style.display === 'block') {
        closeChatModal();
    }
});
</script>

