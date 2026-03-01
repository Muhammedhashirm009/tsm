@extends('layouts.admin')
@section('title', 'Mahal Dashboard')
@section('content')
<div class="page-header animate-in">
    <div class="page-header-left">
        <h1><i data-feather="map-pin" style="width:24px;height:24px;color:var(--accent);"></i> Mahal Dashboard</h1>
        <p>Overview of homes, donations & distributions</p>
    </div>
</div>

<div class="stats-grid animate-in">
    <div class="stat-card income">
        <div class="stat-icon income"><i data-feather="home"></i></div>
        <div class="stat-label">Total Homes</div>
        <div class="stat-value">{{ $totalHomes }}</div>
        <div class="text-xs text-tertiary mt-1">{{ $activeHomes }} active</div>
    </div>
    <div class="stat-card balance">
        <div class="stat-icon balance"><i data-feather="heart"></i></div>
        <div class="stat-label">Total Donations</div>
        <div class="stat-value">₹{{ number_format($totalDonations, 2) }}</div>
    </div>
    <div class="stat-card expense">
        <div class="stat-icon expense"><i data-feather="calendar"></i></div>
        <div class="stat-label">This Month</div>
        <div class="stat-value">₹{{ number_format($thisMonthDonations, 2) }}</div>
    </div>
    <div class="stat-card debt">
        <div class="stat-icon debt"><i data-feather="gift"></i></div>
        <div class="stat-label">Active Events</div>
        <div class="stat-value">{{ $activeEvents }}</div>
    </div>
</div>

<div class="grid-2 animate-in" style="animation-delay:0.1s;">
    {{-- Recent Donations --}}
    <div class="card">
        <div class="card-header">
            <h3 class="section-title"><i data-feather="heart" style="width:16px;height:16px;color:var(--income);"></i> Recent Donations</h3>
            <a href="{{ route('mahal.donations.create') }}" class="btn btn-primary btn-sm"><i data-feather="plus"></i> Add</a>
        </div>
        <div class="transaction-list">
            @forelse($recentDonations as $donation)
                <div class="transaction-item">
                    <div class="transaction-icon income"><i data-feather="heart"></i></div>
                    <div class="transaction-details">
                        <div class="transaction-title">{{ $donation->donor_name ?? 'Anonymous' }}</div>
                        <div class="transaction-meta">
                            <span class="transaction-meta-item"><i data-feather="calendar"></i> {{ $donation->date->format('d M Y') }}</span>
                            @if($donation->home)<span class="badge" style="background:var(--accent-bg);color:var(--accent);font-size:0.55rem;padding:0.1rem 0.35rem;">Home #{{ $donation->home->home_number }}</span>@endif
                        </div>
                    </div>
                    <div class="transaction-amount text-income">+₹{{ number_format($donation->amount, 2) }}</div>
                </div>
            @empty
                <div class="empty-state" style="padding:1.25rem;"><p class="text-sm text-tertiary">No donations yet</p></div>
            @endforelse
        </div>
        @if($recentDonations->count() > 0)
            <div class="text-center mt-3"><a href="{{ route('mahal.donations.index') }}" class="text-sm text-accent font-semibold">View All →</a></div>
        @endif
    </div>

    {{-- Upcoming Events --}}
    <div class="card">
        <div class="card-header">
            <h3 class="section-title"><i data-feather="gift" style="width:16px;height:16px;color:var(--expense);"></i> Upcoming Distributions</h3>
            <a href="{{ route('mahal.distributions.create') }}" class="btn btn-primary btn-sm"><i data-feather="plus"></i> New</a>
        </div>
        <div class="transaction-list">
            @forelse($upcomingEvents as $event)
                <div class="transaction-item" style="cursor:pointer;" onclick="window.location='{{ route('mahal.distributions.show', $event) }}'">
                    <div class="transaction-icon {{ $event->status === 'active' ? 'income' : 'expense' }}"><i data-feather="{{ $event->status === 'active' ? 'zap' : 'clock' }}"></i></div>
                    <div class="transaction-details">
                        <div class="transaction-title">{{ $event->title }}</div>
                        <div class="transaction-meta">
                            <span class="transaction-meta-item"><i data-feather="calendar"></i> {{ $event->event_date->format('d M Y') }}</span>
                            <span class="badge badge-{{ $event->status === 'active' ? 'cash' : ($event->status === 'planned' ? 'bank' : 'other') }}" style="font-size:0.55rem;padding:0.1rem 0.35rem;">{{ ucfirst($event->status) }}</span>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <div class="text-xs text-tertiary">{{ $event->items_description ?? '—' }}</div>
                    </div>
                </div>
            @empty
                <div class="empty-state" style="padding:1.25rem;"><p class="text-sm text-tertiary">No upcoming events</p></div>
            @endforelse
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="card mt-4 animate-in" style="animation-delay:0.15s;">
    <div class="card-header"><h3 class="section-title"><i data-feather="zap" style="width:16px;height:16px;"></i> Quick Actions</h3></div>
    <div style="display:flex;gap:0.75rem;padding:0.5rem 0;flex-wrap:wrap;">
        <a href="{{ route('mahal.homes.create') }}" class="btn btn-secondary"><i data-feather="plus"></i> Add Home</a>
        <a href="{{ route('mahal.donations.create') }}" class="btn btn-secondary" style="border-color:var(--income-border);color:var(--income);"><i data-feather="heart"></i> Add Donation</a>
        <a href="{{ route('mahal.distributions.create') }}" class="btn btn-secondary" style="border-color:var(--expense-border);color:var(--expense);"><i data-feather="gift"></i> New Distribution</a>
    </div>
</div>
@endsection
