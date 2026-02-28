<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#060b18">
    <title>{{ config('app.name', 'Masjid Finance') }} — Login</title>
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
                <h1>Masjid Finance</h1>
                <p>Sign in to manage your accounts</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-error mb-4">
                    <i data-feather="alert-circle" style="width:18px;height:18px;flex-shrink:0;"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="you@example.com" required autofocus value="{{ old('email') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
                    <label style="display:flex;align-items:center;gap:0.4rem;font-size:0.85rem;color:var(--text-secondary);cursor:pointer;">
                        <input type="checkbox" name="remember" style="accent-color:var(--accent);">
                        Remember me
                    </label>
                </div>

                <button type="submit" class="btn btn-primary w-full" style="padding:0.85rem;">
                    <i data-feather="log-in" style="width:18px;height:18px;"></i> Sign In
                </button>
            </form>

            <div class="auth-footer">
                Don't have an account? <a href="{{ route('register') }}">Create one</a>
            </div>
        </div>
    </div>
    <script>feather.replace();</script>
</body>
</html>
