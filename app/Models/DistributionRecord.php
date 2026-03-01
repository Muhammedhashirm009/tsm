<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributionRecord extends Model
{
    protected $fillable = [
        'distribution_event_id', 'home_id',
        'token_given', 'token_given_at',
        'collected', 'collected_at', 'notes',
    ];

    protected $casts = [
        'token_given' => 'boolean',
        'collected' => 'boolean',
        'token_given_at' => 'datetime',
        'collected_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(DistributionEvent::class, 'distribution_event_id');
    }

    public function home()
    {
        return $this->belongsTo(Home::class);
    }
}
