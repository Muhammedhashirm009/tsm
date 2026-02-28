<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#060b18">
    <title>{{ config('app.name', 'Masjid Finance') }} — Register</title>
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card animate-in">
            <div class="auth-brand">
                <div class="auth-brand-icon">
                    <i data-feather="moon" style="width:28px;height:28px;"></i>
                </div>
                <h1>Create Account</h1>
                <p>Start managing your Masjid finances</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-error mb-4">
                    <i data-feather="alert-circle" style="width:18px;height:18px;flex-shrink:0;"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Your full name" required autofocus value="{{ old('name') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="you@example.com" required value="{{ old('email') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Minimum 8 characters" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat your password" required>
                </div>

                <button type="submit" class="btn btn-primary w-full" style="padding:0.85rem;">
                    <i data-feather="user-plus" style="width:18px;height:18px;"></i> Create Account
                </button>
            </form>

            <div class="auth-footer">
                Already have an account? <a href="{{ route('login') }}">Sign in</a>
            </div>
        </div>
    </div>
    <script>feather.replace();</script>
</body>
</html>
