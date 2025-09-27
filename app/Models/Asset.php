<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'symbol',
        'type',
        'active',
        'iqoption_id',
        'current_price',
        'price_updated_at'
    ];

    protected $casts = [
        'active' => 'boolean',
        'current_price' => 'decimal:5',
        'price_updated_at' => 'datetime'
    ];

    public function trendLines()
    {
        return $this->hasMany(TrendLine::class);
    }

    public function signals()
    {
        return $this->hasMany(Signal::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeForex($query)
    {
        return $query->where('type', 'forex');
    }

    public function scopeCrypto($query)
    {
        return $query->where('type', 'crypto');
    }
}