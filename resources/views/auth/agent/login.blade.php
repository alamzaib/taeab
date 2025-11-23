@extends('layouts.app')

@section('title', 'Agent Login - Job Portal UAE')

@section('content')
<div class="container">
    <div class="card" style="max-width: 500px; margin: 50px auto;">
        <h2 class="primary-text" style="margin-bottom: 20px; font-size: 28px;">Agent Login</h2>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('agent.login') }}" id="agentLoginForm">
            @csrf
            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="remember"> Remember me
                </label>
            </div>

            <div class="form-group">
                <button type="submit" class="btn-primary" style="width: 100%;">Login</button>
            </div>

            <div style="text-align: center; margin-top: 15px;">
                <p>Don't have an account? <a href="{{ route('agent.register') }}" class="primary-text">Register here</a></p>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
document.getElementById('agentLoginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    
    if (window.recaptchaSiteKey) {
        executeRecaptcha('login').then(function(token) {
            document.getElementById('g-recaptcha-response').value = token;
            form.submit();
        });
    } else {
        form.submit();
    }
});
</script>
@endpush
@endsection

