<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['name', 'book_no', 'description', 'receipt_prefix', 'receipt_start_no', 'receipt_end_no', 'receipt_current_no'];

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class);
    }

    public function getNextReceiptNoAttribute()
    {
        $nextNum = $this->receipt_current_no > 0
            ? $this->receipt_current_no + 1
            : $this->receipt_start_no;

        if ($this->receipt_end_no && $nextNum > $this->receipt_end_no) {
            return null; // range exhausted
        }

        $prefix = $this->receipt_prefix ?? '';
        return $prefix . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
    }

    public function incrementReceiptNo()
    {
        $nextNum = $this->receipt_current_no > 0
            ? $this->receipt_current_no + 1
            : $this->receipt_start_no;
        $this->receipt_current_no = $nextNum;
        $this->save();
        $prefix = $this->receipt_prefix ?? '';
        return $prefix . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
    }
}
