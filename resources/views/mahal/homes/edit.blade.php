@extends('layouts.admin')
@section('title', 'Edit Home #' . $home->home_number)
@section('content')
<div class="page-header animate-in">
    <div class="page-header-left">
        <h1><i data-feather="edit-2" style="width:24px;height:24px;color:var(--accent);"></i> Edit Home #{{ $home->home_number }}</h1>
        <p>Update home details</p>
    </div>
</div>

<div class="form-card animate-in" style="animation-delay:0.05s;">
    <form action="{{ route('mahal.homes.update', $home) }}" method="POST">@csrf @method('PUT')
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Home Number *</label>
                <input type="text" name="home_number" class="form-control" required value="{{ old('home_number', $home->home_number) }}">
                @error('home_number')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Owner / Head Name *</label>
                <input type="text" name="owner_name" class="form-control" required value="{{ old('owner_name', $home->owner_name) }}">
                @error('owner_name')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number', $home->contact_number) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Members Count</label>
                <input type="number" name="members_count" class="form-control" min="1" value="{{ old('members_count', $home->members_count) }}">
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control" rows="2">{{ old('address', $home->address) }}</textarea>
        </div>
        <div class="divider"></div>
        <div class="flex-between">
            <a href="{{ route('mahal.homes.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary"><i data-feather="check"></i> Update Home</button>
        </div>
    </form>
</div>
@endsection
