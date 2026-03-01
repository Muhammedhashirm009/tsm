<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\Category;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::with(['category', 'account', 'creator'])->latest()->get();
        return view('vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        $categories = Category::where('type', 'expense')->get();
        $accounts = Account::all();
        return view('vouchers.create', compact('categories', 'accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'account_id' => 'nullable|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01|max:99999999',
            'date' => 'required|date',
            'paid_to' => 'nullable|string|max:255',
            'payment_method' => 'nullable|string|in:Cash,Bank Transfer',
            'description' => 'nullable|string|max:1000',
        ]);
        $validated['created_by'] = Auth::id();
        Voucher::create($validated);
        return redirect()->route('vouchers.index')->with('success', 'Voucher created.');
    }

    public function edit(Voucher $voucher)
    {
        $categories = Category::where('type', 'expense')->get();
        $accounts = Account::all();
        return view('vouchers.edit', compact('voucher', 'categories', 'accounts'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'account_id' => 'nullable|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01|max:99999999',
            'date' => 'required|date',
            'paid_to' => 'nullable|string|max:255',
            'payment_method' => 'nullable|string|in:Cash,Bank Transfer',
            'description' => 'nullable|string|max:1000',
        ]);
        $voucher->update($validated);
        return redirect()->route('vouchers.index')->with('success', 'Voucher updated.');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return redirect()->route('vouchers.index')->with('success', 'Voucher deleted.');
    }
}
