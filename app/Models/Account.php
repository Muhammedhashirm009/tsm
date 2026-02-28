<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['name', 'type', 'opening_balance', 'description'];

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class);
    }

    public function debts()
    {
        return $this->hasMany(Debt::class);
    }

    public function getBalanceAttribute()
    {
        $income = $this->receipts()->sum('amount');
        $expense = $this->vouchers()->sum('amount');
        // Borrowed debt credits the account, repayments (paid_amount) debit it
        $debtBorrowed = $this->debts()->where('type', 'borrowed')->sum('amount');
        $debtRepaid = $this->debts()->where('type', 'borrowed')->sum('paid_amount');
        return $this->opening_balance + $income - $expense + $debtBorrowed - $debtRepaid;
    }

    public function getTotalIncomeAttribute()
    {
        return $this->receipts()->sum('amount');
    }

    public function getTotalExpenseAttribute()
    {
        return $this->vouchers()->sum('amount');
    }
}
