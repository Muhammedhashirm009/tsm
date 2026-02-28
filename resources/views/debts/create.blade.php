@extends('layouts.admin')
@section('title', 'Add Debt')
@section('content')
<div class="page-header animate-in"><div class="page-header-left"><h1><i data-feather="plus" style="width:24px;height:24px;"></i> Record Borrowed Debt</h1><p>Track money borrowed from someone</p></div></div>
<div class="form-card animate-in" style="animation-delay:0.05s;">
    <form action="{{ route('debts.store') }}" method="POST" id="debtForm">@csrf
        <input type="hidden" name="type" value="borrowed">
        <div class="grid-2">
            {{-- Creditor search --}}
            <div class="form-group" style="grid-column:1/-1;">
                <label class="form-label">Borrowed From</label>
                <div class="searchable-select">
                    <input type="text" name="creditor_name" id="creditorInput" class="form-control" placeholder="Type a name to search or add new..." required autocomplete="off" value="{{ old('creditor_name') }}">
                    <div class="search-options" id="creditorResults"></div>
                </div>
                <div class="text-xs text-tertiary mt-1">Search existing creditors or type a new name</div>
                @error('creditor_name')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group"><label class="form-label">Amount (₹)</label><input type="number" step="0.01" min="0.01" name="amount" class="form-control" placeholder="0.00" required value="{{ old('amount') }}" id="amountField">@error('amount')<div class="form-error">{{ $message }}</div>@enderror</div>
            <div class="form-group">
                <label class="form-label">Credit To Account</label>
                <select name="account_id" class="form-control" required>
                    <option value="">Select account</option>
                    @foreach($accounts as $acc)
                        <option value="{{ $acc->id }}" {{ old('account_id') == $acc->id ? 'selected' : '' }}>{{ $acc->name }} ({{ ucfirst($acc->type) }})</option>
                    @endforeach
                </select>
                <div class="text-xs text-tertiary mt-1">Borrowed amount will be credited here</div>
                @error('account_id')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group"><label class="form-label">Date</label><input type="date" name="date" class="form-control" required value="{{ old('date', date('Y-m-d')) }}">@error('date')<div class="form-error">{{ $message }}</div>@enderror</div>
        </div>
        <div class="form-group"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2" placeholder="Reason for borrowing (optional)">{{ old('description') }}</textarea></div>
        <div class="divider"></div>
        <div class="flex-between"><a href="{{ route('debts.index') }}" class="btn btn-secondary">Cancel</a><button type="submit" class="btn btn-primary"><i data-feather="check"></i> Save Debt</button></div>
    </form>
</div>
@endsection

@section('scripts')
<script>
(function() {
    const input = document.getElementById('creditorInput');
    const results = document.getElementById('creditorResults');
    let debounce;

    input.addEventListener('input', function() {
        clearTimeout(debounce);
        const q = this.value.trim();
        if (q.length < 1) { results.style.display = 'none'; return; }
        debounce = setTimeout(() => {
            fetch('{{ route("creditors.search") }}?q=' + encodeURIComponent(q))
                .then(r => r.json())
                .then(data => {
                    let html = '';
                    if (data.length > 0) {
                        data.forEach(c => {
                            html += '<div class="search-option" data-name="' + c.name + '">' +
                                '<strong>' + c.name + '</strong>' +
                                (c.phone ? ' <span style="color:var(--text-tertiary);font-size:0.75rem;">📞 ' + c.phone + '</span>' : '') +
                                '</div>';
                        });
                    }
                    // Always show option to add as new if exact match not found
                    const exactMatch = data.some(c => c.name.toLowerCase() === q.toLowerCase());
                    if (!exactMatch && q.length > 0) {
                        html += '<div class="search-option" data-name="' + q + '" style="border-top:1px solid var(--border-light);color:var(--accent);">' +
                            '<i data-feather="plus-circle" style="width:14px;height:14px;display:inline;margin-right:4px;"></i> Add "<strong>' + q + '</strong>" as new creditor' +
                            '</div>';
                    }
                    results.innerHTML = html;
                    results.style.display = 'block';
                    feather.replace();

                    results.querySelectorAll('.search-option').forEach(opt => {
                        opt.addEventListener('mousedown', function(e) {
                            e.preventDefault();
                            input.value = this.dataset.name;
                            input.classList.add('has-value');
                            results.style.display = 'none';
                            document.getElementById('amountField').focus();
                        });
                    });
                });
        }, 200);
    });

    input.addEventListener('blur', () => setTimeout(() => results.style.display = 'none', 200));
    input.addEventListener('focus', function() { if (this.value.length > 0) this.dispatchEvent(new Event('input')); });
})();
</script>
@endsection
