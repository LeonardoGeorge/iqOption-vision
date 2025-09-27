<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrendLine extends Model
{
    use HasFactory;

    const DIRECTION_UP = 'up';
    const DIRECTION_DOWN = 'down';
    const DIRECTION_SIDEWAYS = 'sideways';

    protected $fillable = [
        'asset_id',
        'direction',
        'strength',
        'breakout_point',
        'support_level',
        'resistance_level',
        'timeframe',
        'touch_count',
        'is_valid'
    ];

    protected $casts = [
        'strength' => 'decimal:2',
        'breakout_point' => 'decimal:5',
        'support_level' => 'decimal:5',
        'resistance_level' => 'decimal:5',
        'touch_count' => 'integer',
        'is_valid' => 'boolean'
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function getStrengthPercentageAttribute()
    {
        return ($this->strength * 100) . '%';
    }

    public function isValidTrend()
    {
        return $this->is_valid && $this->touch_count >= 3;
    }
}