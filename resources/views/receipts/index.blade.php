@extends('layouts.admin')
@section('title', 'Income (Receipts)')
@section('content')
<div class="page-header animate-in">
    <div class="page-header-left">
        <h1><i data-feather="arrow-down-left" style="width:24px;height:24px;color:var(--income);"></i> Income</h1>
        <p>All receipts & incoming funds</p>
    </div>
    <a href="{{ route('receipts.create') }}" class="btn btn-primary"><i data-feather="plus"></i> New Receipt</a>
</div>

{{-- Search bar --}}
<div class="card mb-3 animate-in" style="padding:0.75rem;">
    <div style="display:flex;align-items:center;gap:0.5rem;">
        <i data-feather="search" style="width:16px;height:16px;color:var(--text-tertiary);flex-shrink:0;"></i>
        <input type="text" id="receiptSearch" class="form-control" style="border:none;background:transparent;box-shadow:none;padding:0.3rem;" placeholder="Search receipts by name, category, book, receipt no...">
    </div>
</div>

<div class="transaction-list animate-in" style="animation-delay:0.05s;" id="receiptList">
    @forelse($receipts as $receipt)
        <div class="transaction-item" data-search="{{ strtolower(($receipt->receipt_no ?? '') . ' ' . ($receipt->category->name ?? '') . ' ' . ($receipt->book->name ?? '') . ' ' . ($receipt->received_from ?? '') . ' ' . ($receipt->description ?? '')) }}">
            <div class="transaction-icon income"><i data-feather="arrow-down-left"></i></div>
            <div class="transaction-details">
                <div class="transaction-title flex gap-2 items-center">
                    {{ $receipt->category->name ?? '—' }}
                    @if($receipt->receipt_no)<span class="badge" style="background:var(--income-bg);color:var(--income);font-size:0.6rem;">#{{ $receipt->receipt_no }}</span>@endif
                </div>
                <div class="transaction-meta">
                    <span class="transaction-meta-item"><i data-feather="calendar"></i> {{ \Carbon\Carbon::parse($receipt->date)->format('d M Y') }}</span>
                    <span class="transaction-meta-item"><i data-feather="book-open"></i> {{ $receipt->book->name ?? '—' }}</span>
                    @if($receipt->received_from)<span class="transaction-meta-item"><i data-feather="user"></i> {{ $receipt->received_from }}</span>@endif
                    @if($receipt->account)<span class="badge badge-{{ $receipt->account->type }}" style="font-size:0.55rem;padding:0.1rem 0.35rem;">{{ $receipt->account->name }}</span>@endif
                </div>
                <div class="transaction-meta mt-1" style="font-size:0.68rem;opacity:0.7;">
                    <span class="transaction-meta-item"><i data-feather="clock"></i> {{ $receipt->created_at->format('d M Y h:i A') }}</span>
                    @if($receipt->creator)<span class="transaction-meta-item"><i data-feather="shield"></i> {{ $receipt->creator->name }}</span>@endif
                </div>
            </div>
            <div class="transaction-amount text-income">+₹{{ number_format($receipt->amount, 2) }}</div>
            @if(Auth::user()->canEdit())
            <div class="transaction-actions">
                <a href="{{ route('receipts.edit', $receipt) }}" class="btn btn-ghost btn-icon"><i data-feather="edit-2" style="width:15px;height:15px;"></i></a>
                <form action="{{ route('receipts.destroy', $receipt) }}" method="POST" onsubmit="return confirm('Delete this receipt?');">@csrf @method('DELETE')
                    <button type="submit" class="btn btn-ghost btn-icon" style="color:var(--expense);"><i data-feather="trash-2" style="width:15px;height:15px;"></i></button>
                </form>
            </div>
            @endif
        </div>
    @empty
        <div class="card">
            <div class="empty-state">
                <div class="empty-state-icon" style="background:var(--income-bg);color:var(--income);"><i data-feather="arrow-down-left"></i></div>
                <h4>No receipts recorded</h4>
                <p class="text-sm text-tertiary mt-1">Start by adding your first income entry.</p>
                <a href="{{ route('receipts.create') }}" class="btn btn-primary mt-3"><i data-feather="plus"></i> Add Receipt</a>
            </div>
        </div>
    @endforelse
</div>
@endsection

@section('scripts')
<script>
document.getElementById('receiptSearch').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#receiptList .transaction-item').forEach(function(item) {
        const text = item.getAttribute('data-search') || '';
        item.style.display = text.includes(q) ? '' : 'none';
    });
});
</script>
@endsection
