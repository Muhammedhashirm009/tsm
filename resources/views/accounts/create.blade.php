@extends('layouts.admin')
@section('title', 'Add Account')
@section('content')
<div class="page-header animate-in"><div class="page-header-left"><h1><i data-feather="plus" style="width:24px;height:24px;"></i> New Account</h1><p>Add a cash, bank or other account</p></div></div>
<div class="form-card animate-in" style="animation-delay:0.05s;">
    <form action="{{ route('accounts.store') }}" method="POST">@csrf
        <div class="grid-2">
            <div class="form-group"><label class="form-label">Account Name</label><input type="text" name="name" class="form-control" placeholder="e.g. Masjid Cash, SBI Account" required value="{{ old('name') }}">@error('name')<div class="form-error">{{ $message }}</div>@enderror</div>
            <div class="form-group"><label class="form-label">Type</label><select name="type" class="form-control" required><option value="cash" {{ old('type') == 'cash' ? 'selected' : '' }}>Cash</option><option value="bank" {{ old('type') == 'bank' ? 'selected' : '' }}>Bank</option><option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option></select>@error('type')<div class="form-error">{{ $message }}</div>@enderror</div>
        </div>
        <div class="form-group"><label class="form-label">Opening Balance (₹)</label><input type="number" step="0.01" name="opening_balance" class="form-control" placeholder="0.00" value="{{ old('opening_balance', '0') }}">@error('opening_balance')<div class="form-error">{{ $message }}</div>@enderror</div>
        <div class="form-group"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2" placeholder="Optional">{{ old('description') }}</textarea></div>
        <div class="divider"></div>
        <div class="flex-between"><a href="{{ route('accounts.index') }}" class="btn btn-secondary">Cancel</a><button type="submit" class="btn btn-primary"><i data-feather="check"></i> Save</button></div>
    </form>
</div>
@endsection
