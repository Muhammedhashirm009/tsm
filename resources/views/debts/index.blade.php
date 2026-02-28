@extends('layouts.admin')
@section('title', 'Debts')
@section('content')
<div class="page-header animate-in">
    <div class="page-header-left">
        <h1><i data-feather="repeat" style="width:24px;height:24px;"></i> Debts</h1>
        <p>Track borrowed money & repayments</p>
    </div>
    <a href="{{ route('debts.create') }}" class="btn btn-primary"><i data-feather="plus"></i> New Debt</a>
</div>

@if($totalOutstanding > 0)
<div class="account-card mb-4 animate-in" style="border-left:3px solid var(--debt);">
    <div class="account-card-icon other"><i data-feather="alert-circle" style="width:18px;height:18px;"></i></div>
    <div class="account-card-info">
        <div class="label">Outstanding Debt</div>
        <div class="value text-debt">₹{{ number_format($totalOutstanding, 2) }}</div>
        <div class="meta">Total Borrowed: ₹{{ number_format($totalBorrowed, 2) }} · Repaid: ₹{{ number_format($totalPaid, 2) }}</div>
    </div>
</div>
@endif

<div class="data-list animate-in" style="animation-delay:0.05s;">
    @forelse($debts as $debt)
        <div class="data-item">
            <div class="data-item-icon" style="background:var(--debt-bg);color:var(--debt);"><i data-feather="{{ $debt->type == 'borrowed' ? 'arrow-down-left' : 'arrow-up-right' }}"></i></div>
            <div class="data-item-content">
                <div class="data-item-title">@if($debt->creditor)<a href="{{ route('creditors.show', $debt->creditor) }}" style="color:var(--text-primary);text-decoration:none;border-bottom:1px dashed var(--border-light);">{{ $debt->creditor->name }}</a>@else{{ $debt->person_name }}@endif</div>
                <div class="data-item-subtitle flex gap-2 items-center mt-1">
                    <span class="badge badge-debt">Borrowed</span>
                    <span class="badge badge-{{ $debt->status }}">{{ ucfirst($debt->status) }}</span>
                    <span><i data-feather="calendar" style="width:11px;height:11px;display:inline;"></i> {{ \Carbon\Carbon::parse($debt->date)->format('d M Y') }}</span>
                    @if($debt->account)<span class="badge badge-{{ $debt->account->type }}" style="font-size:0.55rem;padding:0.1rem 0.35rem;">{{ $debt->account->name }}</span>@endif
                </div>
                @if($debt->status != 'paid' && $debt->remaining > 0)
                    <div class="text-xs text-tertiary mt-1">Remaining: <strong class="text-debt">₹{{ number_format($debt->remaining, 2) }}</strong></div>
                @endif
            </div>
            <div style="text-align:right;">
                <div class="font-bold text-debt" style="font-size:1rem;">₹{{ number_format($debt->amount, 2) }}</div>
                @if($debt->paid_amount > 0)<div class="text-xs text-income">Paid: ₹{{ number_format($debt->paid_amount, 2) }}</div>@endif
            </div>
            <div class="data-item-actions">
                @if($debt->status !== 'paid')
                    <a href="{{ route('debts.repay', $debt) }}" class="btn btn-sm" style="background:var(--income);color:white;font-size:0.7rem;padding:0.25rem 0.5rem;border-radius:var(--radius-sm);"><i data-feather="check-circle" style="width:13px;height:13px;"></i> Repay</a>
                @endif
                <a href="{{ route('debts.edit', $debt) }}" class="btn btn-ghost btn-icon"><i data-feather="edit-2" style="width:16px;height:16px;"></i></a>
                <form action="{{ route('debts.destroy', $debt) }}" method="POST" onsubmit="return confirm('Delete this debt?');">@csrf @method('DELETE')
                    <button type="submit" class="btn btn-ghost btn-icon" style="color:var(--expense);"><i data-feather="trash-2" style="width:16px;height:16px;"></i></button>
                </form>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="empty-state">
                <div class="empty-state-icon" style="background:var(--debt-bg);color:var(--debt);"><i data-feather="repeat"></i></div>
                <h4>No debts recorded</h4>
                <p class="text-sm text-tertiary mt-1">Track money borrowed here.</p>
            </div>
        </div>
    @endforelse
</div>
@endsection
