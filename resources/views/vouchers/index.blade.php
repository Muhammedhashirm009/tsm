@extends('layouts.admin')
@section('title', 'Expense (Vouchers)')
@section('content')
<div class="page-header animate-in">
    <div class="page-header-left">
        <h1><i data-feather="arrow-up-right" style="width:24px;height:24px;color:var(--expense);"></i> Expenses</h1>
        <p>All vouchers & outgoing payments</p>
    </div>
    <a href="{{ route('vouchers.create') }}" class="btn btn-primary"><i data-feather="plus"></i> New Voucher</a>
</div>

{{-- Search bar --}}
<div class="card mb-3 animate-in" style="padding:0.75rem;">
    <div style="display:flex;align-items:center;gap:0.5rem;">
        <i data-feather="search" style="width:16px;height:16px;color:var(--text-tertiary);flex-shrink:0;"></i>
        <input type="text" id="voucherSearch" class="form-control" style="border:none;background:transparent;box-shadow:none;padding:0.3rem;" placeholder="Search vouchers by name, category...">
    </div>
</div>

<div class="transaction-list animate-in" style="animation-delay:0.05s;" id="voucherList">
    @forelse($vouchers as $voucher)
        <div class="transaction-item" data-search="{{ strtolower(($voucher->voucher_no ?? '') . ' ' . ($voucher->category->name ?? '') . ' ' . ($voucher->paid_to ?? '') . ' ' . ($voucher->description ?? '') . ' ' . ($voucher->account->name ?? '')) }}">
            <div class="transaction-icon expense"><i data-feather="arrow-up-right"></i></div>
            <div class="transaction-details">
                <div class="transaction-title flex gap-2 items-center">
                    {{ $voucher->category->name ?? '—' }}
                    @if($voucher->voucher_no)<span class="badge" style="background:var(--expense-bg);color:var(--expense);font-size:0.6rem;">#{{ $voucher->voucher_no }}</span>@endif
                </div>
                <div class="transaction-meta">
                    <span class="transaction-meta-item"><i data-feather="calendar"></i> {{ \Carbon\Carbon::parse($voucher->date)->format('d M Y') }}</span>
                    @if($voucher->paid_to)<span class="transaction-meta-item"><i data-feather="user"></i> {{ $voucher->paid_to }}</span>@endif
                    @if($voucher->account)<span class="badge badge-{{ $voucher->account->type }}" style="font-size:0.55rem;padding:0.1rem 0.35rem;">{{ $voucher->account->name }}</span>@endif
                    @if($voucher->payment_method)<span class="transaction-meta-item" style="font-size:0.65rem;"><i data-feather="credit-card"></i> {{ $voucher->payment_method }}</span>@endif
                </div>
                <div class="transaction-meta mt-1" style="font-size:0.68rem;opacity:0.7;">
                    <span class="transaction-meta-item"><i data-feather="clock"></i> {{ $voucher->created_at->format('d M Y h:i A') }}</span>
                    @if($voucher->creator)<span class="transaction-meta-item"><i data-feather="shield"></i> {{ $voucher->creator->name }}</span>@endif
                </div>
            </div>
            <div class="transaction-amount text-expense">−₹{{ number_format($voucher->amount, 2) }}</div>
            <div class="transaction-actions">
                <a href="{{ route('vouchers.edit', $voucher) }}" class="btn btn-ghost btn-icon"><i data-feather="edit-2" style="width:15px;height:15px;"></i></a>
                <form action="{{ route('vouchers.destroy', $voucher) }}" method="POST" onsubmit="return confirm('Delete this voucher?');">@csrf @method('DELETE')
                    <button type="submit" class="btn btn-ghost btn-icon" style="color:var(--expense);"><i data-feather="trash-2" style="width:15px;height:15px;"></i></button>
                </form>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="empty-state">
                <div class="empty-state-icon" style="background:var(--expense-bg);color:var(--expense);"><i data-feather="arrow-up-right"></i></div>
                <h4>No vouchers recorded</h4>
                <p class="text-sm text-tertiary mt-1">Start by adding your first expense entry.</p>
                <a href="{{ route('vouchers.create') }}" class="btn btn-primary mt-3"><i data-feather="plus"></i> Add Voucher</a>
            </div>
        </div>
    @endforelse
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var searchEl = document.getElementById('voucherSearch');
    if (searchEl) {
        searchEl.addEventListener('input', function() {
            var q = this.value.toLowerCase();
            document.querySelectorAll('#voucherList .transaction-item').forEach(function(item) {
                var text = item.getAttribute('data-search') || '';
                item.style.display = text.includes(q) ? '' : 'none';
            });
        });
    }
});
</script>
@endsection
