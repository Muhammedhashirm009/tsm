<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Home extends Model
{
    protected $fillable = [
        'home_number', 'owner_name', 'contact_number',
        'members_count', 'address', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function donations()
    {
        return $this->hasMany(MahalDonation::class);
    }

    public function distributionRecords()
    {
        return $this->hasMany(DistributionRecord::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
