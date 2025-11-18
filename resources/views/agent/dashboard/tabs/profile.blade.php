<h2 class="primary-text" style="margin:0 0 24px; font-size:28px;">My Profile</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="margin-bottom:20px; padding:12px 16px; border:1px dashed #cbd5f5; border-radius:10px; background:#eef2ff;">
    <strong>Profile ID:</strong> {{ $agent->unique_code }}
</div>

<form action="{{ route('agent.profile.update') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $agent->name) }}" required>
    </div>

    <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $agent->email) }}" required>
    </div>

    <div class="form-group">
        <label for="phone">Phone Number</label>
        <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $agent->phone) }}" required>
    </div>

    <div class="form-group">
        <label for="company_name">Company Name (optional)</label>
        <input type="text" id="company_name" name="company_name" class="form-control" value="{{ old('company_name', $agent->company_name) }}">
    </div>

    <h3 class="primary-text" style="margin:20px 0 10px;">Account Security</h3>
    <div class="form-group">
        <label for="password">New Password (optional)</label>
        <input type="password" id="password" name="password" class="form-control">
    </div>

    <div class="form-group">
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
    </div>

    <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
        <button type="submit" class="btn-primary">Save Changes</button>
    </div>
</form>

