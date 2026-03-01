@extends('layouts.admin')
@section('title', 'New Distribution Event')
@section('content')
<div class="page-header animate-in">
    <div class="page-header-left">
        <h1><i data-feather="gift" style="width:24px;height:24px;color:var(--expense);"></i> New Distribution Event</h1>
        <p>Create a distribution for Eid or other occasions</p>
    </div>
</div>

<div class="card mb-3 animate-in" style="padding:0.75rem;border-left:3px solid var(--accent);">
    <div style="display:flex;align-items:center;gap:0.65rem;">
        <div style="background:var(--accent-bg);color:var(--accent);border-radius:var(--radius-sm);padding:0.4rem;display:flex;"><i data-feather="info" style="width:16px;height:16px;"></i></div>
        <div>
            <div style="font-weight:600;font-size:0.85rem;">{{ $activeHomesCount }} active homes</div>
            <div class="text-xs text-tertiary">Records will be created for all active homes when you save.</div>
        </div>
    </div>
</div>

<div class="form-card animate-in" style="animation-delay:0.05s;">
    <form action="{{ route('mahal.distributions.store') }}" method="POST">@csrf
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Event Title *</label>
                <input type="text" name="title" class="form-control" placeholder="e.g. Eid-ul-Fitr 2026" required value="{{ old('title') }}" autofocus>
                @error('title')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Event Date *</label>
                <input type="date" name="event_date" class="form-control" required value="{{ old('event_date', date('Y-m-d')) }}">
                @error('event_date')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Items to Distribute</label>
                <input type="text" name="items_description" class="form-control" placeholder="e.g. Rice 5kg, Oil 1L, Dates" value="{{ old('items_description') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="planned" {{ old('status', 'planned') == 'planned' ? 'selected' : '' }}>Planned</option>
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="2" placeholder="Additional details about this event">{{ old('description') }}</textarea>
        </div>
        <div class="divider"></div>
        <div class="flex-between">
            <a href="{{ route('mahal.distributions.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary"><i data-feather="check"></i> Create Event</button>
        </div>
    </form>
</div>
@endsection
