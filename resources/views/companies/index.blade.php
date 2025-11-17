@extends('layouts.app')

@section('title', 'Companies - Job Portal UAE')

@section('content')
<div class="container">
    <div class="card">
        <h1 class="primary-text" style="font-size: 32px; margin-bottom: 25px;">Browse Companies</h1>

        <div style="display: grid; grid-template-columns: 280px 1fr; gap: 25px;">
            <aside style="border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; align-self: start;">
                <form action="{{ route('companies.index') }}" method="GET">
                    <div class="form-group">
                        <label for="q">Search</label>
                        <input type="text" name="q" id="q" class="form-control" placeholder="Company or industry" value="{{ request('q') }}">
                    </div>
                    <div class="form-group">
                        <label for="industry">Industry</label>
                        <select name="industry" id="industry" class="form-control">
                            <option value="">All Industries</option>
                            @foreach($industries as $industry)
                                <option value="{{ $industry }}" {{ request('industry') === $industry ? 'selected' : '' }}>
                                    {{ $industry }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="company_size">Company Size</label>
                        <select name="company_size" id="company_size" class="form-control">
                            <option value="">All Sizes</option>
                            @foreach($companySizes as $size)
                                <option value="{{ $size }}" {{ request('company_size') === $size ? 'selected' : '' }}>
                                    {{ $size }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @php
                        $statusFilter = request()->has('status') ? request('status') : 'active';
                    @endphp
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="active" {{ $statusFilter === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $statusFilter === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="" {{ $statusFilter === '' ? 'selected' : '' }}>Any</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-primary" style="width: 100%;">Apply Filters</button>
                </form>
            </aside>

            <section>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <div>
                        <strong>{{ $companies->total() }}</strong> companies found
                    </div>
                </div>

                @if($companies->isEmpty())
                    <p style="text-align: center; color: #6b7280;">No companies match your filters.</p>
                @else
                    <div style="display: grid; gap: 20px;">
                        @foreach($companies as $company)
                            <div class="card" style="margin-bottom: 0;">
                                <div style="display: flex; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                                    <div>
                                        <h2 class="primary-text" style="margin-bottom: 5px;">
                                            <a href="{{ route('companies.show', $company) }}" style="text-decoration:none; color:inherit;">
                                                {{ $company->company_name ?? $company->name }}
                                            </a>
                                        </h2>
                                        <p style="margin: 0; color: #6b7280;">
                                            {{ $company->industry ?? 'Industry not specified' }}
                                            @if($company->company_size)
                                                • {{ $company->company_size }}
                                            @endif
                                        </p>
                                        <p style="margin: 5px 0 0; color: #6b7280;">Contact: {{ $company->email }}</p>
                                    </div>
                                    <div style="text-align: right;">
                                        <span class="badge {{ $company->status === 'active' ? 'badge-success' : 'badge-secondary' }}">
                                            {{ ucfirst($company->status ?? 'inactive') }}
                                        </span>
                                        @if($company->website)
                                            <div style="margin-top: 10px;">
                                                <a href="{{ $company->website }}" target="_blank" class="btn-primary btn-sm">Visit Website</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div style="margin-top: 20px;">
                        {{ $companies->appends(request()->query())->links('components.pagination') }}
                    </div>
                @endif
            </section>
        </div>
    </div>
</div>
@endsection

