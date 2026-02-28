<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masjid Finance — 500 Server Error</title>
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:var(--bg-page);padding:1rem;">
    <div class="card animate-in" style="max-width:480px;text-align:center;padding:3rem 2rem;">
        <div style="font-size:5rem;margin-bottom:0.5rem;">⚡</div>
        <h1 style="font-size:4rem;font-weight:800;color:var(--expense);margin:0;line-height:1;">500</h1>
        <h2 style="font-size:1.3rem;font-weight:600;margin:0.5rem 0;color:var(--text-primary);">Something Went Wrong</h2>
        <p style="color:var(--text-secondary);margin:1rem 0 0.5rem;font-size:0.95rem;">
            Even the best accountants make mistakes sometimes...
        </p>
        <p style="color:var(--text-tertiary);font-size:0.8rem;margin-bottom:1.5rem;">
            Our team has been notified. Please try again later.
        </p>
        <div style="display:flex;gap:0.75rem;justify-content:center;">
            <a href="/" class="btn btn-primary"><i data-feather="home" style="width:16px;height:16px;"></i> Go Home</a>
            <a href="javascript:location.reload()" class="btn btn-secondary"><i data-feather="refresh-cw" style="width:16px;height:16px;"></i> Retry</a>
        </div>
    </div>
</div>
<script>feather.replace();</script>
</body>
</html>
