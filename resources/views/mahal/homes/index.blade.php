@extends('layouts.admin')
@section('title', 'Homes')
@section('content')
<div class="page-header animate-in">
    <div class="page-header-left">
        <h1><i data-feather="home" style="width:24px;height:24px;color:var(--accent);"></i> Homes</h1>
        <p>{{ $totalHomes }} total &middot; {{ $activeHomes }} active</p>
    </div>
    <a href="{{ route('mahal.homes.create') }}" class="btn btn-primary"><i data-feather="plus"></i> Add Home</a>
</div>

{{-- Search & Filter --}}
<div class="card mb-3 animate-in" style="padding:0.75rem;">
    <form method="GET" action="{{ route('mahal.homes.index') }}" style="display:flex;align-items:center;gap:0.5rem;flex-wrap:wrap;">
        <i data-feather="search" style="width:16px;height:16px;color:var(--text-tertiary);flex-shrink:0;"></i>
        <input type="text" name="search" class="form-control" style="border:none;background:transparent;box-shadow:none;padding:0.3rem;flex:1;min-width:150px;" placeholder="Search by number, name, phone..." value="{{ request('search') }}">
        <select name="status" class="form-control" style="width:auto;padding:0.3rem 0.6rem;font-size:0.8rem;" onchange="this.form.submit()">
            <option value="">All Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <button type="submit" class="btn btn-secondary btn-sm"><i data-feather="search"></i></button>
        @if(request('search') || request('status'))
            <a href="{{ route('mahal.homes.index') }}" class="btn btn-ghost btn-sm">Clear</a>
        @endif
    </form>
</div>

<div class="transaction-list animate-in" style="animation-delay:0.05s;">
    @forelse($homes as $home)
        <div class="transaction-item" style="{{ !$home->is_active ? 'opacity:0.5;' : '' }}">
            <div class="transaction-icon {{ $home->is_active ? 'income' : 'expense' }}">
                <i data-feather="home"></i>
            </div>
            <div class="transaction-details">
                <div class="transaction-title flex gap-2 items-center">
                    #{{ $home->home_number }} — {{ $home->owner_name }}
                    @if(!$home->is_active)<span class="badge" style="background:var(--expense-bg);color:var(--expense);font-size:0.55rem;padding:0.1rem 0.35rem;">Inactive</span>@endif
                </div>
                <div class="transaction-meta">
                    @if($home->contact_number)<span class="transaction-meta-item"><i data-feather="phone"></i> {{ $home->contact_number }}</span>@endif
                    @if($home->members_count)<span class="transaction-meta-item"><i data-feather="users"></i> {{ $home->members_count }} members</span>@endif
                    @if($home->address)<span class="transaction-meta-item"><i data-feather="map-pin"></i> {{ Str::limit($home->address, 40) }}</span>@endif
                </div>
            </div>
            <div class="transaction-actions">
                <form action="{{ route('mahal.homes.toggleActive', $home) }}" method="POST" style="display:inline;">@csrf
                    <button type="submit" class="btn btn-ghost btn-icon" title="{{ $home->is_active ? 'Deactivate' : 'Activate' }}">
                        <i data-feather="{{ $home->is_active ? 'eye-off' : 'eye' }}" style="width:15px;height:15px;"></i>
                    </button>
                </form>
                <a href="{{ route('mahal.homes.edit', $home) }}" class="btn btn-ghost btn-icon"><i data-feather="edit-2" style="width:15px;height:15px;"></i></a>
                <form action="{{ route('mahal.homes.destroy', $home) }}" method="POST" onsubmit="return confirm('Delete home #{{ $home->home_number }}?');" style="display:inline;">@csrf @method('DELETE')
                    <button type="submit" class="btn btn-ghost btn-icon" style="color:var(--expense);"><i data-feather="trash-2" style="width:15px;height:15px;"></i></button>
                </form>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="empty-state">
                <div class="empty-state-icon" style="background:var(--accent-bg);color:var(--accent);"><i data-feather="home"></i></div>
                <h4>No homes added yet</h4>
                <p class="text-sm text-tertiary mt-1">Start by adding homes in the mahal.</p>
                <a href="{{ route('mahal.homes.create') }}" class="btn btn-primary mt-3"><i data-feather="plus"></i> Add Home</a>
            </div>
        </div>
    @endforelse
</div>
@endsection
