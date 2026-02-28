@extends('layouts.admin')
@section('title', 'Edit Receipt')
@section('content')
<div class="page-header animate-in">
    <div class="page-header-left">
        <h1><i data-feather="edit-2" style="width:24px;height:24px;color:var(--income);"></i> Edit Receipt</h1>
        @if($receipt->receipt_no)<p>Receipt #{{ $receipt->receipt_no }}</p>@endif
    </div>
</div>
<div class="form-card animate-in" style="animation-delay:0.05s;">
    <form action="{{ route('receipts.update', $receipt) }}" method="POST">@csrf @method('PUT')
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Book</label>
                <div class="searchable-select">
                    <input type="text" class="form-control search-input" id="bookSearch" placeholder="Search books..." autocomplete="off">
                    <select name="book_id" id="bookSelect" class="form-control" required style="display:none;">
                        @foreach($books as $book)
                            <option value="{{ $book->id }}" data-label="{{ $book->name }}{{ $book->book_no ? ' (#'.$book->book_no.')' : '' }}" {{ old('book_id', $receipt->book_id) == $book->id ? 'selected' : '' }}>{{ $book->name }}</option>
                        @endforeach
                    </select>
                    <div class="search-options" id="bookOptions"></div>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Category</label>
                <div class="searchable-select">
                    <input type="text" class="form-control search-input" id="categorySearch" placeholder="Search categories..." autocomplete="off">
                    <select name="category_id" id="categorySelect" class="form-control" required style="display:none;">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" data-label="{{ $cat->name }}" {{ old('category_id', $receipt->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <div class="search-options" id="categoryOptions"></div>
                </div>
            </div>
            <div class="form-group"><label class="form-label">Account</label><select name="account_id" class="form-control"><option value="">— No account —</option>@foreach($accounts as $acc)<option value="{{ $acc->id }}" {{ old('account_id', $receipt->account_id) == $acc->id ? 'selected' : '' }}>{{ $acc->name }} ({{ ucfirst($acc->type) }})</option>@endforeach</select></div>
            <div class="form-group"><label class="form-label">Amount (₹)</label><input type="number" step="0.01" name="amount" class="form-control" required value="{{ old('amount', $receipt->amount) }}"></div>
            <div class="form-group"><label class="form-label">Date</label><input type="date" name="date" class="form-control" required value="{{ old('date', $receipt->date) }}"></div>
            <div class="form-group"><label class="form-label">Received From</label><input type="text" name="received_from" class="form-control" value="{{ old('received_from', $receipt->received_from) }}"></div>
            <div class="form-group"><label class="form-label">Payment Method</label><select name="payment_method" class="form-control"><option value="Cash" {{ old('payment_method', $receipt->payment_method) == 'Cash' ? 'selected' : '' }}>Cash</option><option value="Bank Transfer" {{ old('payment_method', $receipt->payment_method) == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option><option value="Cheque" {{ old('payment_method', $receipt->payment_method) == 'Cheque' ? 'selected' : '' }}>Cheque</option><option value="Other" {{ old('payment_method', $receipt->payment_method) == 'Other' ? 'selected' : '' }}>Other</option></select></div>
        </div>
        <div class="form-group"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2">{{ old('description', $receipt->description) }}</textarea></div>
        <div class="divider"></div>
        <div class="flex-between"><a href="{{ route('receipts.index') }}" class="btn btn-secondary">Cancel</a><button type="submit" class="btn btn-primary"><i data-feather="check"></i> Update</button></div>
    </form>
</div>
@endsection

@section('scripts')
<script>
function initSearchableSelect(searchId, selectId, optionsId) {
    const searchEl = document.getElementById(searchId);
    const selectEl = document.getElementById(selectId);
    const optionsEl = document.getElementById(optionsId);
    const options = Array.from(selectEl.options).filter(o => o.value);
    const selected = selectEl.querySelector('option:checked');
    if (selected && selected.value) { searchEl.value = selected.getAttribute('data-label') || selected.text; searchEl.classList.add('has-value'); }
    function showOptions(filter) {
        const q = (filter || '').toLowerCase(); let html = '';
        options.forEach(o => { const label = o.getAttribute('data-label') || o.text; if (label.toLowerCase().includes(q)) { html += '<div class="search-option" data-value="' + o.value + '" data-label="' + label + '">' + label + '</div>'; } });
        optionsEl.innerHTML = html || '<div class="search-option-empty">No results</div>'; optionsEl.style.display = 'block';
        optionsEl.querySelectorAll('.search-option').forEach(opt => { opt.addEventListener('mousedown', function(e) { e.preventDefault(); selectEl.value = this.dataset.value; searchEl.value = this.dataset.label; searchEl.classList.add('has-value'); optionsEl.style.display = 'none'; }); });
    }
    searchEl.addEventListener('focus', () => showOptions(searchEl.classList.contains('has-value') ? '' : searchEl.value));
    searchEl.addEventListener('input', function() { searchEl.classList.remove('has-value'); selectEl.value = ''; showOptions(this.value); });
    searchEl.addEventListener('blur', () => setTimeout(() => optionsEl.style.display = 'none', 150));
}
initSearchableSelect('bookSearch', 'bookSelect', 'bookOptions');
initSearchableSelect('categorySearch', 'categorySelect', 'categoryOptions');
</script>
@endsection
