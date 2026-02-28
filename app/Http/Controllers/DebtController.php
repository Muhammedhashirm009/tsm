<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\Account;
use App\Models\Creditor;
use Illuminate\Http\Request;

class DebtController extends Controller
{
    public function index()
    {
        $debts = Debt::with(['account', 'creditor'])->latest()->get();
        $totalBorrowed = Debt::where('type', 'borrowed')->sum('amount');
        $totalPaid = Debt::where('type', 'borrowed')->sum('paid_amount');
        $totalOutstanding = $totalBorrowed - $totalPaid;
        return view('debts.index', compact('debts', 'totalBorrowed', 'totalPaid', 'totalOutstanding'));
    }

    public function create()
    {
        $accounts = Account::all();
        $creditors = Creditor::orderBy('name')->get();
        return view('debts.create', compact('accounts', 'creditors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'creditor_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'type' => 'required|in:borrowed',
            'account_id' => 'required|exists:accounts,id',
            'description' => 'nullable|string',
        ]);

        // Find or create creditor
        $creditor = Creditor::firstOrCreate(
            ['name' => $validated['creditor_name']],
            ['name' => $validated['creditor_name']]
        );

        $debt = Debt::create([
            'creditor_id' => $creditor->id,
            'person_name' => $creditor->name,
            'amount' => $validated['amount'],
            'date' => $validated['date'],
            'type' => $validated['type'],
            'account_id' => $validated['account_id'],
            'description' => $validated['description'] ?? null,
            'status' => 'pending',
            'paid_amount' => 0,
        ]);

        return redirect()->route('debts.index')->with('success', '₹' . number_format($validated['amount'], 2) . ' borrowed from ' . $creditor->name . ' — credited to account.');
    }

    public function edit(Debt $debt)
    {
        $debt->load('creditor');
        $accounts = Account::all();
        return view('debts.edit', compact('debt', 'accounts'));
    }

    public function update(Request $request, Debt $debt)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'type' => 'required|in:borrowed',
            'account_id' => 'required|exists:accounts,id',
            'paid_amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);
        $paidAmount = $validated['paid_amount'] ?? 0;
        if ($paidAmount >= $validated['amount']) {
            $validated['status'] = 'paid';
        } elseif ($paidAmount > 0) {
            $validated['status'] = 'partial';
        } else {
            $validated['status'] = 'pending';
        }
        $debt->update($validated);
        return redirect()->route('debts.index')->with('success', 'Debt updated.');
    }

    public function destroy(Debt $debt)
    {
        $debt->delete();
        return redirect()->route('debts.index')->with('success', 'Debt removed.');
    }

    public function repayForm(Debt $debt)
    {
        $debt->load(['creditor', 'account']);
        $accounts = Account::all();
        return view('debts.repay', compact('debt', 'accounts'));
    }

    public function repay(Request $request, Debt $debt)
    {
        $remaining = $debt->amount - $debt->paid_amount;
        $validated = $request->validate([
            'amounts' => 'required|array|min:1',
            'amounts.*' => 'required|numeric|min:0.01',
            'account_ids' => 'required|array|min:1',
            'account_ids.*' => 'required|exists:accounts,id',
        ]);

        $totalRepay = array_sum($validated['amounts']);
        if ($totalRepay > $remaining + 0.01) {
            return back()->withErrors(['amounts' => 'Total repayment (₹' . number_format($totalRepay, 2) . ') exceeds remaining (₹' . number_format($remaining, 2) . ')']);
        }

        $newPaid = $debt->paid_amount + $totalRepay;
        $status = $newPaid >= $debt->amount ? 'paid' : ($newPaid > 0 ? 'partial' : 'pending');

        $debt->update([
            'paid_amount' => $newPaid,
            'status' => $status,
        ]);

        $creditorName = $debt->creditor ? $debt->creditor->name : $debt->person_name;
        $accountCount = count($validated['account_ids']);
        $msg = '₹' . number_format($totalRepay, 2) . ' repaid to ' . $creditorName;
        if ($accountCount > 1) $msg .= ' (split across ' . $accountCount . ' accounts)';
        $msg .= '. ' . ($status === 'paid' ? 'Debt fully settled! ✓' : 'Remaining: ₹' . number_format($debt->amount - $newPaid, 2));

        return redirect()->route('debts.index')->with('success', $msg);
    }
}
