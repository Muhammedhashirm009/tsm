<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#ffffff">
    <title>{{ config('app.name', 'Masjid Finance') }} — @yield('title')</title>
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
    <div class="app-container">

        <aside class="desktop-sidebar">
            <div class="sidebar-brand">
                <div class="sidebar-brand-icon"><i data-feather="moon" style="width:20px;height:20px;"></i></div>
                <div class="sidebar-brand-text">
                    <h2>Masjid Finance</h2>
                    <span>Accounting System</span>
                </div>
            </div>

            <div class="sidebar-section-title">Overview</div>
            <nav style="display:flex;flex-direction:column;">
                <a href="{{ route('dashboard') }}" class="desktop-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"><i data-feather="home"></i> Dashboard</a>
            </nav>

            <div class="sidebar-section-title">Transactions</div>
            <nav style="display:flex;flex-direction:column;">
                @if(Auth::user()->canViewFinance())
                <a href="{{ route('transactions.index') }}" class="desktop-nav-item {{ request()->routeIs('transactions.*') ? 'active' : '' }}"><i data-feather="list"></i> All Transactions</a>
                @endif
                <a href="{{ route('receipts.index') }}" class="desktop-nav-item {{ request()->routeIs('receipts.*') ? 'active' : '' }}"><i data-feather="arrow-down-left"></i> Income</a>
                @if(Auth::user()->canViewFinance())
                <a href="{{ route('vouchers.index') }}" class="desktop-nav-item {{ request()->routeIs('vouchers.*') ? 'active' : '' }}"><i data-feather="arrow-up-right"></i> Expense</a>
                @endif
            </nav>

            @if(Auth::user()->canViewFinance())
            <div class="sidebar-section-title">Finance</div>
            <nav style="display:flex;flex-direction:column;">
                <a href="{{ route('accounts.index') }}" class="desktop-nav-item {{ request()->routeIs('accounts.*') ? 'active' : '' }}"><i data-feather="credit-card"></i> Accounts</a>
                <a href="{{ route('debts.index') }}" class="desktop-nav-item {{ request()->routeIs('debts.*') ? 'active' : '' }}"><i data-feather="repeat"></i> Debts</a>
                <a href="{{ route('creditors.index') }}" class="desktop-nav-item {{ request()->routeIs('creditors.*') ? 'active' : '' }}"><i data-feather="users"></i> Creditors</a>
            </nav>

            <div class="sidebar-section-title">Setup</div>
            <nav style="display:flex;flex-direction:column;">
                <a href="{{ route('books.index') }}" class="desktop-nav-item {{ request()->routeIs('books.*') ? 'active' : '' }}"><i data-feather="book-open"></i> Books</a>
                @if(Auth::user()->canEdit())
                <a href="{{ route('categories.index') }}" class="desktop-nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}"><i data-feather="grid"></i> Categories</a>
                @endif
            </nav>
            @endif

            @if(Auth::user()->canViewFinance())
            <div class="sidebar-section-title">Mahal</div>
            <nav style="display:flex;flex-direction:column;">
                <a href="{{ route('mahal.dashboard') }}" class="desktop-nav-item {{ request()->routeIs('mahal.dashboard') ? 'active' : '' }}"><i data-feather="map-pin"></i> Dashboard</a>
                <a href="{{ route('mahal.homes.index') }}" class="desktop-nav-item {{ request()->routeIs('mahal.homes.*') ? 'active' : '' }}"><i data-feather="home"></i> Homes</a>
                <a href="{{ route('mahal.donations.index') }}" class="desktop-nav-item {{ request()->routeIs('mahal.donations.*') ? 'active' : '' }}"><i data-feather="heart"></i> Donations</a>
                <a href="{{ route('mahal.distributions.index') }}" class="desktop-nav-item {{ request()->routeIs('mahal.distributions.*') ? 'active' : '' }}"><i data-feather="gift"></i> Distributions</a>
            </nav>
            @endif

            @if(Auth::user()->isAdmin())
            <div class="sidebar-section-title">Admin</div>
            <nav style="display:flex;flex-direction:column;">
                <a href="{{ route('users.index') }}" class="desktop-nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}"><i data-feather="shield"></i> Users</a>
            </nav>
            @endif

            <div style="margin-top:auto;padding-top:1rem;">
                <div style="padding:0.65rem;background:var(--bg-body);border-radius:var(--radius-md);display:flex;align-items:center;gap:0.65rem;margin-bottom:0.65rem;">
                    <div style="width:30px;height:30px;border-radius:var(--radius-sm);background:var(--accent-bg);display:flex;align-items:center;justify-content:center;color:var(--accent);"><i data-feather="user" style="width:14px;height:14px;"></i></div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:600;font-size:0.8rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ Auth::user()->name }}</div>
                        <div style="font-size:0.65rem;color:var(--text-tertiary);">{{ Auth::user()->role_label }}</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="btn btn-secondary w-full"><i data-feather="log-out"></i> Sign Out</button></form>
            </div>
        </aside>

        <main class="main-content">
            <div class="mobile-page-header">
                <h2>@yield('title')</h2>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">@csrf<button type="submit" class="btn btn-ghost"><i data-feather="log-out"></i></button></form>
            </div>

            @if(session('success'))
                <div class="alert alert-success animate-in"><i data-feather="check-circle" style="width:16px;height:16px;flex-shrink:0;"></i> {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error animate-in"><i data-feather="alert-circle" style="width:16px;height:16px;flex-shrink:0;"></i> {{ session('error') }}</div>
            @endif

            @yield('content')
        </main>

        <nav class="mobile-nav">
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"><i data-feather="home"></i><span>Home</span></a>
            <a href="{{ route('transactions.index') }}" class="nav-item {{ request()->routeIs('transactions.*') ? 'active' : '' }}"><i data-feather="list"></i><span>All</span></a>
            <a href="{{ route('receipts.index') }}" class="nav-item {{ request()->routeIs('receipts.*') ? 'active' : '' }}"><i data-feather="arrow-down-left"></i><span>Income</span></a>
            <a href="{{ route('vouchers.index') }}" class="nav-item {{ request()->routeIs('vouchers.*') ? 'active' : '' }}"><i data-feather="arrow-up-right"></i><span>Expense</span></a>
            <a href="{{ route('books.index') }}" class="nav-item {{ request()->routeIs('books.*') ? 'active' : '' }}"><i data-feather="book-open"></i><span>Books</span></a>
        </nav>
    </div>
    <script>feather.replace();</script>
    @yield('scripts')
</body>
</html>
