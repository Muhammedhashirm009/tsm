<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CreditorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    
    Route::get('/creditors/search', [CreditorController::class, 'search'])->name('creditors.search');
    Route::resource('creditors', CreditorController::class)->except(['create', 'store']);
    
    Route::get('/books/{book}/next-receipt', [BookController::class, 'nextReceipt'])->name('books.nextReceipt');
    Route::get('/debts/{debt}/repay', [DebtController::class, 'repayForm'])->name('debts.repay');
    Route::post('/debts/{debt}/repay', [DebtController::class, 'repay'])->name('debts.repay.store');
    
    Route::resource('books', BookController::class);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('receipts', ReceiptController::class)->except(['show']);
    Route::resource('vouchers', VoucherController::class)->except(['show']);
    Route::resource('accounts', AccountController::class)->except(['show']);
    Route::resource('debts', DebtController::class)->except(['show']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
