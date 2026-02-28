<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#f0f4f8">
        <title>{{ config('app.name', 'Masjid Finance') }} — @yield('title', 'Login')</title>
        <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
        <script src="https://unpkg.com/feather-icons"></script>
        <style>
            .auth-wrapper { min-height:100vh; display:flex; align-items:center; justify-content:center; background:var(--bg-page); padding:1rem; }
            .auth-card { width:100%; max-width:420px; background:var(--bg-card); border-radius:var(--radius-lg); box-shadow:var(--shadow-lg); padding:2.5rem 2rem; }
            .auth-header { text-align:center; margin-bottom:2rem; }
            .auth-header .logo { font-size:2.5rem; margin-bottom:0.5rem; }
            .auth-header h1 { font-size:1.5rem; font-weight:700; color:var(--text-primary); margin:0.25rem 0; }
            .auth-header p { color:var(--text-tertiary); font-size:0.85rem; margin:0; }
            .auth-card .form-group { margin-bottom:1rem; }
            .auth-card .form-label { display:block; margin-bottom:0.3rem; font-size:0.8rem; font-weight:600; color:var(--text-secondary); text-transform:uppercase; letter-spacing:0.03em; }
            .auth-card .form-control { width:100%; padding:0.65rem 0.75rem; border:1.5px solid var(--border-light); border-radius:var(--radius-md); font-size:0.9rem; background:var(--bg-card); transition:border-color 0.2s; box-sizing:border-box; }
            .auth-card .form-control:focus { outline:none; border-color:var(--accent); box-shadow:0 0 0 3px rgba(67,97,238,0.1); }
            .auth-footer { text-align:center; margin-top:1.5rem; font-size:0.8rem; color:var(--text-tertiary); }
            .auth-footer a { color:var(--accent); text-decoration:none; font-weight:500; }
            .auth-remember { display:flex; align-items:center; gap:0.5rem; margin:1rem 0; font-size:0.85rem; color:var(--text-secondary); }
            .auth-btn { width:100%; padding:0.75rem; border:none; border-radius:var(--radius-md); background:var(--accent); color:white; font-size:0.9rem; font-weight:600; cursor:pointer; transition:all 0.2s; }
            .auth-btn:hover { background:var(--accent-hover); transform:translateY(-1px); box-shadow:var(--shadow-md); }
            .form-error { color:var(--expense); font-size:0.75rem; margin-top:0.2rem; }
        </style>
    </head>
    <body>
        <div class="auth-wrapper">
            <div class="auth-card animate-in">
                <div class="auth-header">
                    <div class="logo">🕌</div>
                    <h1>{{ config('app.name', 'Masjid Finance') }}</h1>
                    <p>Accounting System</p>
                </div>
                {{ $slot }}
            </div>
        </div>
        <script>feather.replace();</script>
    </body>
</html>
