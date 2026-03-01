@extends('layouts.admin')
@section('title', $book->name)
@section('content')
<div class="page-header animate-in">
    <div class="page-header-left">
        <h1><i data-feather="book-open" style="width:24px;height:24px;"></i> {{ $book->name }}@if($book->book_no) <span class="badge" style="background:var(--bg-body);color:var(--text-secondary);font-size:0.6rem;">No. {{ $book->book_no }}</span>@endif</h1>
        @if($book->description)<p>{{ $book->description }}</p>@endif
    </div>
    <a href="{{ route('books.index') }}" class="btn btn-secondary"><i data-feather="arrow-left"></i> Back</a>
</div>

{{-- Summary Cards — Income Only --}}
<div class="stats-grid animate-in" style="animation-delay:0.05s;grid-template-columns:repeat(3,1fr);">
    <div class="stat-card income">
        <div class="stat-icon income"><i data-feather="arrow-down-left"></i></div>
        <div class="stat-label">Total Income</div>
        <div class="stat-value text-income">₹{{ number_format($totalIncome, 2) }}</div>
    </div>
    <div class="stat-card" style="border-top:3px solid var(--text-tertiary);">
        <div class="stat-icon" style="background:var(--bg-body);color:var(--text-secondary);"><i data-feather="hash"></i></div>
        <div class="stat-label">Receipts Issued</div>
        <div class="stat-value">{{ $totalReceiptsCount }}</div>
    </div>
    <div class="stat-card" style="border-top:3px solid var(--info);">
        <div class="stat-icon" style="background:var(--info-bg);color:var(--info);"><i data-feather="file-text"></i></div>
        <div class="stat-label">Receipt Series</div>
        <div class="stat-value" style="font-size:0.95rem;">
            @if($book->receipt_prefix)
                {{ $book->receipt_prefix }}{{ str_pad($book->receipt_start_no, 4, '0', STR_PAD_LEFT) }}@if($book->receipt_end_no) → {{ $book->receipt_prefix }}{{ str_pad($book->receipt_end_no, 4, '0', STR_PAD_LEFT) }}@endif
            @else
                —
            @endif
        </div>
        @if($book->receipt_current_no > 0)
            <div class="text-xs text-accent mt-1">Last: {{ $book->receipt_prefix }}{{ str_pad($book->receipt_current_no, 4, '0', STR_PAD_LEFT) }}</div>
        @endif
    </div>
</div>

{{-- Search --}}
<div class="card mb-3 animate-in" style="padding:0.75rem;animation-delay:0.1s;">
    <div style="display:flex;align-items:center;gap:0.5rem;">
        <i data-feather="search" style="width:16px;height:16px;color:var(--text-tertiary);flex-shrink:0;"></i>
        <input type="text" id="bookSearch" class="form-control" style="border:none;background:transparent;box-shadow:none;padding:0.3rem;" placeholder="Search receipts in this book...">
    </div>
</div>

{{-- Income Receipts --}}
<div class="section animate-in" style="animation-delay:0.12s;">
    <div class="section-header">
        <h3 class="section-title"><i data-feather="arrow-down-left" style="width:16px;height:16px;color:var(--income);"></i> Income Receipts ({{ $receipts->count() }})</h3>
        <a href="{{ route('receipts.create', ['book_id' => $book->id]) }}" class="btn btn-primary btn-sm"><i data-feather="plus"></i> Add Receipt</a>
    </div>
    <div class="transaction-list" id="receiptsList">
        @forelse($receipts as $receipt)
            <div class="transaction-item" data-search="{{ strtolower(($receipt->receipt_no ?? '') . ' ' . ($receipt->category->name ?? '') . ' ' . ($receipt->received_from ?? '') . ' ' . ($receipt->description ?? '')) }}">
                <div class="transaction-icon income"><i data-feather="arrow-down-left"></i></div>
                <div class="transaction-details">
                    <div class="transaction-title flex gap-2 items-center">
                        {{ $receipt->category->name ?? '—' }}
                        @if($receipt->receipt_no)<span class="badge" style="background:var(--income-bg);color:var(--income);font-size:0.6rem;">#{{ $receipt->receipt_no }}</span>@endif
                    </div>
                    <div class="transaction-meta">
                        <span class="transaction-meta-item"><i data-feather="calendar"></i> {{ \Carbon\Carbon::parse($receipt->date)->format('d M Y') }}</span>
                        @if($receipt->received_from)<span class="transaction-meta-item"><i data-feather="user"></i> {{ $receipt->received_from }}</span>@endif
                        @if($receipt->account)<span class="badge badge-{{ $receipt->account->type }}" style="font-size:0.55rem;padding:0.1rem 0.35rem;">{{ $receipt->account->name }}</span>@endif
                    </div>
                    <div class="transaction-meta mt-1" style="font-size:0.68rem;opacity:0.7;">
                        <span class="transaction-meta-item"><i data-feather="clock"></i> {{ $receipt->created_at->format('d M Y h:i A') }}</span>
                        @if($receipt->creator)<span class="transaction-meta-item"><i data-feather="shield"></i> {{ $receipt->creator->name }}</span>@endif
                        @if($receipt->payment_method)<span class="transaction-meta-item"><i data-feather="credit-card"></i> {{ $receipt->payment_method }}</span>@endif
                    </div>
                </div>
                <div class="transaction-amount text-income">+₹{{ number_format($receipt->amount, 2) }}</div>
            </div>
        @empty
            <div class="text-center text-sm text-tertiary" style="padding:1rem;">No income receipts in this book.</div>
        @endforelse
    </div>
</div>

{{-- Mahal Donations --}}
@if($mahalDonations->count() > 0)
<div class="section animate-in" style="animation-delay:0.15s;">
    <div class="section-header">
        <h3 class="section-title"><i data-feather="heart" style="width:16px;height:16px;color:var(--accent);"></i> Mahal Donations ({{ $mahalDonations->count() }})</h3>
    </div>
    <div class="transaction-list" id="donationsList">
        @foreach($mahalDonations as $donation)
            <div class="transaction-item" data-search="{{ strtolower(($donation->receipt_no ?? '') . ' ' . ($donation->donor_name ?? '') . ' ' . ($donation->home->home_number ?? '') . ' ' . ($donation->home->owner_name ?? '')) }}">
                <div class="transaction-icon" style="background:var(--accent-bg);color:var(--accent);"><i data-feather="heart"></i></div>
                <div class="transaction-details">
                    <div class="transaction-title flex gap-2 items-center">
                        {{ $donation->donor_name ?? 'Anonymous' }}
                        @if($donation->receipt_no)<span class="badge" style="background:var(--income-bg);color:var(--income);font-size:0.6rem;">#{{ $donation->receipt_no }}</span>@endif
                        <span class="badge" style="background:var(--accent-bg);color:var(--accent);font-size:0.55rem;padding:0.1rem 0.35rem;">Mahal</span>
                    </div>
                    <div class="transaction-meta">
                        <span class="transaction-meta-item"><i data-feather="calendar"></i> {{ $donation->date->format('d M Y') }}</span>
                        @if($donation->home)<span class="transaction-meta-item"><i data-feather="home"></i> Home #{{ $donation->home->home_number }}</span>@endif
                        @if($donation->account)<span class="badge badge-{{ $donation->account->type }}" style="font-size:0.55rem;padding:0.1rem 0.35rem;">{{ $donation->account->name }}</span>@endif
                    </div>
                    <div class="transaction-meta mt-1" style="font-size:0.68rem;opacity:0.7;">
                        <span class="transaction-meta-item"><i data-feather="clock"></i> {{ $donation->created_at->format('d M Y h:i A') }}</span>
                        @if($donation->creator)<span class="transaction-meta-item"><i data-feather="shield"></i> {{ $donation->creator->name }}</span>@endif
                    </div>
                </div>
                <div class="transaction-amount text-income">+₹{{ number_format($donation->amount, 2) }}</div>
            </div>
        @endforeach
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
document.getElementById('bookSearch').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#receiptsList .transaction-item, #donationsList .transaction-item').forEach(function(item) {
        item.style.display = (item.getAttribute('data-search') || '').includes(q) ? '' : 'none';
    });
});
</script>
@endsection
