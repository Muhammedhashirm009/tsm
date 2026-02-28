<?php

namespace App\Http\Controllers;

use App\Models\Creditor;
use Illuminate\Http\Request;

class CreditorController extends Controller
{
    public function index()
    {
        $creditors = Creditor::withCount('debts')->get()->map(function ($c) {
            $c->total_borrowed_val = $c->total_borrowed;
            $c->outstanding_val = $c->outstanding;
            return $c;
        });
        $totalOutstanding = $creditors->sum('outstanding_val');
        return view('creditors.index', compact('creditors', 'totalOutstanding'));
    }

    public function show(Creditor $creditor)
    {
        $debts = $creditor->debts()->with('account')->latest()->get();
        $totalBorrowed = $debts->sum('amount');
        $totalRepaid = $debts->sum('paid_amount');
        $outstanding = $totalBorrowed - $totalRepaid;
        return view('creditors.show', compact('creditor', 'debts', 'totalBorrowed', 'totalRepaid', 'outstanding'));
    }

    public function search(Request $request)
    {
        $q = $request->get('q', '');
        $creditors = Creditor::where('name', 'like', '%' . $q . '%')
            ->limit(10)
            ->get(['id', 'name', 'phone']);
        return response()->json($creditors);
    }

    public function edit(Creditor $creditor)
    {
        return view('creditors.edit', compact('creditor'));
    }

    public function update(Request $request, Creditor $creditor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:creditors,name,' . $creditor->id,
            'phone' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);
        $creditor->update($validated);
        return redirect()->route('creditors.show', $creditor)->with('success', 'Creditor updated.');
    }

    public function destroy(Creditor $creditor)
    {
        $creditor->delete();
        return redirect()->route('creditors.index')->with('success', 'Creditor removed.');
    }
}
