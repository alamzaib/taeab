@extends('admin.layouts.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('title', 'Package Requests - Admin Dashboard')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Package Requests</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All Package Requests</h3>
                </div>
                <div class="card-body">
                    @if($requests->isEmpty())
                        <p class="text-muted">No package requests found.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Company</th>
                                        <th>Package</th>
                                        <th>Status</th>
                                        <th>Message</th>
                                        <th>Requested</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $request)
                                        <tr>
                                            <td>
                                                <strong>{{ $request->company->company_name ?? $request->company->name }}</strong><br>
                                                <small class="text-muted">{{ $request->company->email }}</small>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">{{ $request->package->display_name }}</span><br>
                                                <small class="text-muted">${{ number_format($request->package->price, 2) }}</small>
                                            </td>
                                            <td>
                                                @if($request->status === 'pending')
                                                    <span class="badge badge-warning">Pending</span>
                                                @elseif($request->status === 'approved')
                                                    <span class="badge badge-success">Approved</span>
                                                @else
                                                    <span class="badge badge-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($request->message)
                                                    <small>{{ Str::limit($request->message, 50) }}</small>
                                                @else
                                                    <small class="text-muted">No message</small>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $request->created_at->format('M d, Y') }}</small><br>
                                                <small class="text-muted">{{ $request->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                @if($request->status === 'pending')
                                                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#approveModal{{ $request->id }}">
                                                        Approve
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#rejectModal{{ $request->id }}">
                                                        Reject
                                                    </button>
                                                @else
                                                    @if($request->admin_notes)
                                                        <small class="text-muted">{{ Str::limit($request->admin_notes, 30) }}</small>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>

                                        <!-- Approve Modal -->
                                        <div class="modal fade" id="approveModal{{ $request->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Approve Package Request</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <form action="{{ route('admin.packages.approve', $request) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <p>Approve <strong>{{ $request->company->company_name ?? $request->company->name }}</strong>'s request for <strong>{{ $request->package->display_name }}</strong> package?</p>
                                                            <div class="form-group">
                                                                <label for="admin_notes_approve{{ $request->id }}">Admin Notes (Optional)</label>
                                                                <textarea name="admin_notes" id="admin_notes_approve{{ $request->id }}" class="form-control" rows="3" placeholder="Add any notes..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-success">Approve</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Reject Modal -->
                                        <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Reject Package Request</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <form action="{{ route('admin.packages.reject', $request) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <p>Reject <strong>{{ $request->company->company_name ?? $request->company->name }}</strong>'s request for <strong>{{ $request->package->display_name }}</strong> package?</p>
                                                            <div class="form-group">
                                                                <label for="admin_notes_reject{{ $request->id }}">Admin Notes (Optional)</label>
                                                                <textarea name="admin_notes" id="admin_notes_reject{{ $request->id }}" class="form-control" rows="3" placeholder="Add reason for rejection..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">Reject</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $requests->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

