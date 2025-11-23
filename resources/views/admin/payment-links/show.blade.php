@extends('admin.layouts.app')

@section('title', 'Payment Link Details')
@section('page-title', 'Payment Link Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.payment-links.index') }}">Payment Links</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Payment Link #{{ $paymentLink->id }}</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th width="200">Recipient Type</th>
                <td>
                    <span class="badge badge-{{ $paymentLink->recipient_type === 'seeker' ? 'info' : 'warning' }}">
                        {{ ucfirst($paymentLink->recipient_type) }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Recipient</th>
                <td>
                    @if($paymentLink->recipient)
                        <strong>{{ $paymentLink->recipient_type === 'seeker' ? $paymentLink->recipient->name : $paymentLink->recipient->company_name }}</strong><br>
                        <small class="text-muted">{{ $paymentLink->recipient->email }}</small>
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Amount</th>
                <td><strong>{{ $paymentLink->currency }} {{ number_format($paymentLink->amount, 2) }}</strong></td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @if($paymentLink->status === 'paid')
                        <span class="badge badge-success">Paid</span>
                    @elseif($paymentLink->status === 'pending')
                        <span class="badge badge-warning">Pending</span>
                    @elseif($paymentLink->status === 'failed')
                        <span class="badge badge-danger">Failed</span>
                    @else
                        <span class="badge badge-secondary">{{ ucfirst($paymentLink->status) }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Description</th>
                <td>{{ $paymentLink->description ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Payment URL</th>
                <td>
                    @if($paymentLink->payment_url)
                        <a href="{{ $paymentLink->payment_url }}" target="_blank" class="btn btn-sm btn-primary">
                            <i class="fas fa-external-link-alt"></i> Open Payment Link
                        </a>
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Stripe Session ID</th>
                <td><code>{{ $paymentLink->stripe_checkout_session_id ?? 'N/A' }}</code></td>
            </tr>
            <tr>
                <th>Stripe Invoice ID</th>
                <td><code>{{ $paymentLink->stripe_invoice_id ?? 'N/A' }}</code></td>
            </tr>
            <tr>
                <th>Created At</th>
                <td>{{ $paymentLink->created_at->format('Y-m-d H:i:s') }}</td>
            </tr>
            <tr>
                <th>Expires At</th>
                <td>{{ $paymentLink->expires_at ? $paymentLink->expires_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
            </tr>
            <tr>
                <th>Paid At</th>
                <td>{{ $paymentLink->paid_at ? $paymentLink->paid_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
            </tr>
        </table>

        <div class="mt-3">
            @if($paymentLink->status === 'paid')
                <a href="{{ route('admin.payment-links.invoice', $paymentLink) }}" class="btn btn-success" target="_blank">
                    <i class="fas fa-file-invoice"></i> Generate Invoice
                </a>
            @endif
            <a href="{{ route('admin.payment-links.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection

