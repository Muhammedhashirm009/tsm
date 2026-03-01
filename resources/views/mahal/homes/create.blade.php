@extends('layouts.admin')
@section('title', 'Add Home')
@section('content')
<div class="page-header animate-in">
    <div class="page-header-left">
        <h1><i data-feather="plus" style="width:24px;height:24px;color:var(--accent);"></i> Add Home</h1>
        <p>Register a new home in the mahal</p>
    </div>
</div>

<div class="form-card animate-in" style="animation-delay:0.05s;">
    <form action="{{ route('mahal.homes.store') }}" method="POST">@csrf
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Home Number *</label>
                <input type="text" name="home_number" class="form-control" placeholder="e.g. 001, A-12" required value="{{ old('home_number') }}" autofocus>
                @error('home_number')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Owner / Head Name *</label>
                <input type="text" name="owner_name" class="form-control" placeholder="Full name" required value="{{ old('owner_name') }}">
                @error('owner_name')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact_number" class="form-control" placeholder="Phone number" value="{{ old('contact_number') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Members Count</label>
                <input type="number" name="members_count" class="form-control" placeholder="Number of family members" min="1" value="{{ old('members_count') }}">
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control" rows="2" placeholder="Street address or landmark">{{ old('address') }}</textarea>
        </div>
        <div class="divider"></div>
        <div class="flex-between" style="flex-wrap:wrap;gap:0.5rem;">
            <a href="{{ route('mahal.homes.index') }}" class="btn btn-secondary">Cancel</a>
            <div style="display:flex;gap:0.5rem;">
                <button type="submit" name="add_another" value="1" class="btn btn-secondary" style="border-color:var(--accent-border);color:var(--accent);"><i data-feather="plus"></i> Save & Add Another</button>
                <button type="submit" class="btn btn-primary"><i data-feather="check"></i> Save Home</button>
            </div>
        </div>
    </form>
</div>
@endsection
