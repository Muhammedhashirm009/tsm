@extends('layouts.admin')
@section('title', 'Edit Debt')
@section('content')
<div class="page-header animate-in"><div class="page-header-left"><h1><i data-feather="edit-2" style="width:24px;height:24px;"></i> Edit Debt</h1>@if($debt->creditor)<p>From: {{ $debt->creditor->name }}</p>@endif</div></div>
<div class="form-card animate-in" style="animation-delay:0.05s;">
    <form action="{{ route('debts.update', $debt) }}" method="POST">@csrf @method('PUT')
        <input type="hidden" name="type" value="borrowed">
        <div class="grid-2">
            <div class="form-group"><label class="form-label">Total Amount (₹)</label><input type="number" step="0.01" min="0.01" name="amount" class="form-control" required value="{{ old('amount', $debt->amount) }}">@error('amount')<div class="form-error">{{ $message }}</div>@enderror</div>
            <div class="form-group">
                <label class="form-label">Account</label>
                <select name="account_id" class="form-control" required>
                    <option value="">Select account</option>
                    @foreach($accounts as $acc)
                        <option value="{{ $acc->id }}" {{ old('account_id', $debt->account_id) == $acc->id ? 'selected' : '' }}>{{ $acc->name }} ({{ ucfirst($acc->type) }})</option>
                    @endforeach
                </select>
                @error('account_id')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group"><label class="form-label">Amount Repaid (₹)</label><input type="number" step="0.01" min="0" name="paid_amount" class="form-control" value="{{ old('paid_amount', $debt->paid_amount) }}" placeholder="0.00">@error('paid_amount')<div class="form-error">{{ $message }}</div>@enderror<div class="text-xs text-tertiary mt-1">Status auto-updates: pending → partial → paid</div></div>
            <div class="form-group"><label class="form-label">Date</label><input type="date" name="date" class="form-control" required value="{{ old('date', $debt->date) }}"></div>
        </div>
        <div class="form-group"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2">{{ old('description', $debt->description) }}</textarea></div>
        <div class="divider"></div>
        <div class="flex-between"><a href="{{ route('debts.index') }}" class="btn btn-secondary">Cancel</a><button type="submit" class="btn btn-primary"><i data-feather="check"></i> Update</button></div>
    </form>
</div>
@endsection
