<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\Voucher;
use App\Models\MahalDonation;
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
        $donationsQuery = MahalDonation::with(['book', 'home', 'account', 'category', 'creator']);

        // Filters
        if ($request->filled('book_id')) {
            $receiptsQuery->where('book_id', $request->book_id);
            $vouchersQuery->where('book_id', $request->book_id);
            $donationsQuery->where('book_id', $request->book_id);
        }
        if ($request->filled('category_id')) {
            $receiptsQuery->where('category_id', $request->category_id);
            $vouchersQuery->where('category_id', $request->category_id);
        }
        if ($request->filled('account_id')) {
            $receiptsQuery->where('account_id', $request->account_id);
            $vouchersQuery->where('account_id', $request->account_id);
            $donationsQuery->where('account_id', $request->account_id);
        }
        if ($request->filled('date_from')) {
            $receiptsQuery->where('date', '>=', $request->date_from);
            $vouchersQuery->where('date', '>=', $request->date_from);
            $donationsQuery->where('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $receiptsQuery->where('date', '<=', $request->date_to);
            $vouchersQuery->where('date', '<=', $request->date_to);
            $donationsQuery->where('date', '<=', $request->date_to);
        }

        $typeFilter = $request->get('type', 'all');

        $receipts = ($typeFilter === 'all' || $typeFilter === 'income') ? $receiptsQuery->get() : collect();
        $vouchers = ($typeFilter === 'all' || $typeFilter === 'expense') ? $vouchersQuery->get() : collect();
        $donations = ($typeFilter === 'all' || $typeFilter === 'income') ? $donationsQuery->get() : collect();

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
        foreach ($donations as $d) {
            $transactions->push((object)[
                'id' => $d->id,
                'type' => 'donation',
                'ref_no' => $d->receipt_no,
                'amount' => $d->amount,
                'date' => $d->date,
                'category_name' => $d->category->name ?? 'Mahal Donation',
                'book_name' => $d->book->name ?? '—',
                'person' => $d->donor_name ?? ($d->home ? 'Home #' . $d->home->home_number : '—'),
                'account' => $d->account,
                'payment_method' => $d->payment_method,
                'description' => $d->description,
                'creator_name' => $d->creator->name ?? null,
                'created_at' => $d->created_at,
            ]);
        }

        $transactions = $transactions->sortByDesc('date')->values();

        $totalIncome = $receipts->sum('amount') + $donations->sum('amount');
        $totalExpense = $vouchers->sum('amount');

        return view('transactions.index', compact(
            'transactions', 'books', 'categories', 'accounts',
            'totalIncome', 'totalExpense', 'typeFilter'
        ));
    }
}
