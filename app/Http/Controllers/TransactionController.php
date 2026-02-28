<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\Voucher;
use App\Models\Book;
use App\Models\Category;
use App\Models\Account;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::all();
        $categories = Category::all();
        $accounts = Account::all();

        $receiptsQuery = Receipt::with(['book', 'category', 'account', 'creator']);
        $vouchersQuery = Voucher::with(['book', 'category', 'account', 'creator']);

        // Filters
        if ($request->filled('book_id')) {
            $receiptsQuery->where('book_id', $request->book_id);
            $vouchersQuery->where('book_id', $request->book_id);
        }
        if ($request->filled('category_id')) {
            $receiptsQuery->where('category_id', $request->category_id);
            $vouchersQuery->where('category_id', $request->category_id);
        }
        if ($request->filled('account_id')) {
            $receiptsQuery->where('account_id', $request->account_id);
            $vouchersQuery->where('account_id', $request->account_id);
        }
        if ($request->filled('date_from')) {
            $receiptsQuery->where('date', '>=', $request->date_from);
            $vouchersQuery->where('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $receiptsQuery->where('date', '<=', $request->date_to);
            $vouchersQuery->where('date', '<=', $request->date_to);
        }

        $typeFilter = $request->get('type', 'all');

        $receipts = ($typeFilter === 'all' || $typeFilter === 'income') ? $receiptsQuery->get() : collect();
        $vouchers = ($typeFilter === 'all' || $typeFilter === 'expense') ? $vouchersQuery->get() : collect();

        // Merge into a single collection with type markers
        $transactions = collect();
        foreach ($receipts as $r) {
            $transactions->push((object)[
                'id' => $r->id,
                'type' => 'income',
                'ref_no' => $r->receipt_no,
                'amount' => $r->amount,
                'date' => $r->date,
                'category_name' => $r->category->name ?? '—',
                'book_name' => $r->book->name ?? '—',
                'person' => $r->received_from,
                'account' => $r->account,
                'payment_method' => $r->payment_method,
                'description' => $r->description,
                'creator_name' => $r->creator->name ?? null,
                'created_at' => $r->created_at,
            ]);
        }
        foreach ($vouchers as $v) {
            $transactions->push((object)[
                'id' => $v->id,
                'type' => 'expense',
                'ref_no' => $v->voucher_no,
                'amount' => $v->amount,
                'date' => $v->date,
                'category_name' => $v->category->name ?? '—',
                'book_name' => $v->book->name ?? '—',
                'person' => $v->paid_to,
                'account' => $v->account,
                'payment_method' => $v->payment_method,
                'description' => $v->description,
                'creator_name' => $v->creator->name ?? null,
                'created_at' => $v->created_at,
            ]);
        }

        $transactions = $transactions->sortByDesc('date')->values();

        $totalIncome = $receipts->sum('amount');
        $totalExpense = $vouchers->sum('amount');

        return view('transactions.index', compact(
            'transactions', 'books', 'categories', 'accounts',
            'totalIncome', 'totalExpense', 'typeFilter'
        ));
    }
}
