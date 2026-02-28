<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\Book;
use App\Models\Category;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceiptController extends Controller
{
    public function index()
    {
        $receipts = Receipt::with(['book', 'category', 'account', 'creator'])->latest()->get();
        return view('receipts.index', compact('receipts'));
    }

    public function create()
    {
        $books = Book::all();
        $categories = Category::where('type', 'income')->get();
        $accounts = Account::all();
        return view('receipts.create', compact('books', 'categories', 'accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'category_id' => 'required|exists:categories,id',
            'account_id' => 'nullable|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'received_from' => 'nullable|string|max:255',
            'payment_method' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Auto-generate receipt number from book
        $book = Book::findOrFail($validated['book_id']);
        $validated['receipt_no'] = $book->incrementReceiptNo();
        $validated['created_by'] = Auth::id();

        Receipt::create($validated);

        // Check if addAnother flag
        if ($request->has('add_another')) {
            return redirect()->route('receipts.create', [
                'book_id' => $validated['book_id'],
                'category_id' => $validated['category_id'],
                'account_id' => $validated['account_id'] ?? '',
                'payment_method' => $validated['payment_method'] ?? 'Cash',
            ])->with('success', 'Receipt #' . $validated['receipt_no'] . ' saved! Add another below.');
        }

        return redirect()->route('receipts.index')->with('success', 'Receipt #' . $validated['receipt_no'] . ' created.');
    }

    public function edit(Receipt $receipt)
    {
        $books = Book::all();
        $categories = Category::where('type', 'income')->get();
        $accounts = Account::all();
        return view('receipts.edit', compact('receipt', 'books', 'categories', 'accounts'));
    }

    public function update(Request $request, Receipt $receipt)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'category_id' => 'required|exists:categories,id',
            'account_id' => 'nullable|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'received_from' => 'nullable|string|max:255',
            'payment_method' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
        $receipt->update($validated);
        return redirect()->route('receipts.index')->with('success', 'Receipt updated.');
    }

    public function destroy(Receipt $receipt)
    {
        $receipt->delete();
        return redirect()->route('receipts.index')->with('success', 'Receipt deleted.');
    }
}
