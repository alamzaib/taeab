<div class="card">
    <div class="card-header">
        <h3 class="card-title">reCAPTCHA v3 Settings</h3>
    </div>
    <form method="POST" action="{{ route('admin.settings.recaptcha.update') }}">
        @csrf
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group">
                <label>
                    <input type="checkbox" name="recaptcha_enabled" value="1" {{ ($settings['recaptcha_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                    Enable reCAPTCHA v3
                </label>
                <small class="form-text text-muted">Enable this to protect login, registration, and contact forms from spam and abuse.</small>
            </div>

            <div class="form-group">
                <label for="recaptcha_site_key">Site Key (Public Key) <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('recaptcha_site_key') is-invalid @enderror" 
                       id="recaptcha_site_key" name="recaptcha_site_key" 
                       value="{{ old('recaptcha_site_key', $settings['recaptcha_site_key'] ?? '') }}" 
                       placeholder="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI">
                <small class="form-text text-muted">Get your keys from <a href="https://www.google.com/recaptcha/admin" target="_blank">Google reCAPTCHA</a></small>
                @error('recaptcha_site_key')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="recaptcha_secret_key">Secret Key (Private Key) <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('recaptcha_secret_key') is-invalid @enderror" 
                       id="recaptcha_secret_key" name="recaptcha_secret_key" 
                       value="{{ old('recaptcha_secret_key', $settings['recaptcha_secret_key'] ?? '') }}" 
                       placeholder="6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe">
                <small class="form-text text-muted">Keep this key secret. Never expose it in client-side code.</small>
                @error('recaptcha_secret_key')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="alert alert-info">
                <strong>How to get reCAPTCHA keys:</strong>
                <ol style="margin: 10px 0 0 20px; padding: 0;">
                    <li>Visit <a href="https://www.google.com/recaptcha/admin" target="_blank">Google reCAPTCHA Admin</a></li>
                    <li>Click "+" to create a new site</li>
                    <li>Select "reCAPTCHA v3"</li>
                    <li>Add your domain(s)</li>
                    <li>Copy the Site Key and Secret Key</li>
                    <li>Paste them above and save</li>
                </ol>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Settings</button>
        </div>
    </form>
</div>

