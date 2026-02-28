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
        $user = auth()->user();

        if ($user->isCollector()) {
            // Collector view: only show receipts added by them today/recently
            $recentReceipts = Receipt::with(['book', 'category', 'account'])
                                ->where('created_by', $user->id)
                                ->latest()
                                ->take(10)
                                ->get();
                                
            $myTotalCollected = Receipt::where('created_by', $user->id)->sum('amount');
            $myTodayCollected = Receipt::where('created_by', $user->id)
                                    ->whereDate('created_at', \Carbon\Carbon::today())
                                    ->sum('amount');

            return view('dashboard', compact('recentReceipts', 'myTotalCollected', 'myTodayCollected'));
        }

        // Full view for Admins, Secretaries, Presidents
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
