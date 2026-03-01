@extends('layouts.admin')
@section('title', $distribution->title)
@section('content')
<div class="page-header animate-in">
    <div class="page-header-left">
        <h1><i data-feather="gift" style="width:24px;height:24px;color:var(--expense);"></i> {{ $distribution->title }}</h1>
        <p>{{ $distribution->event_date->format('d M Y') }} @if($distribution->items_description)&middot; {{ $distribution->items_description }}@endif</p>
    </div>
    <div style="display:flex;gap:0.5rem;align-items:center;">
        <form action="{{ route('mahal.distributions.updateStatus', $distribution) }}" method="POST" style="display:flex;gap:0.35rem;">@csrf
            <select name="status" class="form-control" style="width:auto;padding:0.3rem 0.6rem;font-size:0.8rem;">
                <option value="planned" {{ $distribution->status == 'planned' ? 'selected' : '' }}>Planned</option>
                <option value="active" {{ $distribution->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="completed" {{ $distribution->status == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
            <button type="submit" class="btn btn-secondary btn-sm">Update</button>
        </form>
    </div>
</div>

{{-- Progress stats --}}
<div class="stats-grid animate-in" style="grid-template-columns:repeat(3,1fr);">
    <div class="stat-card balance">
        <div class="stat-icon balance"><i data-feather="home"></i></div>
        <div class="stat-label">Total Homes</div>
        <div class="stat-value">{{ $totalHomes }}</div>
    </div>
    <div class="stat-card income">
        <div class="stat-icon income"><i data-feather="send"></i></div>
        <div class="stat-label">Tokens Given</div>
        <div class="stat-value text-accent" id="tokensGivenCount">{{ $tokensGiven }}</div>
        @if($totalHomes > 0)
        <div style="width:100%;height:4px;background:var(--bg-body);border-radius:2px;overflow:hidden;margin-top:0.35rem;">
            <div id="tokensBar" style="height:100%;width:{{ ($tokensGiven / $totalHomes) * 100 }}%;background:var(--accent);border-radius:2px;transition:width 0.3s;"></div>
        </div>
        @endif
    </div>
    <div class="stat-card expense">
        <div class="stat-icon expense"><i data-feather="check-circle"></i></div>
        <div class="stat-label">Collected</div>
        <div class="stat-value text-income" id="collectedCount">{{ $collected }}</div>
        @if($totalHomes > 0)
        <div style="width:100%;height:4px;background:var(--bg-body);border-radius:2px;overflow:hidden;margin-top:0.35rem;">
            <div id="collectedBar" style="height:100%;width:{{ ($collected / $totalHomes) * 100 }}%;background:var(--income);border-radius:2px;transition:width 0.3s;"></div>
        </div>
        @endif
    </div>
</div>

{{-- Search --}}
<div class="card mb-3 animate-in" style="padding:0.75rem;">
    <div style="display:flex;align-items:center;gap:0.5rem;">
        <i data-feather="search" style="width:16px;height:16px;color:var(--text-tertiary);flex-shrink:0;"></i>
        <input type="text" id="homeSearch" class="form-control" style="border:none;background:transparent;box-shadow:none;padding:0.3rem;flex:1;" placeholder="Search by home number or owner name...">
        <select id="filterStatus" class="form-control" style="width:auto;padding:0.3rem 0.6rem;font-size:0.8rem;">
            <option value="all">All</option>
            <option value="pending-token">Token Not Given</option>
            <option value="token-given">Token Given</option>
            <option value="pending-collection">Not Collected</option>
            <option value="collected">Collected</option>
        </select>
    </div>
</div>

{{-- Home Checklist --}}
<div class="animate-in" style="animation-delay:0.05s;" id="recordsList">
    @foreach($records as $record)
    <div class="card mb-2 record-item" data-search="{{ strtolower($record->home->home_number . ' ' . $record->home->owner_name) }}" data-token="{{ $record->token_given ? '1' : '0' }}" data-collected="{{ $record->collected ? '1' : '0' }}" style="padding:0.75rem;">
        <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap;">
            {{-- Home info --}}
            <div style="display:flex;align-items:center;gap:0.65rem;flex:1;min-width:140px;">
                <div style="width:36px;height:36px;border-radius:var(--radius-sm);background:{{ $record->collected ? 'var(--income-bg)' : ($record->token_given ? 'var(--accent-bg)' : 'var(--bg-body)') }};display:flex;align-items:center;justify-content:center;color:{{ $record->collected ? 'var(--income)' : ($record->token_given ? 'var(--accent)' : 'var(--text-tertiary)') }};font-weight:700;font-size:0.75rem;">
                    {{ $record->home->home_number }}
                </div>
                <div>
                    <div style="font-weight:600;font-size:0.85rem;">{{ $record->home->owner_name }}</div>
                    @if($record->home->contact_number)<div class="text-xs text-tertiary">{{ $record->home->contact_number }}</div>@endif
                </div>
            </div>

            {{-- Token Given toggle --}}
            <div style="display:flex;align-items:center;gap:0.5rem;">
                <button class="btn {{ $record->token_given ? 'btn-primary' : 'btn-secondary' }} btn-sm toggle-btn"
                    data-record-id="{{ $record->id }}"
                    data-action="token"
                    data-url="{{ route('mahal.distributions.toggleToken', $record->id) }}"
                    style="min-width:110px;font-size:0.75rem;{{ $record->token_given ? '' : 'opacity:0.7;' }}">
                    <i data-feather="{{ $record->token_given ? 'check-square' : 'square' }}" style="width:14px;height:14px;"></i>
                    Token {{ $record->token_given ? 'Given' : 'Pending' }}
                </button>

                {{-- Collected toggle --}}
                <button class="btn {{ $record->collected ? 'btn-primary' : 'btn-secondary' }} btn-sm toggle-btn"
                    data-record-id="{{ $record->id }}"
                    data-action="collected"
                    data-url="{{ route('mahal.distributions.toggleCollected', $record->id) }}"
                    style="min-width:110px;font-size:0.75rem;{{ $record->collected ? 'background:var(--income);border-color:var(--income);' : 'opacity:0.7;' }}">
                    <i data-feather="{{ $record->collected ? 'check-square' : 'square' }}" style="width:14px;height:14px;"></i>
                    {{ $record->collected ? 'Collected' : 'Not Yet' }}
                </button>
            </div>
        </div>
        @if($record->token_given_at || $record->collected_at)
        <div class="text-xs text-tertiary mt-1" style="margin-left:48px;">
            @if($record->token_given_at)<span>Token: {{ $record->token_given_at->format('d M h:i A') }}</span>@endif
            @if($record->token_given_at && $record->collected_at) &middot; @endif
            @if($record->collected_at)<span>Collected: {{ $record->collected_at->format('d M h:i A') }}</span>@endif
        </div>
        @endif
    </div>
    @endforeach
</div>

@if($totalHomes === 0)
<div class="card">
    <div class="empty-state">
        <div class="empty-state-icon" style="background:var(--expense-bg);color:var(--expense);"><i data-feather="home"></i></div>
        <h4>No homes in this event</h4>
        <p class="text-sm text-tertiary mt-1">Add active homes first, then create a new event.</p>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
// AJAX toggle for token given / collected
document.querySelectorAll('.toggle-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var url = this.dataset.url;
        var action = this.dataset.action;
        var button = this;

        button.disabled = true;
        button.style.opacity = '0.5';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                // Update button appearance
                if (action === 'token') {
                    if (data.token_given) {
                        button.className = 'btn btn-primary btn-sm toggle-btn';
                        button.style.opacity = '1';
                        button.innerHTML = '<i data-feather="check-square" style="width:14px;height:14px;"></i> Token Given';
                        button.closest('.record-item').dataset.token = '1';
                    } else {
                        button.className = 'btn btn-secondary btn-sm toggle-btn';
                        button.style.opacity = '0.7';
                        button.innerHTML = '<i data-feather="square" style="width:14px;height:14px;"></i> Token Pending';
                        button.closest('.record-item').dataset.token = '0';
                    }
                } else {
                    if (data.collected) {
                        button.className = 'btn btn-primary btn-sm toggle-btn';
                        button.style.opacity = '1';
                        button.style.background = 'var(--income)';
                        button.style.borderColor = 'var(--income)';
                        button.innerHTML = '<i data-feather="check-square" style="width:14px;height:14px;"></i> Collected';
                        button.closest('.record-item').dataset.collected = '1';
                    } else {
                        button.className = 'btn btn-secondary btn-sm toggle-btn';
                        button.style.opacity = '0.7';
                        button.style.background = '';
                        button.style.borderColor = '';
                        button.innerHTML = '<i data-feather="square" style="width:14px;height:14px;"></i> Not Yet';
                        button.closest('.record-item').dataset.collected = '0';
                    }
                }

                // Update counters
                var allItems = document.querySelectorAll('.record-item');
                var tg = 0, cl = 0;
                allItems.forEach(function(item) {
                    if (item.dataset.token === '1') tg++;
                    if (item.dataset.collected === '1') cl++;
                });
                document.getElementById('tokensGivenCount').textContent = tg;
                document.getElementById('collectedCount').textContent = cl;
                var total = allItems.length;
                if (total > 0) {
                    document.getElementById('tokensBar').style.width = ((tg / total) * 100) + '%';
                    document.getElementById('collectedBar').style.width = ((cl / total) * 100) + '%';
                }

                feather.replace();
            }
            button.disabled = false;
        })
        .catch(function() {
            button.disabled = false;
            button.style.opacity = '1';
            alert('Failed to update. Please try again.');
        });
    });
});

// Client-side search & filter
var searchEl = document.getElementById('homeSearch');
var filterEl = document.getElementById('filterStatus');

function filterRecords() {
    var q = searchEl.value.toLowerCase();
    var f = filterEl.value;
    document.querySelectorAll('.record-item').forEach(function(item) {
        var searchMatch = !q || (item.getAttribute('data-search') || '').includes(q);
        var filterMatch = true;

        if (f === 'pending-token') filterMatch = item.dataset.token === '0';
        else if (f === 'token-given') filterMatch = item.dataset.token === '1';
        else if (f === 'pending-collection') filterMatch = item.dataset.collected === '0';
        else if (f === 'collected') filterMatch = item.dataset.collected === '1';

        item.style.display = (searchMatch && filterMatch) ? '' : 'none';
    });
}

searchEl.addEventListener('input', filterRecords);
filterEl.addEventListener('change', filterRecords);
</script>
@endsection
