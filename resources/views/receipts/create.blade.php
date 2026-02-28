@extends('layouts.admin')
@section('title', 'Add Receipt')
@section('content')
<div class="page-header animate-in">
    <div class="page-header-left">
        <h1><i data-feather="plus" style="width:24px;height:24px;color:var(--income);"></i> New Receipt</h1>
        <p>Record an incoming payment</p>
    </div>
</div>

{{-- Receipt number preview --}}
<div id="receiptPreview" class="card mb-3 animate-in" style="padding:0.6rem 0.9rem;display:none;border-left:3px solid var(--income);">
    <div style="display:flex;align-items:center;gap:0.75rem;">
        <div style="background:var(--income-bg);color:var(--income);border-radius:var(--radius-sm);padding:0.4rem;display:flex;"><i data-feather="hash" style="width:16px;height:16px;"></i></div>
        <div>
            <div class="text-xs text-tertiary">Next Receipt Number</div>
            <div id="receiptNo" style="font-weight:700;font-size:1.1rem;color:var(--income);"></div>
        </div>
        <div style="margin-left:auto;">
            <div id="receiptSeries" class="text-xs text-tertiary"></div>
        </div>
    </div>
</div>
<div class="form-card animate-in" style="animation-delay:0.05s;">
    <form action="{{ route('receipts.store') }}" method="POST" id="receiptForm">@csrf

        {{-- Book selector with search --}}
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Book</label>
                <div class="searchable-select">
                    <input type="text" class="form-control search-input" id="bookSearch" placeholder="Search books..." autocomplete="off">
                    <select name="book_id" id="bookSelect" class="form-control" required style="display:none;">
                        <option value="">Select book</option>
                        @foreach($books as $book)
                            <option value="{{ $book->id }}" data-label="{{ $book->name }}{{ $book->book_no ? ' (#'.$book->book_no.')' : '' }}" {{ (old('book_id', request('book_id')) == $book->id) ? 'selected' : '' }}>{{ $book->name }}{{ $book->book_no ? ' (#'.$book->book_no.')' : '' }}</option>
                        @endforeach
                    </select>
                    <div class="search-options" id="bookOptions"></div>
                </div>
                @error('book_id')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            {{-- Category selector with search --}}
            <div class="form-group">
                <label class="form-label">Category</label>
                <div class="searchable-select">
                    <input type="text" class="form-control search-input" id="categorySearch" placeholder="Search categories..." autocomplete="off">
                    <select name="category_id" id="categorySelect" class="form-control" required style="display:none;">
                        <option value="">Select category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" data-label="{{ $cat->name }}" {{ (old('category_id', request('category_id')) == $cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <div class="search-options" id="categoryOptions"></div>
                </div>
                @error('category_id')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Account</label>
                <select name="account_id" id="accountSelect" class="form-control">
                    <option value="" data-type="">— No account —</option>
                    @foreach($accounts as $acc)
                        <option value="{{ $acc->id }}" data-type="{{ $acc->type }}" {{ (old('account_id', request('account_id')) == $acc->id) ? 'selected' : '' }}>{{ $acc->name }} ({{ ucfirst($acc->type) }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Amount (₹)</label>
                <input type="number" step="0.01" min="0.01" name="amount" class="form-control" placeholder="0.00" required value="{{ old('amount') }}" id="amountField">
                @error('amount')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" required value="{{ old('date', date('Y-m-d')) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Received From</label>
                <input type="text" name="received_from" class="form-control" placeholder="Name of donor / source" value="{{ old('received_from') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Payment Method</label>
                <select name="payment_method" id="paymentMethod" class="form-control">
                    <option value="Cash" {{ old('payment_method', request('payment_method', 'Cash')) == 'Cash' ? 'selected' : '' }}>Cash</option>
                    <option value="Bank Transfer" {{ old('payment_method', request('payment_method')) == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="Cheque" {{ old('payment_method', request('payment_method')) == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                    <option value="Other" {{ old('payment_method', request('payment_method')) == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="2" placeholder="Optional notes">{{ old('description') }}</textarea>
        </div>
        <div class="divider"></div>
        <div class="flex-between" style="flex-wrap:wrap;gap:0.5rem;">
            <a href="{{ route('receipts.index') }}" class="btn btn-secondary">Cancel</a>
            <div style="display:flex;gap:0.5rem;">
                <button type="submit" name="add_another" value="1" class="btn btn-secondary" style="border-color:var(--income-border);color:var(--income);"><i data-feather="plus"></i> Save & Add Another</button>
                <button type="submit" class="btn btn-primary"><i data-feather="check"></i> Save Receipt</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
// Searchable Select Component
function initSearchableSelect(searchId, selectId, optionsId, onSelect) {
    const searchEl = document.getElementById(searchId);
    const selectEl = document.getElementById(selectId);
    const optionsEl = document.getElementById(optionsId);
    const options = Array.from(selectEl.options).filter(o => o.value);

    // Show pre-selected value
    const selected = selectEl.querySelector('option:checked');
    if (selected && selected.value) {
        searchEl.value = selected.getAttribute('data-label') || selected.text;
        searchEl.classList.add('has-value');
    }

    function showOptions(filter) {
        const q = (filter || '').toLowerCase();
        let html = '';
        options.forEach(o => {
            const label = o.getAttribute('data-label') || o.text;
            if (label.toLowerCase().includes(q)) {
                html += '<div class="search-option" data-value="' + o.value + '" data-label="' + label + '">' + label + '</div>';
            }
        });
        optionsEl.innerHTML = html || '<div class="search-option-empty">No results</div>';
        optionsEl.style.display = 'block';

        optionsEl.querySelectorAll('.search-option').forEach(opt => {
            opt.addEventListener('mousedown', function(e) {
                e.preventDefault();
                selectEl.value = this.dataset.value;
                searchEl.value = this.dataset.label;
                searchEl.classList.add('has-value');
                optionsEl.style.display = 'none';
                if (onSelect) onSelect(this.dataset.value);
            });
        });
    }

    searchEl.addEventListener('focus', () => showOptions(searchEl.classList.contains('has-value') ? '' : searchEl.value));
    searchEl.addEventListener('input', function() {
        searchEl.classList.remove('has-value');
        selectEl.value = '';
        showOptions(this.value);
    });
    searchEl.addEventListener('blur', () => setTimeout(() => optionsEl.style.display = 'none', 150));
}

initSearchableSelect('bookSearch', 'bookSelect', 'bookOptions', function(bookId) {
    // Fetch next receipt number when book is selected
    if (!bookId) { document.getElementById('receiptPreview').style.display = 'none'; return; }
    fetch('/books/' + bookId + '/next-receipt')
        .then(r => r.json())
        .then(data => {
            const preview = document.getElementById('receiptPreview');
            if (data.next_receipt_no) {
                document.getElementById('receiptNo').textContent = '#' + data.next_receipt_no;
                document.getElementById('receiptSeries').textContent = data.series ? 'Series: ' + data.series : '';
                preview.style.display = 'block';
                feather.replace();
            } else {
                document.getElementById('receiptNo').textContent = 'Range exhausted!';
                document.getElementById('receiptSeries').textContent = '';
                preview.style.display = 'block';
                preview.style.borderLeftColor = 'var(--expense)';
            }
        });
});
initSearchableSelect('categorySearch', 'categorySelect', 'categoryOptions');

// Auto-focus amount field for quick entry
@if(request('book_id'))
    document.getElementById('amountField').focus();
    // Trigger receipt number fetch for pre-selected book
    var preselectedBook = document.getElementById('bookSelect').value;
    if (preselectedBook) {
        fetch('/books/' + preselectedBook + '/next-receipt')
            .then(r => r.json())
            .then(data => {
                if (data.next_receipt_no) {
                    document.getElementById('receiptNo').textContent = '#' + data.next_receipt_no;
                    document.getElementById('receiptSeries').textContent = data.series ? 'Series: ' + data.series : '';
                    document.getElementById('receiptPreview').style.display = 'block';
                    feather.replace();
                }
            });
    }
@endif

// Auto-match payment method when account is selected
document.getElementById('accountSelect').addEventListener('change', function() {
    var type = this.options[this.selectedIndex].dataset.type;
    var pm = document.getElementById('paymentMethod');
    if (type === 'cash') pm.value = 'Cash';
    else if (type === 'bank') pm.value = 'Bank Transfer';
});
</script>
@endsection
