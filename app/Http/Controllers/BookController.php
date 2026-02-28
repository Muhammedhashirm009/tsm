<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::withCount('receipts', 'vouchers')->get();
        return view('books.index', compact('books'));
    }

    public function show(Book $book)
    {
        $book->loadCount('receipts');
        $receipts = $book->receipts()->with(['category', 'account', 'creator'])->latest()->get();
        $totalIncome = $receipts->sum('amount');
        return view('books.show', compact('book', 'receipts', 'totalIncome'));
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'book_no' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'receipt_prefix' => 'nullable|string|max:20',
            'receipt_start_no' => 'nullable|integer|min:1',
            'receipt_end_no' => 'nullable|integer|min:1',
        ]);
        $validated['receipt_start_no'] = $validated['receipt_start_no'] ?? 1;
        $validated['receipt_current_no'] = 0;
        Book::create($validated);
        return redirect()->route('books.index')->with('success', 'Book created.');
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'book_no' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'receipt_prefix' => 'nullable|string|max:20',
            'receipt_start_no' => 'nullable|integer|min:1',
            'receipt_end_no' => 'nullable|integer|min:1',
        ]);
        $book->update($validated);
        return redirect()->route('books.index')->with('success', 'Book updated.');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Book deleted.');
    }

    public function nextReceipt(Book $book)
    {
        return response()->json([
            'next_receipt_no' => $book->next_receipt_no,
            'prefix' => $book->receipt_prefix,
            'series' => $book->receipt_prefix
                ? $book->receipt_prefix . str_pad($book->receipt_start_no, 4, '0', STR_PAD_LEFT)
                  . ($book->receipt_end_no ? ' → ' . $book->receipt_prefix . str_pad($book->receipt_end_no, 4, '0', STR_PAD_LEFT) : '')
                : null,
        ]);
    }
}
