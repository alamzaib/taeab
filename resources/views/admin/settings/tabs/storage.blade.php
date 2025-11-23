<div class="card">
    <div class="card-header">
        <h3 class="card-title">Storage Settings</h3>
    </div>
    <form method="POST" action="{{ route('admin.settings.storage.update') }}">
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
                <label for="storage_driver">Storage Driver <span class="text-danger">*</span></label>
                <select class="form-control @error('storage_driver') is-invalid @enderror" 
                        id="storage_driver" name="storage_driver" required>
                    <option value="local" {{ ($settings['storage_driver'] ?? 'local') === 'local' ? 'selected' : '' }}>Local Storage</option>
                    <option value="s3" {{ ($settings['storage_driver'] ?? 'local') === 's3' ? 'selected' : '' }}>Amazon S3</option>
                </select>
                <small class="form-text text-muted">Choose where to store uploaded files (photos, CVs, resumes, etc.)</small>
                @error('storage_driver')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div id="s3-settings" style="display: {{ ($settings['storage_driver'] ?? 'local') === 's3' ? 'block' : 'none' }};">
                <h4 class="mt-4 mb-3">AWS S3 Configuration</h4>

                <div class="form-group">
                    <label for="aws_access_key_id">AWS Access Key ID <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('aws_access_key_id') is-invalid @enderror" 
                           id="aws_access_key_id" name="aws_access_key_id" 
                           value="{{ old('aws_access_key_id', $settings['aws_access_key_id'] ?? '') }}" 
                           placeholder="AKIAIOSFODNN7EXAMPLE">
                    @error('aws_access_key_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="aws_secret_access_key">AWS Secret Access Key <span class="text-danger">*</span></label>
                    <input type="password" class="form-control @error('aws_secret_access_key') is-invalid @enderror" 
                           id="aws_secret_access_key" name="aws_secret_access_key" 
                           value="{{ old('aws_secret_access_key', $settings['aws_secret_access_key'] ?? '') }}" 
                           placeholder="wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY">
                    <small class="form-text text-muted">Keep this key secret. Never expose it in client-side code.</small>
                    @error('aws_secret_access_key')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="aws_default_region">AWS Default Region <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('aws_default_region') is-invalid @enderror" 
                           id="aws_default_region" name="aws_default_region" 
                           value="{{ old('aws_default_region', $settings['aws_default_region'] ?? 'us-east-1') }}" 
                           placeholder="us-east-1">
                    <small class="form-text text-muted">AWS region where your S3 bucket is located (e.g., us-east-1, eu-west-1)</small>
                    @error('aws_default_region')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="aws_bucket">AWS Bucket Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('aws_bucket') is-invalid @enderror" 
                           id="aws_bucket" name="aws_bucket" 
                           value="{{ old('aws_bucket', $settings['aws_bucket'] ?? '') }}" 
                           placeholder="my-bucket-name">
                    @error('aws_bucket')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="aws_url">AWS URL (Optional)</label>
                    <input type="url" class="form-control @error('aws_url') is-invalid @enderror" 
                           id="aws_url" name="aws_url" 
                           value="{{ old('aws_url', $settings['aws_url'] ?? '') }}" 
                           placeholder="https://s3.amazonaws.com">
                    <small class="form-text text-muted">Custom URL for S3 (leave empty for default)</small>
                    @error('aws_url')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="aws_endpoint">AWS Endpoint (Optional)</label>
                    <input type="url" class="form-control @error('aws_endpoint') is-invalid @enderror" 
                           id="aws_endpoint" name="aws_endpoint" 
                           value="{{ old('aws_endpoint', $settings['aws_endpoint'] ?? '') }}" 
                           placeholder="https://s3.amazonaws.com">
                    <small class="form-text text-muted">Custom endpoint for S3-compatible services (e.g., DigitalOcean Spaces, MinIO)</small>
                    @error('aws_endpoint')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="alert alert-info">
                    <strong>How to set up AWS S3:</strong>
                    <ol style="margin: 10px 0 0 20px; padding: 0;">
                        <li>Create an AWS account and go to S3 service</li>
                        <li>Create a new S3 bucket</li>
                        <li>Go to IAM and create a user with S3 access</li>
                        <li>Generate Access Key ID and Secret Access Key</li>
                        <li>Configure bucket permissions (public read for files if needed)</li>
                        <li>Enter the credentials above and save</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Settings</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const storageDriver = document.getElementById('storage_driver');
    const s3Settings = document.getElementById('s3-settings');
    
    if (storageDriver && s3Settings) {
        storageDriver.addEventListener('change', function() {
            if (this.value === 's3') {
                s3Settings.style.display = 'block';
                // Make S3 fields required
                document.querySelectorAll('#s3-settings input[required]').forEach(function(input) {
                    input.setAttribute('required', 'required');
                });
            } else {
                s3Settings.style.display = 'none';
                // Remove required from S3 fields
                document.querySelectorAll('#s3-settings input[required]').forEach(function(input) {
                    input.removeAttribute('required');
                });
            }
        });
    }
});
</script>
@endpush

