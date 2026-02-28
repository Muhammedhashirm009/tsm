@extends('layouts.admin')
@section('title', $creditor->name)
@section('content')
<div class="page-header animate-in">
    <div class="page-header-left">
        <h1><i data-feather="user" style="width:24px;height:24px;"></i> {{ $creditor->name }}</h1>
        @if($creditor->phone)<p><i data-feather="phone" style="width:13px;height:13px;display:inline;"></i> {{ $creditor->phone }}</p>@endif
    </div>
    <div style="display:flex;gap:0.5rem;">
        <a href="{{ route('creditors.index') }}" class="btn btn-secondary"><i data-feather="arrow-left"></i> Back</a>
        <a href="{{ route('creditors.edit', $creditor) }}" class="btn btn-secondary"><i data-feather="edit-2"></i> Edit</a>
    </div>
</div>

<div class="stats-grid animate-in" style="animation-delay:0.05s;grid-template-columns:repeat(3,1fr);">
    <div class="stat-card debt">
        <div class="stat-icon debt"><i data-feather="arrow-down-left"></i></div>
        <div class="stat-label">Total Borrowed</div>
        <div class="stat-value text-debt">₹{{ number_format($totalBorrowed, 2) }}</div>
    </div>
    <div class="stat-card income">
        <div class="stat-icon income"><i data-feather="check-circle"></i></div>
        <div class="stat-label">Total Repaid</div>
        <div class="stat-value text-income">₹{{ number_format($totalRepaid, 2) }}</div>
    </div>
    <div class="stat-card" style="border-top:3px solid {{ $outstanding > 0 ? 'var(--expense)' : 'var(--income)' }};">
        <div class="stat-icon {{ $outstanding > 0 ? 'expense' : 'income' }}"><i data-feather="{{ $outstanding > 0 ? 'alert-circle' : 'check' }}"></i></div>
        <div class="stat-label">Outstanding</div>
        <div class="stat-value {{ $outstanding > 0 ? 'text-expense' : 'text-income' }}">₹{{ number_format($outstanding, 2) }}</div>
    </div>
</div>

<div class="section animate-in" style="animation-delay:0.1s;">
    <div class="section-header">
        <h3 class="section-title"><i data-feather="list" style="width:16px;height:16px;"></i> Debt History ({{ $debts->count() }})</h3>
        <a href="{{ route('debts.create') }}" class="btn btn-primary btn-sm"><i data-feather="plus"></i> New Debt</a>
    </div>
    <div class="transaction-list">
        @forelse($debts as $debt)
            <div class="transaction-item">
                <div class="transaction-icon" style="background:var(--debt-bg);color:var(--debt);"><i data-feather="arrow-down-left"></i></div>
                <div class="transaction-details">
                    <div class="transaction-title flex gap-2 items-center">
                        ₹{{ number_format($debt->amount, 2) }}
                        <span class="badge badge-{{ $debt->status }}">{{ ucfirst($debt->status) }}</span>
                        @if($debt->account)<span class="badge badge-{{ $debt->account->type }}" style="font-size:0.55rem;padding:0.1rem 0.35rem;">{{ $debt->account->name }}</span>@endif
                    </div>
                    <div class="transaction-meta">
                        <span class="transaction-meta-item"><i data-feather="calendar"></i> {{ \Carbon\Carbon::parse($debt->date)->format('d M Y') }}</span>
                        @if($debt->paid_amount > 0)<span class="transaction-meta-item text-income"><i data-feather="check-circle"></i> Repaid ₹{{ number_format($debt->paid_amount, 2) }}</span>@endif
                        @if($debt->remaining > 0)<span class="transaction-meta-item text-debt">Remaining ₹{{ number_format($debt->remaining, 2) }}</span>@endif
                    </div>
                    @if($debt->description)<div class="text-xs text-tertiary mt-1">{{ $debt->description }}</div>@endif
                </div>
                <div class="transaction-actions">
                    <a href="{{ route('debts.edit', $debt) }}" class="btn btn-ghost btn-icon"><i data-feather="edit-2" style="width:15px;height:15px;"></i></a>
                </div>
            </div>
        @empty
            <div class="text-center text-sm text-tertiary" style="padding:1.5rem;">No debt records for this creditor yet.</div>
        @endforelse
    </div>
</div>
@endsection
