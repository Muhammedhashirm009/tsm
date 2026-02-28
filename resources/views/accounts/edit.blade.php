@extends('layouts.admin')
@section('title', 'Edit Account')
@section('content')
<div class="page-header animate-in"><div class="page-header-left"><h1><i data-feather="edit-2" style="width:24px;height:24px;"></i> Edit Account</h1></div></div>
<div class="form-card animate-in" style="animation-delay:0.05s;">
    <form action="{{ route('accounts.update', $account) }}" method="POST">@csrf @method('PUT')
        <div class="grid-2">
            <div class="form-group"><label class="form-label">Account Name</label><input type="text" name="name" class="form-control" required value="{{ old('name', $account->name) }}">@error('name')<div class="form-error">{{ $message }}</div>@enderror</div>
            <div class="form-group"><label class="form-label">Type</label><select name="type" class="form-control" required><option value="cash" {{ old('type', $account->type) == 'cash' ? 'selected' : '' }}>Cash</option><option value="bank" {{ old('type', $account->type) == 'bank' ? 'selected' : '' }}>Bank</option><option value="other" {{ old('type', $account->type) == 'other' ? 'selected' : '' }}>Other</option></select></div>
        </div>
        <div class="form-group"><label class="form-label">Opening Balance (₹)</label><input type="number" step="0.01" name="opening_balance" class="form-control" value="{{ old('opening_balance', $account->opening_balance) }}"></div>
        <div class="form-group"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2">{{ old('description', $account->description) }}</textarea></div>
        <div class="divider"></div>
        <div class="flex-between"><a href="{{ route('accounts.index') }}" class="btn btn-secondary">Cancel</a><button type="submit" class="btn btn-primary"><i data-feather="check"></i> Update</button></div>
    </form>
</div>
@endsection
