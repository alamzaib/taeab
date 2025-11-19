@extends('layouts.company-dashboard')

@section('title', 'Packages - Job Portal UAE')

@section('dashboard-content')
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
            <div>
                <h2 class="primary-text" style="margin: 0 0 8px; font-size: 28px;">Select a Package</h2>
                <p style="margin: 0; color: #64748b;">Choose the package that best fits your hiring needs</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="padding: 16px; background: #10b981; color: white; border-radius: 12px; margin-bottom: 24px;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error" style="padding: 16px; background: #ef4444; color: white; border-radius: 12px; margin-bottom: 24px;">
                {{ session('error') }}
            </div>
        @endif

        @if($pendingRequest)
            <div style="padding: 20px; background: #fef3c7; border: 1px solid #fcd34d; border-radius: 16px; margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong style="color: #92400e;">Pending Package Request</strong>
                        <p style="margin: 6px 0 0; color: #78350f;">You have a pending request for <strong>{{ $pendingRequest->package->display_name }}</strong> package. Waiting for admin approval.</p>
                    </div>
                    <span style="padding: 6px 12px; background: #fbbf24; color: #78350f; border-radius: 8px; font-size: 12px; font-weight: 600;">Pending</span>
                </div>
            </div>
        @endif

        @if($currentPackage)
            <div style="padding: 20px; background: #ecfdf5; border: 1px solid #86efac; border-radius: 16px; margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong style="color: #065f46;">Current Package</strong>
                        <p style="margin: 6px 0 0; color: #047857;">You are currently on the <strong>{{ $currentPackage->display_name }}</strong> package.</p>
                    </div>
                    <span style="padding: 6px 12px; background: #10b981; color: white; border-radius: 8px; font-size: 12px; font-weight: 600;">Active</span>
                </div>
            </div>
        @endif

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
            @foreach($packages as $package)
                <div class="card" style="border: 2px solid {{ $currentPackage && $currentPackage->id === $package->id ? '#10b981' : '#e2e8f0' }}; border-radius: 20px; padding: 28px; position: relative; {{ $currentPackage && $currentPackage->id === $package->id ? 'background: #f0fdf4;' : '' }}">
                    @if($currentPackage && $currentPackage->id === $package->id)
                        <div style="position: absolute; top: 16px; right: 16px; padding: 4px 12px; background: #10b981; color: white; border-radius: 999px; font-size: 11px; font-weight: 600;">CURRENT</div>
                    @endif
                    
                    <div style="margin-bottom: 20px;">
                        <h3 style="margin: 0 0 8px; font-size: 24px; font-weight: 700; color: #0f172a;">{{ $package->display_name }}</h3>
                        <div style="display: flex; align-items: baseline; gap: 8px;">
                            <span style="font-size: 32px; font-weight: 800; color: #0f172a;">${{ number_format($package->price, 2) }}</span>
                            @if($package->price > 0)
                                <span style="font-size: 14px; color: #64748b;">/month</span>
                            @endif
                        </div>
                        @if($package->description)
                            <p style="margin: 12px 0 0; color: #64748b; font-size: 14px; line-height: 1.5;">{{ $package->description }}</p>
                        @endif
                    </div>

                    @if($package->features && count($package->features) > 0)
                        <ul style="list-style: none; padding: 0; margin: 0 0 24px; display: flex; flex-direction: column; gap: 12px;">
                            @foreach($package->features as $feature)
                                <li style="display: flex; align-items: center; gap: 10px; color: #475569; font-size: 14px;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if(!$pendingRequest)
                        <form action="{{ route('company.packages.request') }}" method="POST">
                            @csrf
                            <input type="hidden" name="package_id" value="{{ $package->id }}">
                            <div style="margin-bottom: 16px;">
                                <label for="message-{{ $package->id }}" style="display: block; margin-bottom: 6px; font-size: 13px; font-weight: 600; color: #475569;">Message (Optional)</label>
                                <textarea name="message" id="message-{{ $package->id }}" rows="3" class="form-control" placeholder="Add any notes or special requests..." style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"></textarea>
                            </div>
                            <button type="submit" class="btn-primary" style="width: 100%; {{ $currentPackage && $currentPackage->id === $package->id ? 'opacity: 0.6; cursor: not-allowed;' : '' }}" {{ $currentPackage && $currentPackage->id === $package->id ? 'disabled' : '' }}>
                                {{ $currentPackage && $currentPackage->id === $package->id ? 'Current Package' : 'Request Package' }}
                            </button>
                        </form>
                    @else
                        <button type="button" class="btn btn-light" style="width: 100%;" disabled>Request Pending</button>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endsection

