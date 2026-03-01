@extends('layouts.admin')
@section('title', 'Add Donation')
@section('content')
<div class="page-header animate-in">
    <div class="page-header-left">
        <h1><i data-feather="heart" style="width:24px;height:24px;color:var(--income);"></i> Add Donation</h1>
        <p>Record a mahal donation</p>
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
    <form action="{{ route('mahal.donations.store') }}" method="POST">@csrf
        <div class="grid-2">
            {{-- Book selector with search --}}
            <div class="form-group">
                <label class="form-label">Book</label>
                <div class="searchable-select">
                    <input type="text" class="form-control search-input" id="bookSearch" placeholder="Search books..." autocomplete="off">
                    <select name="book_id" id="bookSelect" class="form-control" style="display:none;">
                        <option value="">— No book —</option>
                        @foreach($books as $book)
                            <option value="{{ $book->id }}" data-label="{{ $book->name }}{{ $book->book_no ? ' (#'.$book->book_no.')' : '' }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>{{ $book->name }}{{ $book->book_no ? ' (#'.$book->book_no.')' : '' }}</option>
                        @endforeach
                    </select>
                    <div class="search-options" id="bookOptions"></div>
                </div>
            </div>

            {{-- Home selector with search --}}
            <div class="form-group">
                <label class="form-label">Home</label>
                <div class="searchable-select">
                    <input type="text" class="form-control search-input" id="homeSearch" placeholder="Search by home number or owner..." autocomplete="off">
                    <select name="home_id" id="homeSelect" class="form-control" style="display:none;">
                        <option value="">— External / No home —</option>
                        @foreach($homes as $home)
                            <option value="{{ $home->id }}" data-label="#{{ $home->home_number }} — {{ $home->owner_name }}" data-owner="{{ $home->owner_name }}" {{ old('home_id') == $home->id ? 'selected' : '' }}>#{{ $home->home_number }} — {{ $home->owner_name }}</option>
                        @endforeach
                    </select>
                    <div class="search-options" id="homeOptions"></div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Donor Name</label>
                <input type="text" name="donor_name" id="donorName" class="form-control" placeholder="Auto-filled from home or enter manually" value="{{ old('donor_name') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Account</label>
                <select name="account_id" id="accountSelect" class="form-control">
                    <option value="" data-type="">— No account —</option>
                    @foreach($accounts as $acc)
                        <option value="{{ $acc->id }}" data-type="{{ $acc->type }}" {{ old('account_id') == $acc->id ? 'selected' : '' }}>{{ $acc->name }} ({{ ucfirst($acc->type) }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Amount (₹) *</label>
                <input type="number" step="0.01" min="0.01" name="amount" class="form-control" placeholder="0.00" required value="{{ old('amount') }}" id="amountField">
                @error('amount')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Date *</label>
                <input type="date" name="date" class="form-control" required value="{{ old('date', date('Y-m-d')) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Payment Method *</label>
                <select name="payment_method" id="paymentMethod" class="form-control">
                    <option value="Cash" {{ old('payment_method', 'Cash') == 'Cash' ? 'selected' : '' }}>Cash</option>
                    <option value="Bank Transfer" {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="2" placeholder="Optional notes">{{ old('description') }}</textarea>
        </div>
        <div class="divider"></div>
        <div class="flex-between" style="flex-wrap:wrap;gap:0.5rem;">
            <a href="{{ route('mahal.donations.index') }}" class="btn btn-secondary">Cancel</a>
            <div style="display:flex;gap:0.5rem;">
                <button type="submit" name="add_another" value="1" class="btn btn-secondary" style="border-color:var(--income-border);color:var(--income);"><i data-feather="plus"></i> Save & Add Another</button>
                <button type="submit" class="btn btn-primary"><i data-feather="check"></i> Save Donation</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
// Searchable Select Component (same as receipts)
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

// Init book searchable select with receipt number fetch
initSearchableSelect('bookSearch', 'bookSelect', 'bookOptions', function(bookId) {
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

// Init home searchable select with donor name auto-fill
initSearchableSelect('homeSearch', 'homeSelect', 'homeOptions', function(homeId) {
    if (!homeId) return;
    var selected = document.querySelector('#homeSelect option[value="' + homeId + '"]');
    if (selected && selected.dataset.owner) {
        document.getElementById('donorName').value = selected.dataset.owner;
    }
});

// Auto-select & lock payment method when account is selected
function syncPaymentMethod() {
    var acct = document.getElementById('accountSelect');
    var pm = document.getElementById('paymentMethod');
    var type = acct.options[acct.selectedIndex].dataset.type;
    if (type === 'cash') {
        pm.value = 'Cash';
        pm.querySelectorAll('option').forEach(function(o) { o.disabled = o.value !== 'Cash'; });
    } else if (type === 'bank') {
        pm.value = 'Bank Transfer';
        pm.querySelectorAll('option').forEach(function(o) { o.disabled = o.value !== 'Bank Transfer'; });
    } else {
        pm.querySelectorAll('option').forEach(function(o) { o.disabled = false; });
    }
}
document.getElementById('accountSelect').addEventListener('change', syncPaymentMethod);
syncPaymentMethod(); // run on page load for pre-selected values
</script>
@endsection
