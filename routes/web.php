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
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// ── Authenticated Routes ──
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard — all except collector
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Receipts — everyone can add, only editors can edit/delete
    Route::get('/receipts', [ReceiptController::class, 'index'])->name('receipts.index');
    Route::get('/receipts/create', [ReceiptController::class, 'create'])->name('receipts.create');
    Route::post('/receipts', [ReceiptController::class, 'store'])->name('receipts.store');

    Route::middleware('role:admin,secretary,joint_secretary')->group(function () {
        Route::get('/receipts/{receipt}/edit', [ReceiptController::class, 'edit'])->name('receipts.edit');
        Route::put('/receipts/{receipt}', [ReceiptController::class, 'update'])->name('receipts.update');
        Route::delete('/receipts/{receipt}', [ReceiptController::class, 'destroy'])->name('receipts.destroy');
    });

    // Books — all except collector can view, only editors can modify
    Route::get('/books/{book}/next-receipt', [BookController::class, 'nextReceipt'])->name('books.nextReceipt');

    Route::middleware('role:admin,secretary,joint_secretary,president')->group(function () {
        Route::get('/books', [BookController::class, 'index'])->name('books.index');
        Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    });

    Route::middleware('role:admin,secretary,joint_secretary')->group(function () {
        Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
        Route::post('/books', [BookController::class, 'store'])->name('books.store');
        Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
        Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
        Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    });

    // ── Finance section (all except collector) ──
    Route::middleware('role:admin,secretary,joint_secretary,president')->group(function () {
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

        // Vouchers — president view-only, editors full access
        Route::get('/vouchers', [VoucherController::class, 'index'])->name('vouchers.index');

        // Accounts
        Route::get('/accounts', [AccountController::class, 'index'])->name('accounts.index');

        // Debts
        Route::get('/debts', [DebtController::class, 'index'])->name('debts.index');

        // Creditors
        Route::get('/creditors/search', [CreditorController::class, 'search'])->name('creditors.search');
        Route::get('/creditors', [CreditorController::class, 'index'])->name('creditors.index');
        Route::get('/creditors/{creditor}', [CreditorController::class, 'show'])->name('creditors.show');
    });

    // ── Editor-only actions (admin, secretary, joint_secretary) ──
    Route::middleware('role:admin,secretary,joint_secretary')->group(function () {
        // Vouchers CRUD
        Route::get('/vouchers/create', [VoucherController::class, 'create'])->name('vouchers.create');
        Route::post('/vouchers', [VoucherController::class, 'store'])->name('vouchers.store');
        Route::get('/vouchers/{voucher}/edit', [VoucherController::class, 'edit'])->name('vouchers.edit');
        Route::put('/vouchers/{voucher}', [VoucherController::class, 'update'])->name('vouchers.update');
        Route::delete('/vouchers/{voucher}', [VoucherController::class, 'destroy'])->name('vouchers.destroy');

        // Accounts CRUD
        Route::get('/accounts/create', [AccountController::class, 'create'])->name('accounts.create');
        Route::post('/accounts', [AccountController::class, 'store'])->name('accounts.store');
        Route::get('/accounts/{account}/edit', [AccountController::class, 'edit'])->name('accounts.edit');
        Route::put('/accounts/{account}', [AccountController::class, 'update'])->name('accounts.update');
        Route::delete('/accounts/{account}', [AccountController::class, 'destroy'])->name('accounts.destroy');

        // Debts CRUD + Repay
        Route::get('/debts/create', [DebtController::class, 'create'])->name('debts.create');
        Route::post('/debts', [DebtController::class, 'store'])->name('debts.store');
        Route::get('/debts/{debt}/edit', [DebtController::class, 'edit'])->name('debts.edit');
        Route::put('/debts/{debt}', [DebtController::class, 'update'])->name('debts.update');
        Route::delete('/debts/{debt}', [DebtController::class, 'destroy'])->name('debts.destroy');
        Route::get('/debts/{debt}/repay', [DebtController::class, 'repayForm'])->name('debts.repay');
        Route::post('/debts/{debt}/repay', [DebtController::class, 'repay'])->name('debts.repay.store');

        // Creditors edit/delete
        Route::get('/creditors/{creditor}/edit', [CreditorController::class, 'edit'])->name('creditors.edit');
        Route::put('/creditors/{creditor}', [CreditorController::class, 'update'])->name('creditors.update');
        Route::delete('/creditors/{creditor}', [CreditorController::class, 'destroy'])->name('creditors.destroy');

        // Categories
        Route::resource('categories', CategoryController::class)->except(['show']);
    });

    // ── Admin-only: User Management ──
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
