@extends('layouts.admin')
@section('title', 'Creditors')
@section('content')
<div class="page-header animate-in">
    <div class="page-header-left">
        <h1><i data-feather="users" style="width:24px;height:24px;"></i> Creditors</h1>
        <p>People the masjid has borrowed from</p>
    </div>
</div>

@if($totalOutstanding > 0)
<div class="account-card mb-4 animate-in" style="border-left:3px solid var(--debt);">
    <div class="account-card-icon other"><i data-feather="alert-circle" style="width:18px;height:18px;"></i></div>
    <div class="account-card-info">
        <div class="label">Total Outstanding To All Creditors</div>
        <div class="value text-debt">₹{{ number_format($totalOutstanding, 2) }}</div>
    </div>
</div>
@endif

<div class="card mb-3 animate-in" style="padding:0.75rem;">
    <div style="display:flex;align-items:center;gap:0.5rem;">
        <i data-feather="search" style="width:16px;height:16px;color:var(--text-tertiary);flex-shrink:0;"></i>
        <input type="text" id="creditorSearch" class="form-control" style="border:none;background:transparent;box-shadow:none;padding:0.3rem;" placeholder="Search creditors...">
    </div>
</div>

<div class="data-list animate-in" style="animation-delay:0.05s;" id="creditorList">
    @forelse($creditors as $creditor)
        <a href="{{ route('creditors.show', $creditor) }}" class="data-item" style="text-decoration:none;color:inherit;" data-search="{{ strtolower($creditor->name . ' ' . ($creditor->phone ?? '')) }}">
            <div class="data-item-icon" style="background:var(--debt-bg);color:var(--debt);"><i data-feather="user"></i></div>
            <div class="data-item-content">
                <div class="data-item-title">{{ $creditor->name }}</div>
                <div class="data-item-subtitle flex gap-2 items-center mt-1">
                    @if($creditor->phone)<span><i data-feather="phone" style="width:11px;height:11px;display:inline;"></i> {{ $creditor->phone }}</span>@endif
                    <span>{{ $creditor->debts_count }} debt{{ $creditor->debts_count != 1 ? 's' : '' }}</span>
                </div>
            </div>
            <div style="text-align:right;">
                @if($creditor->outstanding_val > 0)
                    <div class="font-bold text-debt" style="font-size:1rem;">₹{{ number_format($creditor->outstanding_val, 2) }}</div>
                    <div class="text-xs text-tertiary">outstanding</div>
                @else
                    <span class="badge badge-paid">Settled</span>
                @endif
            </div>
        </a>
    @empty
        <div class="card">
            <div class="empty-state">
                <div class="empty-state-icon" style="background:var(--debt-bg);color:var(--debt);"><i data-feather="users"></i></div>
                <h4>No creditors yet</h4>
                <p class="text-sm text-tertiary mt-1">Creditors are created automatically when you record a debt.</p>
                <a href="{{ route('debts.create') }}" class="btn btn-primary mt-3"><i data-feather="plus"></i> Record a Debt</a>
            </div>
        </div>
    @endforelse
</div>
@endsection

@section('scripts')
<script>
document.getElementById('creditorSearch').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#creditorList .data-item').forEach(function(item) {
        item.style.display = (item.getAttribute('data-search') || '').includes(q) ? '' : 'none';
    });
});
</script>
@endsection
