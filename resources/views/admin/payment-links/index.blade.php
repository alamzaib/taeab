@extends('admin.layouts.app')

@section('title', 'Payment Links')
@section('page-title', 'Payment Links Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Payment Links</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Payment Links</h3>
        <div class="card-tools">
            <a href="{{ route('admin.payment-links.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Generate Payment Link
            </a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Recipient</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Paid At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paymentLinks as $paymentLink)
                    <tr>
                        <td>{{ $paymentLink->id }}</td>
                        <td>
                            @if($paymentLink->recipient)
                                {{ $paymentLink->recipient_type === 'seeker' ? $paymentLink->recipient->name : $paymentLink->recipient->company_name }}
                                <br><small class="text-muted">{{ $paymentLink->recipient->email }}</small>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $paymentLink->recipient_type === 'seeker' ? 'info' : 'warning' }}">
                                {{ ucfirst($paymentLink->recipient_type) }}
                            </span>
                        </td>
                        <td>{{ $paymentLink->currency }} {{ number_format($paymentLink->amount, 2) }}</td>
                        <td>
                            @if($paymentLink->status === 'paid')
                                <span class="badge badge-success">Paid</span>
                            @elseif($paymentLink->status === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($paymentLink->status === 'failed')
                                <span class="badge badge-danger">Failed</span>
                            @elseif($paymentLink->status === 'expired')
                                <span class="badge badge-secondary">Expired</span>
                            @else
                                <span class="badge badge-secondary">{{ ucfirst($paymentLink->status) }}</span>
                            @endif
                        </td>
                        <td>{{ $paymentLink->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $paymentLink->paid_at ? $paymentLink->paid_at->format('Y-m-d H:i') : '-' }}</td>
                        <td>
                            <a href="{{ route('admin.payment-links.show', $paymentLink) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> View
                            </a>
                            @if($paymentLink->status === 'paid')
                                <a href="{{ route('admin.payment-links.invoice', $paymentLink) }}" class="btn btn-sm btn-success" target="_blank">
                                    <i class="fas fa-file-invoice"></i> Invoice
                                </a>
                            @endif
                            @if($paymentLink->payment_url && $paymentLink->status === 'pending')
                                <a href="{{ $paymentLink->payment_url }}" class="btn btn-sm btn-primary" target="_blank">
                                    <i class="fas fa-external-link-alt"></i> Link
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No payment links found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $paymentLinks->links() }}
        </div>
    </div>
</div>
@endsection

