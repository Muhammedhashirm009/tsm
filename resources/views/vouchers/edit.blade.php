@extends('layouts.admin')
@section('title', 'Edit Voucher')
@section('content')
<div class="page-header animate-in"><div class="page-header-left"><h1><i data-feather="edit-2" style="width:24px;height:24px;color:var(--expense);"></i> Edit Voucher</h1></div></div>
<div class="form-card animate-in" style="animation-delay:0.05s;">
    <form action="{{ route('vouchers.update', $voucher) }}" method="POST">@csrf @method('PUT')
        <div class="grid-2">
            <div class="form-group"><label class="form-label">Category</label><select name="category_id" class="form-control" required>@foreach($categories as $cat)<option value="{{ $cat->id }}" {{ old('category_id', $voucher->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach</select></div>
            <div class="form-group"><label class="form-label">Account</label><select name="account_id" id="accountSelect" class="form-control"><option value="" data-type="">No account</option>@foreach($accounts as $acc)<option value="{{ $acc->id }}" data-type="{{ $acc->type }}" {{ old('account_id', $voucher->account_id) == $acc->id ? 'selected' : '' }}>{{ $acc->name }} ({{ ucfirst($acc->type) }})</option>@endforeach</select></div>
            <div class="form-group"><label class="form-label">Amount (₹)</label><input type="number" step="0.01" min="0.01" name="amount" class="form-control" required value="{{ old('amount', $voucher->amount) }}"></div>
            <div class="form-group"><label class="form-label">Date</label><input type="date" name="date" class="form-control" required value="{{ old('date', $voucher->date) }}"></div>
            <div class="form-group"><label class="form-label">Paid To</label><input type="text" name="paid_to" class="form-control" value="{{ old('paid_to', $voucher->paid_to) }}"></div>
            <div class="form-group"><label class="form-label">Payment Method</label><select name="payment_method" id="paymentMethod" class="form-control"><option value="Cash" {{ old('payment_method', $voucher->payment_method) == 'Cash' ? 'selected' : '' }}>Cash</option><option value="Bank Transfer" {{ old('payment_method', $voucher->payment_method) == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option><option value="Cheque" {{ old('payment_method', $voucher->payment_method) == 'Cheque' ? 'selected' : '' }}>Cheque</option><option value="Other" {{ old('payment_method', $voucher->payment_method) == 'Other' ? 'selected' : '' }}>Other</option></select></div>
        </div>
        <div class="form-group"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2">{{ old('description', $voucher->description) }}</textarea></div>
        <div class="divider"></div>
        <div class="flex-between"><a href="{{ route('vouchers.index') }}" class="btn btn-secondary">Cancel</a><button type="submit" class="btn btn-primary"><i data-feather="check"></i> Update</button></div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('accountSelect').addEventListener('change', function() {
    const type = this.options[this.selectedIndex].dataset.type;
    const pm = document.getElementById('paymentMethod');
    if (type === 'cash') pm.value = 'Cash';
    else if (type === 'bank') pm.value = 'Bank Transfer';
});
</script>
@endsection
