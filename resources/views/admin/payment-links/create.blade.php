@extends('admin.layouts.app')

@section('title', 'Generate Payment Link')
@section('page-title', 'Generate Payment Link')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.payment-links.index') }}">Payment Links</a></li>
    <li class="breadcrumb-item active">Generate</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Generate Payment Link</h3>
    </div>
    <form action="{{ route('admin.payment-links.store') }}" method="POST">
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
                <label for="recipient_type">Recipient Type <span class="text-danger">*</span></label>
                <select class="form-control @error('recipient_type') is-invalid @enderror" id="recipient_type" name="recipient_type" required>
                    <option value="">Select Type</option>
                    <option value="seeker" {{ old('recipient_type') === 'seeker' ? 'selected' : '' }}>Job Seeker</option>
                    <option value="company" {{ old('recipient_type') === 'company' ? 'selected' : '' }}>Company</option>
                </select>
                @error('recipient_type')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="recipient_id">Recipient <span class="text-danger">*</span></label>
                <select class="form-control @error('recipient_id') is-invalid @enderror" id="recipient_id" name="recipient_id" required disabled>
                    <option value="">Please select recipient type first</option>
                </select>
                <small class="form-text text-muted">Select recipient type above to load recipients</small>
                @error('recipient_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="amount">Amount <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="number" step="0.01" min="0.01" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount') }}" required>
                    <select class="form-control" id="currency" name="currency" style="max-width: 100px;">
                        <option value="USD" {{ old('currency', 'USD') === 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="EUR" {{ old('currency') === 'EUR' ? 'selected' : '' }}>EUR</option>
                        <option value="GBP" {{ old('currency') === 'GBP' ? 'selected' : '' }}>GBP</option>
                        <option value="AED" {{ old('currency') === 'AED' ? 'selected' : '' }}>AED</option>
                    </select>
                </div>
                @error('amount')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="expires_in_days">Expires In (Days)</label>
                <input type="number" min="1" max="365" class="form-control @error('expires_in_days') is-invalid @enderror" id="expires_in_days" name="expires_in_days" value="{{ old('expires_in_days', 30) }}">
                <small class="form-text text-muted">Default: 30 days</small>
                @error('expires_in_days')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Generate Payment Link</button>
            <a href="{{ route('admin.payment-links.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
(function() {
    'use strict';
    
    document.addEventListener('DOMContentLoaded', function() {
        const recipientType = document.getElementById('recipient_type');
        const recipientId = document.getElementById('recipient_id');
        
        if (!recipientType || !recipientId) {
            console.error('Required elements not found');
            return;
        }
        
        // Get data from Blade
        const seekersData = @json($seekers ?? []);
        const companiesData = @json($companies ?? []);

        function populateRecipients() {
            // Clear existing options
            recipientId.innerHTML = '<option value="">Loading...</option>';
            recipientId.disabled = true;
            
            const selectedType = recipientType.value;
            
            if (!selectedType) {
                recipientId.innerHTML = '<option value="">Please select recipient type first</option>';
                recipientId.disabled = true;
                return;
            }
            
            // Try to use local data first
            let data = null;
            if (selectedType === 'seeker' && Array.isArray(seekersData) && seekersData.length > 0) {
                data = seekersData;
            } else if (selectedType === 'company' && Array.isArray(companiesData) && companiesData.length > 0) {
                data = companiesData;
            }
            
            if (data) {
                // Use local data
                recipientId.innerHTML = '<option value="">Select Recipient</option>';
                recipientId.disabled = false;
                
                data.forEach(function(item) {
                    if (item && item.id) {
                        const option = document.createElement('option');
                        option.value = item.id;
                        const displayName = item.name || item.company_name || 'N/A';
                        const email = item.email || '';
                        option.textContent = displayName + (email ? ' (' + email + ')' : '');
                        recipientId.appendChild(option);
                    }
                });
            } else {
                // Load via AJAX
                fetch('{{ route("admin.payment-links.recipients") }}?type=' + selectedType)
                    .then(response => response.json())
                    .then(data => {
                        recipientId.innerHTML = '<option value="">Select Recipient</option>';
                        recipientId.disabled = false;
                        
                        if (Array.isArray(data) && data.length > 0) {
                            data.forEach(function(item) {
                                const option = document.createElement('option');
                                option.value = item.id;
                                const displayName = item.name || 'N/A';
                                const email = item.email || '';
                                option.textContent = displayName + (email ? ' (' + email + ')' : '');
                                recipientId.appendChild(option);
                            });
                        } else {
                            const option = document.createElement('option');
                            option.value = '';
                            option.textContent = 'No ' + selectedType + 's found';
                            option.disabled = true;
                            recipientId.appendChild(option);
                        }
                    })
                    .catch(error => {
                        console.error('Error loading recipients:', error);
                        recipientId.innerHTML = '<option value="">Error loading recipients</option>';
                        recipientId.disabled = true;
                    });
            }
        }

        // Add event listener
        recipientType.addEventListener('change', function() {
            populateRecipients();
        });

        // Initial population if old values exist
        const oldRecipientType = '{{ old("recipient_type", "") }}';
        const oldRecipientId = parseInt('{{ old("recipient_id", 0) }}') || 0;
        
        if (oldRecipientType) {
            recipientType.value = oldRecipientType;
            populateRecipients();
            if (oldRecipientId > 0) {
                setTimeout(function() {
                    recipientId.value = oldRecipientId;
                }, 200);
            }
        }
        
        // Debug: Log data to console
        console.log('Seekers count:', seekersData ? seekersData.length : 0);
        console.log('Companies count:', companiesData ? companiesData.length : 0);
    });
})();
</script>
@endpush
@endsection

