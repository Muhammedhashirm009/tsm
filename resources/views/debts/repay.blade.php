@extends('layouts.admin')
@section('title', 'Repay Debt')
@section('content')
<div class="page-header animate-in">
    <div class="page-header-left">
        <h1><i data-feather="check-circle" style="width:24px;height:24px;color:var(--income);"></i> Repay Debt</h1>
        <p>Record a repayment to {{ $debt->creditor ? $debt->creditor->name : $debt->person_name }}</p>
    </div>
    <a href="{{ route('debts.index') }}" class="btn btn-secondary"><i data-feather="arrow-left"></i> Back</a>
</div>

{{-- Debt summary --}}
<div class="stats-grid animate-in" style="animation-delay:0.03s;grid-template-columns:repeat(3,1fr);">
    <div class="stat-card debt">
        <div class="stat-icon debt"><i data-feather="arrow-down-left"></i></div>
        <div class="stat-label">Total Borrowed</div>
        <div class="stat-value">₹{{ number_format($debt->amount, 2) }}</div>
    </div>
    <div class="stat-card income">
        <div class="stat-icon income"><i data-feather="check-circle"></i></div>
        <div class="stat-label">Already Repaid</div>
        <div class="stat-value text-income">₹{{ number_format($debt->paid_amount, 2) }}</div>
    </div>
    <div class="stat-card expense">
        <div class="stat-icon expense"><i data-feather="alert-circle"></i></div>
        <div class="stat-label">Remaining</div>
        <div class="stat-value text-expense" id="remainingDisplay">₹{{ number_format($debt->remaining, 2) }}</div>
    </div>
</div>

<div class="form-card animate-in" style="animation-delay:0.06s;">
    <form action="{{ route('debts.repay.store', $debt) }}" method="POST" id="repayForm">@csrf
        <div id="accountRows">
            <div class="account-row mb-3" style="display:flex;gap:0.75rem;align-items:flex-end;">
                <div class="form-group" style="flex:1;">
                    <label class="form-label">Amount (₹)</label>
                    <input type="number" step="0.01" min="0.01" max="{{ $debt->remaining }}" name="amounts[]" class="form-control repay-amount" placeholder="0.00" required value="{{ old('amounts.0', $debt->remaining) }}" style="font-size:1.1rem;font-weight:700;">
                </div>
                <div class="form-group" style="flex:1;">
                    <label class="form-label">From Account</label>
                    <select name="account_ids[]" class="form-control" required>
                        <option value="">Select account</option>
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}" {{ old('account_ids.0', $debt->account_id) == $acc->id ? 'selected' : '' }}>{{ $acc->name }} ({{ ucfirst($acc->type) }}) — ₹{{ number_format($acc->balance, 2) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <button type="button" id="addAccountBtn" class="btn btn-secondary btn-sm mb-3" style="font-size:0.75rem;"><i data-feather="plus" style="width:14px;height:14px;"></i> Split Across Another Account</button>

        <div id="totalWarning" class="form-error mb-3" style="display:none;"></div>

        @error('amounts')<div class="form-error mb-3">{{ $message }}</div>@enderror
        @error('account_ids')<div class="form-error mb-3">{{ $message }}</div>@enderror

        <div class="divider"></div>
        <div class="flex-between">
            <a href="{{ route('debts.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary" style="background:var(--income);" id="submitBtn"><i data-feather="check"></i> Confirm Repayment</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
(function() {
    const remaining = {{ $debt->remaining }};
    const accountsHtml = `@foreach($accounts as $acc)<option value="{{ $acc->id }}">{{ $acc->name }} ({{ ucfirst($acc->type) }}) — ₹{{ number_format($acc->balance, 2) }}</option>@endforeach`;
    let rowCount = 1;

    document.getElementById('addAccountBtn').addEventListener('click', function() {
        rowCount++;
        const row = document.createElement('div');
        row.className = 'account-row mb-3';
        row.style.cssText = 'display:flex;gap:0.75rem;align-items:flex-end;';
        row.innerHTML = `
            <div class="form-group" style="flex:1;">
                <label class="form-label">Amount (₹)</label>
                <input type="number" step="0.01" min="0.01" name="amounts[]" class="form-control repay-amount" placeholder="0.00" required style="font-size:1.1rem;font-weight:700;">
            </div>
            <div class="form-group" style="flex:1;">
                <label class="form-label">From Account</label>
                <select name="account_ids[]" class="form-control" required>
                    <option value="">Select account</option>
                    ${accountsHtml}
                </select>
            </div>
            <button type="button" class="btn btn-ghost btn-icon remove-row" style="color:var(--expense);margin-bottom:0.5rem;" title="Remove"><i data-feather="x" style="width:16px;height:16px;"></i></button>
        `;
        document.getElementById('accountRows').appendChild(row);
        feather.replace();
        updateTotal();
        row.querySelector('.remove-row').addEventListener('click', function() {
            row.remove();
            rowCount--;
            updateTotal();
        });
    });

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.repay-amount').forEach(function(el) {
            total += parseFloat(el.value) || 0;
        });
        const warn = document.getElementById('totalWarning');
        if (total > remaining) {
            warn.textContent = 'Total (₹' + total.toFixed(2) + ') exceeds remaining (₹' + remaining.toFixed(2) + ')';
            warn.style.display = 'block';
            document.getElementById('submitBtn').disabled = true;
        } else {
            warn.style.display = 'none';
            document.getElementById('submitBtn').disabled = false;
        }
    }

    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('repay-amount')) updateTotal();
    });

    // Set initial first row amount to remaining
    const firstAmount = document.querySelector('.repay-amount');
    if (firstAmount) firstAmount.value = remaining;
})();
</script>
@endsection
