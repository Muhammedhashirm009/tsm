@extends('layouts.admin')
@section('title', 'Accounts')
@section('content')
<div class="page-header animate-in">
    <div class="page-header-left">
        <h1><i data-feather="credit-card" style="width:24px;height:24px;"></i> Accounts</h1>
        <p>Manage cash, bank & other accounts</p>
    </div>
    <a href="{{ route('accounts.create') }}" class="btn btn-primary"><i data-feather="plus"></i> New Account</a>
</div>
<div class="data-list animate-in" style="animation-delay:0.05s;">
    @forelse($accounts as $account)
        <div class="data-item">
            <div class="data-item-icon" style="{{ $account->type == 'cash' ? 'background:var(--warning-bg);color:var(--warning);' : ($account->type == 'bank' ? 'background:var(--info-bg);color:var(--info);' : 'background:var(--debt-bg);color:var(--debt);') }}">
                <i data-feather="{{ $account->type == 'cash' ? 'dollar-sign' : ($account->type == 'bank' ? 'credit-card' : 'briefcase') }}"></i>
            </div>
            <div class="data-item-content">
                <div class="data-item-title">{{ $account->name }}</div>
                <div class="data-item-subtitle flex gap-2 items-center mt-1">
                    <span class="badge badge-{{ $account->type }}">{{ ucfirst($account->type) }}</span>
                    <span>Balance: <strong class="{{ $account->balance >= 0 ? 'text-income' : 'text-expense' }}">₹{{ number_format($account->balance, 2) }}</strong></span>
                </div>
            </div>
            <div class="data-item-actions">
                <a href="{{ route('accounts.edit', $account) }}" class="btn btn-ghost btn-icon"><i data-feather="edit-2" style="width:16px;height:16px;"></i></a>
                <form action="{{ route('accounts.destroy', $account) }}" method="POST" onsubmit="return confirm('Delete this account?');">@csrf @method('DELETE')
                    <button type="submit" class="btn btn-ghost btn-icon" style="color:var(--expense);"><i data-feather="trash-2" style="width:16px;height:16px;"></i></button>
                </form>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="empty-state">
                <div class="empty-state-icon"><i data-feather="credit-card"></i></div>
                <h4>No accounts yet</h4>
                <p class="text-sm text-tertiary mt-1">Add a cash or bank account to track balances.</p>
                <a href="{{ route('accounts.create') }}" class="btn btn-primary mt-3"><i data-feather="plus"></i> Add Account</a>
            </div>
        </div>
    @endforelse
</div>
@endsection
