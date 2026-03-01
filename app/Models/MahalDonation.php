<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MahalDonation extends Model
{
    protected $fillable = [
        'book_id', 'receipt_no', 'account_id', 'home_id', 'amount', 'date', 'donor_name',
        'payment_method', 'description', 'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    public function book()
    {
        return $this->belongsTo(\App\Models\Book::class);
    }

    public function account()
    {
        return $this->belongsTo(\App\Models\Account::class);
    }

    public function home()
    {
        return $this->belongsTo(Home::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
