@extends('layouts.admin')
@section('title', 'Distributions')
@section('content')
<div class="page-header animate-in">
    <div class="page-header-left">
        <h1><i data-feather="gift" style="width:24px;height:24px;color:var(--expense);"></i> Distribution Events</h1>
        <p>Manage Eid & festival distributions</p>
    </div>
    <a href="{{ route('mahal.distributions.create') }}" class="btn btn-primary"><i data-feather="plus"></i> New Event</a>
</div>

<div class="transaction-list animate-in" style="animation-delay:0.05s;">
    @forelse($events as $event)
        <div class="transaction-item" style="cursor:pointer;" onclick="window.location='{{ route('mahal.distributions.show', $event) }}'">
            <div class="transaction-icon {{ $event->status === 'completed' ? 'income' : ($event->status === 'active' ? 'expense' : 'balance') }}">
                <i data-feather="{{ $event->status === 'completed' ? 'check-circle' : ($event->status === 'active' ? 'zap' : 'clock') }}"></i>
            </div>
            <div class="transaction-details">
                <div class="transaction-title flex gap-2 items-center">
                    {{ $event->title }}
                    <span class="badge badge-{{ $event->status === 'completed' ? 'cash' : ($event->status === 'active' ? 'bank' : 'other') }}" style="font-size:0.55rem;padding:0.1rem 0.35rem;">{{ ucfirst($event->status) }}</span>
                </div>
                <div class="transaction-meta">
                    <span class="transaction-meta-item"><i data-feather="calendar"></i> {{ $event->event_date->format('d M Y') }}</span>
                    @if($event->items_description)<span class="transaction-meta-item"><i data-feather="package"></i> {{ Str::limit($event->items_description, 40) }}</span>@endif
                </div>
            </div>
            <div style="text-align:right;min-width:100px;">
                <div style="display:flex;flex-direction:column;gap:0.25rem;align-items:flex-end;">
                    <div class="text-xs"><span style="color:var(--accent);font-weight:600;">{{ $event->tokens_given_count }}</span><span class="text-tertiary">/{{ $event->records_count }} tokens</span></div>
                    <div class="text-xs"><span style="color:var(--income);font-weight:600;">{{ $event->collected_count }}</span><span class="text-tertiary">/{{ $event->records_count }} collected</span></div>
                    @if($event->records_count > 0)
                    <div style="width:80px;height:4px;background:var(--bg-body);border-radius:2px;overflow:hidden;">
                        <div style="height:100%;width:{{ ($event->collected_count / $event->records_count) * 100 }}%;background:var(--income);border-radius:2px;transition:width 0.3s;"></div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="empty-state">
                <div class="empty-state-icon" style="background:var(--expense-bg);color:var(--expense);"><i data-feather="gift"></i></div>
                <h4>No distribution events</h4>
                <p class="text-sm text-tertiary mt-1">Create your first distribution event for Eid or other occasions.</p>
                <a href="{{ route('mahal.distributions.create') }}" class="btn btn-primary mt-3"><i data-feather="plus"></i> New Event</a>
            </div>
        </div>
    @endforelse
</div>
@endsection
