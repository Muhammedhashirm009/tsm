@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
@if(Auth::user()->isCollector())
{{-- Collector Dashboard --}}
<div class="stats-grid animate-in" style="grid-template-columns:repeat(2,1fr);">
    <div class="stat-card income">
        <div class="stat-icon income"><i data-feather="arrow-down-left"></i></div>
        <div class="stat-label">My Collections (Today)</div>
        <div class="stat-value text-income">₹{{ number_format($myTodayCollected, 2) }}</div>
    </div>
    <div class="stat-card balance">
        <div class="stat-icon balance"><i data-feather="briefcase"></i></div>
        <div class="stat-label">My Total Collections</div>
        <div class="stat-value">₹{{ number_format($myTotalCollected, 2) }}</div>
    </div>
</div>

<div class="card mt-4 animate-in" style="animation-delay:0.1s;">
    <div class="card-header">
        <h3 class="section-title"><i data-feather="clock" style="width:16px;height:16px;color:var(--text-secondary);"></i> My Recent Receipts</h3>
        <a href="{{ route('receipts.create') }}" class="btn btn-primary btn-sm"><i data-feather="plus"></i> Add Receipt</a>
    </div>
    <div class="transaction-list">
        @forelse($recentReceipts as $receipt)
            <div class="transaction-item">
                <div class="transaction-icon income"><i data-feather="arrow-down-left"></i></div>
                <div class="transaction-details">
                    <div class="transaction-title">{{ $receipt->category->name ?? '—' }}</div>
                    <div class="transaction-meta">
                        <span class="transaction-meta-item"><i data-feather="calendar"></i> {{ \Carbon\Carbon::parse($receipt->date)->format('d M') }}</span>
                        @if($receipt->account)<span class="badge badge-{{ $receipt->account->type }}" style="font-size:0.55rem;padding:0.1rem 0.35rem;">{{ $receipt->account->name }}</span>@endif
                    </div>
                </div>
                <div class="transaction-amount text-income">+₹{{ number_format($receipt->amount, 2) }}</div>
            </div>
        @empty
            <div class="empty-state" style="padding:1.25rem;"><p class="text-sm text-tertiary">You haven't added any receipts yet.</p></div>
        @endforelse
    </div>
</div>

@else
{{-- Full Dashboard (Admin, President, Secretary) --}}
<div class="stats-grid animate-in">
    <div class="stat-card income">
        <div class="stat-icon income"><i data-feather="arrow-down-left"></i></div>
        <div class="stat-label">Total Income</div>
        <div class="stat-value text-income">₹{{ number_format($totalIncome, 2) }}</div>
    </div>
    <div class="stat-card expense">
        <div class="stat-icon expense"><i data-feather="arrow-up-right"></i></div>
        <div class="stat-label">Total Expense</div>
        <div class="stat-value text-expense">₹{{ number_format($totalExpense, 2) }}</div>
    </div>
    <div class="stat-card balance">
        <div class="stat-icon balance"><i data-feather="briefcase"></i></div>
        <div class="stat-label">Net Balance</div>
        <div class="stat-value">₹{{ number_format($balance, 2) }}</div>
    </div>
    <div class="stat-card debt">
        <div class="stat-icon debt"><i data-feather="repeat"></i></div>
        <div class="stat-label">Debt Outstanding</div>
        <div class="stat-value text-debt">₹{{ number_format($totalDebtOutstanding, 2) }}</div>
    </div>
</div>

{{-- Account Balances --}}
@if($accounts->count() > 0)
<div class="section animate-in" style="animation-delay:0.05s;">
    <div class="section-header">
        <h3 class="section-title"><i data-feather="credit-card" style="width:16px;height:16px;"></i> Account Balances</h3>
        <a href="{{ route('accounts.index') }}" class="text-sm text-accent font-semibold">Manage →</a>
    </div>
    <div class="accounts-grid">
        @foreach($accounts as $account)
            <div class="account-card">
                <div class="account-card-icon {{ $account->type }}"><i data-feather="{{ $account->type == 'cash' ? 'dollar-sign' : ($account->type == 'bank' ? 'credit-card' : 'briefcase') }}"></i></div>
                <div class="account-card-info">
                    <div class="label">{{ $account->name }}</div>
                    <div class="value">₹{{ number_format($account->balance, 2) }}</div>
                    <div class="meta">In: ₹{{ number_format($account->total_income, 2) }} · Out: ₹{{ number_format($account->total_expense, 2) }}</div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

<div class="grid-2 animate-in" style="animation-delay:0.1s;">
    <div class="card">
        <div class="card-header">
            <h3 class="section-title"><i data-feather="trending-up" style="width:16px;height:16px;color:var(--income);"></i> Recent Income</h3>
            <a href="{{ route('receipts.create') }}" class="btn btn-primary btn-sm"><i data-feather="plus"></i> Add</a>
        </div>
        <div class="transaction-list">
            @forelse($recentReceipts as $receipt)
                <div class="transaction-item">
                    <div class="transaction-icon income"><i data-feather="arrow-down-left"></i></div>
                    <div class="transaction-details">
                        <div class="transaction-title">{{ $receipt->category->name ?? '—' }}</div>
                        <div class="transaction-meta">
                            <span class="transaction-meta-item"><i data-feather="calendar"></i> {{ \Carbon\Carbon::parse($receipt->date)->format('d M') }}</span>
                            @if($receipt->account)<span class="badge badge-{{ $receipt->account->type }}" style="font-size:0.55rem;padding:0.1rem 0.35rem;">{{ $receipt->account->name }}</span>@endif
                        </div>
                    </div>
                    <div class="transaction-amount text-income">+₹{{ number_format($receipt->amount, 2) }}</div>
                </div>
            @empty
                <div class="empty-state" style="padding:1.25rem;"><p class="text-sm text-tertiary">No income recorded yet</p></div>
            @endforelse
        </div>
        @if($recentReceipts->count() > 0)
            <div class="text-center mt-3"><a href="{{ route('receipts.index') }}" class="text-sm text-accent font-semibold">View All →</a></div>
        @endif
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="section-title"><i data-feather="trending-down" style="width:16px;height:16px;color:var(--expense);"></i> Recent Expenses</h3>
            <a href="{{ route('vouchers.create') }}" class="btn btn-primary btn-sm"><i data-feather="plus"></i> Add</a>
        </div>
        <div class="transaction-list">
            @forelse($recentVouchers as $voucher)
                <div class="transaction-item">
                    <div class="transaction-icon expense"><i data-feather="arrow-up-right"></i></div>
                    <div class="transaction-details">
                        <div class="transaction-title">{{ $voucher->category->name ?? '—' }}</div>
                        <div class="transaction-meta">
                            <span class="transaction-meta-item"><i data-feather="calendar"></i> {{ \Carbon\Carbon::parse($voucher->date)->format('d M') }}</span>
                            @if($voucher->account)<span class="badge badge-{{ $voucher->account->type }}" style="font-size:0.55rem;padding:0.1rem 0.35rem;">{{ $voucher->account->name }}</span>@endif
                        </div>
                    </div>
                    <div class="transaction-amount text-expense">−₹{{ number_format($voucher->amount, 2) }}</div>
                </div>
            @empty
                <div class="empty-state" style="padding:1.25rem;"><p class="text-sm text-tertiary">No expenses recorded yet</p></div>
            @endforelse
        </div>
        @if($recentVouchers->count() > 0)
            <div class="text-center mt-3"><a href="{{ route('vouchers.index') }}" class="text-sm text-accent font-semibold">View All →</a></div>
        @endif
    </div>
</div>
@endif
@endsection
