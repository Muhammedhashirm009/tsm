<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::all();
        return view('accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('accounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:cash,bank,other',
            'opening_balance' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);
        $validated['opening_balance'] = $validated['opening_balance'] ?? 0;
        Account::create($validated);
        return redirect()->route('accounts.index')->with('success', 'Account created successfully.');
    }

    public function edit(Account $account)
    {
        return view('accounts.edit', compact('account'));
    }

    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:cash,bank,other',
            'opening_balance' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);
        $validated['opening_balance'] = $validated['opening_balance'] ?? 0;
        $account->update($validated);
        return redirect()->route('accounts.index')->with('success', 'Account updated.');
    }

    public function destroy(Account $account)
    {
        $account->delete();
        return redirect()->route('accounts.index')->with('success', 'Account deleted.');
    }
}
