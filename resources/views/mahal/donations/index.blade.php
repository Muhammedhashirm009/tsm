@extends('layouts.admin')
@section('title', 'Mahal Donations')
@section('content')
<div class="page-header animate-in">
    <div class="page-header-left">
        <h1><i data-feather="heart" style="width:24px;height:24px;color:var(--income);"></i> Donations</h1>
        <p>{{ $donorsCount }} donors &middot; ₹{{ number_format($totalAmount, 2) }} total</p>
    </div>
    <a href="{{ route('mahal.donations.create') }}" class="btn btn-primary"><i data-feather="plus"></i> Add Donation</a>
</div>

{{-- Stats --}}
<div class="stats-grid animate-in" style="grid-template-columns:repeat(2,1fr);">
    <div class="stat-card income">
        <div class="stat-icon income"><i data-feather="heart"></i></div>
        <div class="stat-label">Total Collected</div>
        <div class="stat-value text-income">₹{{ number_format($totalAmount, 2) }}</div>
    </div>
    <div class="stat-card balance">
        <div class="stat-icon balance"><i data-feather="calendar"></i></div>
        <div class="stat-label">This Month</div>
        <div class="stat-value">₹{{ number_format($thisMonthAmount, 2) }}</div>
    </div>
</div>

{{-- Search --}}
<div class="card mb-3 animate-in" style="padding:0.75rem;">
    <div style="display:flex;align-items:center;gap:0.5rem;">
        <i data-feather="search" style="width:16px;height:16px;color:var(--text-tertiary);flex-shrink:0;"></i>
        <input type="text" id="donationSearch" class="form-control" style="border:none;background:transparent;box-shadow:none;padding:0.3rem;" placeholder="Search by donor, home, description...">
    </div>
</div>

<div class="transaction-list animate-in" style="animation-delay:0.05s;" id="donationList">
    @forelse($donations as $donation)
        <div class="transaction-item" data-search="{{ strtolower(($donation->donor_name ?? '') . ' ' . ($donation->home->home_number ?? '') . ' ' . ($donation->home->owner_name ?? '') . ' ' . ($donation->description ?? '') . ' ' . ($donation->payment_method ?? '') . ' ' . ($donation->book->name ?? '') . ' ' . ($donation->receipt_no ?? '')) }}">
            <div class="transaction-icon income"><i data-feather="heart"></i></div>
            <div class="transaction-details">
                <div class="transaction-title flex gap-2 items-center">
                    {{ $donation->donor_name ?? 'Anonymous' }}
                    @if($donation->receipt_no)<span class="badge" style="background:var(--income-bg);color:var(--income);font-size:0.6rem;">#{{ $donation->receipt_no }}</span>@endif
                </div>
                <div class="transaction-meta">
                    <span class="transaction-meta-item"><i data-feather="calendar"></i> {{ $donation->date->format('d M Y') }}</span>
                    <span class="transaction-meta-item"><i data-feather="credit-card"></i> {{ $donation->payment_method }}</span>
                    @if($donation->book)<span class="transaction-meta-item"><i data-feather="book-open"></i> {{ $donation->book->name }}</span>@endif
                    @if($donation->home)<span class="badge" style="background:var(--accent-bg);color:var(--accent);font-size:0.55rem;padding:0.1rem 0.35rem;">Home #{{ $donation->home->home_number }}</span>@endif
                    @if($donation->account)<span class="badge badge-{{ $donation->account->type }}" style="font-size:0.55rem;padding:0.1rem 0.35rem;">{{ $donation->account->name }}</span>@endif
                </div>
                @if($donation->description)
                <div class="transaction-meta mt-1" style="font-size:0.68rem;opacity:0.7;">
                    <span class="transaction-meta-item">{{ $donation->description }}</span>
                </div>
                @endif
                <div class="transaction-meta mt-1" style="font-size:0.68rem;opacity:0.7;">
                    <span class="transaction-meta-item"><i data-feather="clock"></i> {{ $donation->created_at->format('d M Y h:i A') }}</span>
                    @if($donation->creator)<span class="transaction-meta-item"><i data-feather="shield"></i> {{ $donation->creator->name }}</span>@endif
                </div>
            </div>
            <div class="transaction-amount text-income">+₹{{ number_format($donation->amount, 2) }}</div>
            @if(Auth::user()->canEdit())
            <div class="transaction-actions">
                <form action="{{ route('mahal.donations.destroy', $donation) }}" method="POST" onsubmit="return confirm('Delete this donation?');">@csrf @method('DELETE')
                    <button type="submit" class="btn btn-ghost btn-icon" style="color:var(--expense);"><i data-feather="trash-2" style="width:15px;height:15px;"></i></button>
                </form>
            </div>
            @endif
        </div>
    @empty
        <div class="card">
            <div class="empty-state">
                <div class="empty-state-icon" style="background:var(--income-bg);color:var(--income);"><i data-feather="heart"></i></div>
                <h4>No donations recorded</h4>
                <p class="text-sm text-tertiary mt-1">Start collecting donations for the mahal.</p>
                <a href="{{ route('mahal.donations.create') }}" class="btn btn-primary mt-3"><i data-feather="plus"></i> Add Donation</a>
            </div>
        </div>
    @endforelse
</div>
@endsection

@section('scripts')
<script>
document.getElementById('donationSearch').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#donationList .transaction-item').forEach(function(item) {
        const text = item.getAttribute('data-search') || '';
        item.style.display = text.includes(q) ? '' : 'none';
    });
});
</script>
@endsection
