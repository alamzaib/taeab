<div class="card">
    <div class="card-header">
        <h3 class="card-title">SMTP Settings</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.settings.smtp.update') }}">
            @csrf
            <div class="form-group">
                <label for="smtp_host">SMTP Host</label>
                <input type="text" name="smtp_host" id="smtp_host" class="form-control" value="{{ $settings['smtp_host'] ?? '' }}" placeholder="smtp.gmail.com">
                <small class="form-text">Your SMTP server hostname</small>
            </div>

            <div class="form-group">
                <label for="smtp_port">SMTP Port</label>
                <input type="number" name="smtp_port" id="smtp_port" class="form-control" value="{{ $settings['smtp_port'] ?? '587' }}" placeholder="587">
                <small class="form-text">Common ports: 587 (TLS), 465 (SSL), 25</small>
            </div>

            <div class="form-group">
                <label for="smtp_username">SMTP Username</label>
                <input type="text" name="smtp_username" id="smtp_username" class="form-control" value="{{ $settings['smtp_username'] ?? '' }}" placeholder="your-email@gmail.com">
                <small class="form-text">Your SMTP username (usually your email address)</small>
            </div>

            <div class="form-group">
                <label for="smtp_password">SMTP Password</label>
                <input type="password" name="smtp_password" id="smtp_password" class="form-control" value="{{ $settings['smtp_password'] ?? '' }}" placeholder="Your SMTP password or app password">
                <small class="form-text">Your SMTP password or app-specific password</small>
            </div>

            <div class="form-group">
                <label for="smtp_encryption">Encryption</label>
                <select name="smtp_encryption" id="smtp_encryption" class="form-control">
                    <option value="tls" {{ ($settings['smtp_encryption'] ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS</option>
                    <option value="ssl" {{ ($settings['smtp_encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                    <option value="null" {{ ($settings['smtp_encryption'] ?? '') === 'null' ? 'selected' : '' }}>None</option>
                </select>
                <small class="form-text">Encryption type for SMTP connection</small>
            </div>

            <div class="form-group">
                <label for="smtp_from_address">From Email Address</label>
                <input type="email" name="smtp_from_address" id="smtp_from_address" class="form-control" value="{{ $settings['smtp_from_address'] ?? '' }}" placeholder="noreply@example.com">
                <small class="form-text">Email address that will appear as sender</small>
            </div>

            <div class="form-group">
                <label for="smtp_from_name">From Name</label>
                <input type="text" name="smtp_from_name" id="smtp_from_name" class="form-control" value="{{ $settings['smtp_from_name'] ?? '' }}" placeholder="Job Portal UAE">
                <small class="form-text">Name that will appear as sender</small>
            </div>

            <div style="margin-top: 24px;">
                <button type="submit" class="btn btn-primary">Save SMTP Settings</button>
            </div>
        </form>
    </div>
</div>

