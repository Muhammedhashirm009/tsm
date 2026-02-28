<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    protected $fillable = ['person_name', 'amount', 'date', 'type', 'status', 'paid_amount', 'description', 'account_id', 'creditor_id'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function creditor()
    {
        return $this->belongsTo(Creditor::class);
    }

    public function getRemainingAttribute()
    {
        return $this->amount - $this->paid_amount;
    }
}
