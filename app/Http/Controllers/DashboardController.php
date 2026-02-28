<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Account;
use App\Models\Receipt;
use App\Models\Voucher;
use App\Models\Debt;

class DashboardController extends Controller
{
    public function index()
    {
        $totalIncome = Receipt::sum('amount');
        $totalExpense = Voucher::sum('amount');
        $balance = $totalIncome - $totalExpense;

        $accounts = Account::all();
        $totalDebtOutstanding = Debt::where('type', 'borrowed')->selectRaw('SUM(amount - paid_amount) as total')->value('total') ?? 0;

        $recentReceipts = Receipt::with(['book', 'category', 'account'])->latest()->take(5)->get();
        $recentVouchers = Voucher::with(['book', 'category', 'account'])->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalIncome', 'totalExpense', 'balance',
            'accounts', 'totalDebtOutstanding',
            'recentReceipts', 'recentVouchers'
        ));
    }
}
