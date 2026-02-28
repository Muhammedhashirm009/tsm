@extends('layouts.admin')
@section('title', 'All Transactions')
@section('content')
<div class="page-header animate-in">
    <div class="page-header-left">
        <h1><i data-feather="list" style="width:24px;height:24px;"></i> All Transactions</h1>
        <p>Complete financial overview</p>
    </div>
</div>

{{-- Summary strip --}}
<div class="stats-grid animate-in" style="animation-delay:0.03s;grid-template-columns:repeat(3,1fr);">
    <div class="stat-card income">
        <div class="stat-icon income"><i data-feather="arrow-down-left"></i></div>
        <div class="stat-label">Income</div>
        <div class="stat-value text-income">₹{{ number_format($totalIncome, 2) }}</div>
    </div>
    <div class="stat-card expense">
        <div class="stat-icon expense"><i data-feather="arrow-up-right"></i></div>
        <div class="stat-label">Expense</div>
        <div class="stat-value text-expense">₹{{ number_format($totalExpense, 2) }}</div>
    </div>
    <div class="stat-card balance">
        <div class="stat-icon balance"><i data-feather="briefcase"></i></div>
        <div class="stat-label">Net</div>
        <div class="stat-value">₹{{ number_format($totalIncome - $totalExpense, 2) }}</div>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-3 animate-in" style="padding:0.85rem;animation-delay:0.06s;">
    <form method="GET" action="{{ route('transactions.index') }}" id="filterForm">
        <div style="display:flex;flex-wrap:wrap;gap:0.5rem;align-items:flex-end;">
            {{-- Type tabs --}}
            <div class="filter-tabs">
                <button type="submit" name="type" value="all" class="filter-tab {{ $typeFilter == 'all' ? 'active' : '' }}">All</button>
                <button type="submit" name="type" value="income" class="filter-tab {{ $typeFilter == 'income' ? 'active income' : '' }}">Income</button>
                <button type="submit" name="type" value="expense" class="filter-tab {{ $typeFilter == 'expense' ? 'active expense' : '' }}">Expense</button>
            </div>
            <select name="book_id" class="form-control" style="max-width:160px;padding:0.4rem 0.6rem;font-size:0.8rem;" onchange="document.getElementById('filterForm').submit();">
                <option value="">All Books</option>
                @foreach($books as $b)
                    <option value="{{ $b->id }}" {{ request('book_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                @endforeach
            </select>
            <select name="category_id" class="form-control" style="max-width:160px;padding:0.4rem 0.6rem;font-size:0.8rem;" onchange="document.getElementById('filterForm').submit();">
                <option value="">All Categories</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
            <select name="account_id" class="form-control" style="max-width:150px;padding:0.4rem 0.6rem;font-size:0.8rem;" onchange="document.getElementById('filterForm').submit();">
                <option value="">All Accounts</option>
                @foreach($accounts as $a)
                    <option value="{{ $a->id }}" {{ request('account_id') == $a->id ? 'selected' : '' }}>{{ $a->name }}</option>
                @endforeach
            </select>
            <input type="date" name="date_from" class="form-control" style="max-width:140px;padding:0.4rem 0.6rem;font-size:0.8rem;" value="{{ request('date_from') }}" placeholder="From" onchange="document.getElementById('filterForm').submit();">
            <input type="date" name="date_to" class="form-control" style="max-width:140px;padding:0.4rem 0.6rem;font-size:0.8rem;" value="{{ request('date_to') }}" placeholder="To" onchange="document.getElementById('filterForm').submit();">
            @if(request()->hasAny(['book_id','category_id','account_id','date_from','date_to','type']))
                <a href="{{ route('transactions.index') }}" class="btn btn-ghost btn-sm" style="color:var(--expense);"><i data-feather="x" style="width:14px;height:14px;"></i> Clear</a>
            @endif
        </div>
    </form>
</div>

{{-- Search --}}
<div class="card mb-3 animate-in" style="padding:0.75rem;animation-delay:0.09s;">
    <div style="display:flex;align-items:center;gap:0.5rem;">
        <i data-feather="search" style="width:16px;height:16px;color:var(--text-tertiary);flex-shrink:0;"></i>
        <input type="text" id="txnSearch" class="form-control" style="border:none;background:transparent;box-shadow:none;padding:0.3rem;" placeholder="Search by category, person, receipt no, description...">
    </div>
</div>

{{-- Transaction list --}}
<div class="transaction-list animate-in" style="animation-delay:0.12s;" id="txnList">
    @forelse($transactions as $txn)
        <div class="transaction-item" data-search="{{ strtolower(($txn->ref_no ?? '') . ' ' . $txn->category_name . ' ' . $txn->book_name . ' ' . ($txn->person ?? '') . ' ' . ($txn->description ?? '')) }}">
            <div class="transaction-icon {{ $txn->type }}">
                <i data-feather="{{ $txn->type == 'income' ? 'arrow-down-left' : 'arrow-up-right' }}"></i>
            </div>
            <div class="transaction-details">
                <div class="transaction-title flex gap-2 items-center">
                    {{ $txn->category_name }}
                    @if($txn->ref_no)
                        <span class="badge" style="background:{{ $txn->type == 'income' ? 'var(--income-bg)' : 'var(--expense-bg)' }};color:{{ $txn->type == 'income' ? 'var(--income)' : 'var(--expense)' }};font-size:0.6rem;">#{{ $txn->ref_no }}</span>
                    @endif
                    <span class="badge badge-{{ $txn->type }}">{{ ucfirst($txn->type) }}</span>
                </div>
                <div class="transaction-meta">
                    <span class="transaction-meta-item"><i data-feather="calendar"></i> {{ \Carbon\Carbon::parse($txn->date)->format('d M Y') }}</span>
                    <span class="transaction-meta-item"><i data-feather="book-open"></i> {{ $txn->book_name }}</span>
                    @if($txn->person)<span class="transaction-meta-item"><i data-feather="user"></i> {{ $txn->person }}</span>@endif
                    @if($txn->account)<span class="badge badge-{{ $txn->account->type }}" style="font-size:0.55rem;padding:0.1rem 0.35rem;">{{ $txn->account->name }}</span>@endif
                </div>
                <div class="transaction-meta mt-1" style="font-size:0.68rem;opacity:0.7;">
                    <span class="transaction-meta-item"><i data-feather="clock"></i> {{ $txn->created_at->format('d M Y h:i A') }}</span>
                    @if($txn->creator_name)<span class="transaction-meta-item"><i data-feather="shield"></i> {{ $txn->creator_name }}</span>@endif
                    @if($txn->payment_method)<span class="transaction-meta-item"><i data-feather="credit-card"></i> {{ $txn->payment_method }}</span>@endif
                </div>
            </div>
            <div class="transaction-amount {{ $txn->type == 'income' ? 'text-income' : 'text-expense' }}">
                {{ $txn->type == 'income' ? '+' : '−' }}₹{{ number_format($txn->amount, 2) }}
            </div>
        </div>
    @empty
        <div class="card">
            <div class="empty-state">
                <div class="empty-state-icon"><i data-feather="list"></i></div>
                <h4>No transactions found</h4>
                <p class="text-sm text-tertiary mt-1">Try adjusting your filters or add receipts/vouchers.</p>
            </div>
        </div>
    @endforelse
</div>

<div class="text-center text-sm text-tertiary mt-3 animate-in" style="animation-delay:0.15s;">
    Showing {{ $transactions->count() }} transaction{{ $transactions->count() !== 1 ? 's' : '' }}
</div>
@endsection

@section('scripts')
<script>
document.getElementById('txnSearch').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#txnList .transaction-item').forEach(function(item) {
        item.style.display = (item.getAttribute('data-search') || '').includes(q) ? '' : 'none';
    });
});
</script>
@endsection
