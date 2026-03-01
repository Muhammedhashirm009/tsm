<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Debt extends Model
{
    use SoftDeletes;

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
