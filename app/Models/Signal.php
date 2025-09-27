<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signal extends Model
{
    use HasFactory;

    const TYPE_BUY = 'buy';
    const TYPE_SELL = 'sell';
    const TYPE_HOLD = 'hold';

    const RISK_LOW = 'low';
    const RISK_MEDIUM = 'medium';
    const RISK_HIGH = 'high';

    protected $fillable = [
        'asset_id',
        'type',
        'confidence',
        'price',
        'timestamp',
        'trend_strength',
        'risk_level',
        'recommended_action',
        'stop_loss',
        'take_profit',
        'timeframe',
        'signal_source'
    ];

    protected $casts = [
        'confidence' => 'decimal:2',
        'price' => 'decimal:5',
        'timestamp' => 'datetime',
        'trend_strength' => 'decimal:2',
        'stop_loss' => 'decimal:5',
        'take_profit' => 'decimal:5'
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function isBuySignal()
    {
        return $this->type === self::TYPE_BUY;
    }

    public function isSellSignal()
    {
        return $this->type === self::TYPE_SELL;
    }

    public function getConfidencePercentageAttribute()
    {
        return ($this->confidence * 100) . '%';
    }

    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('timestamp', '>=', now()->subHours($hours));
    }

    public function scopeHighConfidence($query, $threshold = 0.7)
    {
        return $query->where('confidence', '>=', $threshold);
    }
}
