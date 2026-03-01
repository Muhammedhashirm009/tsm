<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributionEvent extends Model
{
    protected $fillable = [
        'title', 'description', 'event_date',
        'items_description', 'status',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function records()
    {
        return $this->hasMany(DistributionRecord::class);
    }

    public function getTokensGivenCountAttribute()
    {
        return $this->records()->where('token_given', true)->count();
    }

    public function getCollectedCountAttribute()
    {
        return $this->records()->where('collected', true)->count();
    }

    public function getTotalHomesAttribute()
    {
        return $this->records()->count();
    }
}
