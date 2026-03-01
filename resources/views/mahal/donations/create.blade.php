@extends('layouts.admin')
@section('title', 'Add Donation')
@section('content')
<div class="page-header animate-in">
    <div class="page-header-left">
        <h1><i data-feather="heart" style="width:24px;height:24px;color:var(--income);"></i> Add Donation</h1>
    </div>
</div>

{{-- Receipt number preview --}}
<div id="receiptPreview" class="card mb-3 animate-in" style="padding:0.5rem 0.8rem;display:none;border-left:3px solid var(--income);">
    <div style="display:flex;align-items:center;gap:0.6rem;">
        <div style="background:var(--income-bg);color:var(--income);border-radius:var(--radius-sm);padding:0.3rem;display:flex;"><i data-feather="hash" style="width:14px;height:14px;"></i></div>
        <div>
            <div class="text-xs text-tertiary">Next Receipt</div>
            <div id="receiptNo" style="font-weight:700;font-size:1rem;color:var(--income);"></div>
        </div>
        <div id="receiptSeries" class="text-xs text-tertiary" style="margin-left:auto;"></div>
    </div>
</div>

<div class="form-card animate-in" style="animation-delay:0.05s;padding:1rem;">
    <form action="{{ route('mahal.donations.store') }}" method="POST">@csrf

        {{-- Amount — the star of the show --}}
        <div class="form-group">
            <label class="form-label">Amount (₹) *</label>
            <input type="number" step="0.01" min="0.01" name="amount" class="form-control donation-amount-input" placeholder="0.00" required value="{{ old('amount') }}" id="amountField" inputmode="decimal">
            @error('amount')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        {{-- Home & Donor side-by-side compact --}}
        <div class="form-group">
            <label class="form-label">Home</label>
            <div class="searchable-select">
                <input type="text" class="form-control search-input" id="homeSearch" placeholder="Type home # or owner..." autocomplete="off" inputmode="search">
                <select name="home_id" id="homeSelect" class="form-control" style="display:none;">
                    <option value="">— No home —</option>
                    @foreach($homes as $home)
                        <option value="{{ $home->id }}" data-label="#{{ $home->home_number }} — {{ $home->owner_name }}" data-owner="{{ $home->owner_name }}" {{ old('home_id') == $home->id ? 'selected' : '' }}>#{{ $home->home_number }} — {{ $home->owner_name }}</option>
                    @endforeach
                </select>
                <div class="search-options" id="homeOptions"></div>
            </div>
        </div>

        <div class="compact-row">
            <div class="form-group" style="flex:1;">
                <label class="form-label">Donor</label>
                <input type="text" name="donor_name" id="donorName" class="form-control" placeholder="Auto-filled" value="{{ old('donor_name') }}">
            </div>
            <div class="form-group" style="flex:1;">
                <label class="form-label">Date *</label>
                <input type="date" name="date" class="form-control" required value="{{ old('date', date('Y-m-d')) }}">
            </div>
        </div>

        <div class="compact-row">
            <div class="form-group" style="flex:1;">
                <label class="form-label">Account</label>
                <select name="account_id" id="accountSelect" class="form-control">
                    <option value="" data-type="">— Select —</option>
                    @foreach($accounts as $acc)
                        <option value="{{ $acc->id }}" data-type="{{ $acc->type }}" {{ old('account_id', request('account_id')) == $acc->id ? 'selected' : '' }}>{{ $acc->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="flex:1;">
                <label class="form-label">Method *</label>
                <select name="payment_method" id="paymentMethod" class="form-control">
                    <option value="Cash" {{ old('payment_method', 'Cash') == 'Cash' ? 'selected' : '' }}>Cash</option>
                    <option value="Bank Transfer" {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                </select>
            </div>
        </div>

        <div class="compact-row">
            <div class="form-group" style="flex:1;">
                <label class="form-label">Book</label>
                <div class="searchable-select">
                    <input type="text" class="form-control search-input" id="bookSearch" placeholder="Select book..." autocomplete="off">
                    <select name="book_id" id="bookSelect" class="form-control" style="display:none;">
                        <option value="">— None —</option>
                        @foreach($books as $book)
                            <option value="{{ $book->id }}" data-label="{{ $book->name }}{{ $book->book_no ? ' (#'.$book->book_no.')' : '' }}" {{ old('book_id', request('book_id')) == $book->id ? 'selected' : '' }}>{{ $book->name }}{{ $book->book_no ? ' (#'.$book->book_no.')' : '' }}</option>
                        @endforeach
                    </select>
                    <div class="search-options" id="bookOptions"></div>
                </div>
            </div>
            <div class="form-group" style="flex:1;">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-control">
                    <option value="">— None —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', request('category_id')) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-bottom:0.5rem;">
            <label class="form-label">Note <span style="font-weight:400;text-transform:none;">(optional)</span></label>
            <input type="text" name="description" class="form-control" placeholder="Quick note..." value="{{ old('description') }}">
        </div>

        {{-- Buttons --}}
        <div class="donation-form-actions">
            <button type="submit" name="add_another" value="1" class="btn btn-secondary donation-btn-save-another"><i data-feather="plus"></i> Save & Next</button>
            <button type="submit" class="btn btn-primary donation-btn-save"><i data-feather="check"></i> Save</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
function initSearchableSelect(searchId, selectId, optionsId, onSelect) {
    const searchEl = document.getElementById(searchId);
    const selectEl = document.getElementById(selectId);
    const optionsEl = document.getElementById(optionsId);
    const options = Array.from(selectEl.options).filter(o => o.value);
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
    searchEl.addEventListener('input', function() { searchEl.classList.remove('has-value'); selectEl.value = ''; showOptions(this.value); });
    searchEl.addEventListener('blur', () => setTimeout(() => optionsEl.style.display = 'none', 200));
}

initSearchableSelect('bookSearch', 'bookSelect', 'bookOptions', function(bookId) {
    if (!bookId) { document.getElementById('receiptPreview').style.display = 'none'; return; }
    fetch('/books/' + bookId + '/next-receipt').then(r => r.json()).then(data => {
        const preview = document.getElementById('receiptPreview');
        if (data.next_receipt_no) {
            document.getElementById('receiptNo').textContent = '#' + data.next_receipt_no;
            document.getElementById('receiptSeries').textContent = data.series ? data.series : '';
            preview.style.display = 'block'; feather.replace();
        } else {
            document.getElementById('receiptNo').textContent = 'Exhausted!';
            preview.style.display = 'block'; preview.style.borderLeftColor = 'var(--expense)';
        }
    });
});

initSearchableSelect('homeSearch', 'homeSelect', 'homeOptions', function(homeId) {
    if (!homeId) return;
    var sel = document.querySelector('#homeSelect option[value="' + homeId + '"]');
    if (sel && sel.dataset.owner) document.getElementById('donorName').value = sel.dataset.owner;
});

function syncPaymentMethod() {
    var acct = document.getElementById('accountSelect');
    var pm = document.getElementById('paymentMethod');
    var type = acct.options[acct.selectedIndex].dataset.type;
    if (type === 'cash') { pm.value = 'Cash'; pm.querySelectorAll('option').forEach(o => o.disabled = o.value !== 'Cash'); }
    else if (type === 'bank') { pm.value = 'Bank Transfer'; pm.querySelectorAll('option').forEach(o => o.disabled = o.value !== 'Bank Transfer'); }
    else { pm.querySelectorAll('option').forEach(o => o.disabled = false); }
}
document.getElementById('accountSelect').addEventListener('change', syncPaymentMethod);
syncPaymentMethod();
</script>
@endsection
