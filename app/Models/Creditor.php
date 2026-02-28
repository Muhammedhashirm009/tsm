<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Creditor extends Model
{
    protected $fillable = ['name', 'phone', 'description'];

    public function debts()
    {
        return $this->hasMany(Debt::class);
    }

    public function getTotalBorrowedAttribute()
    {
        return $this->debts()->sum('amount');
    }

    public function getTotalRepaidAttribute()
    {
        return $this->debts()->sum('paid_amount');
    }

    public function getOutstandingAttribute()
    {
        return $this->total_borrowed - $this->total_repaid;
    }
}
