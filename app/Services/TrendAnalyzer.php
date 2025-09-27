<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\TrendLine;
use App\Models\Signal;


class TrendAnalyzer
{
    public function analyzePriceData($priceData)
    {
        if (count($priceData) < 20) {
            return ['error' => 'Dados insuficientes para análise'];
        }

        $closes = array_column($priceData, 'close');

        return [
            'trend_direction' => $this->determineTrendDirection($closes),
            'trend_strength' => $this->calculateTrendStrength($closes),
            'support_levels' => $this->findSupportLevels($priceData),
            'resistance_levels' => $this->findResistanceLevels($priceData),
            'moving_averages' => $this->calculateMovingAverages($closes),
            'rsi' => $this->calculateRSI($closes),
            'signal' => $this->generateSignal($closes)
        ];
    }

    private function determineTrendDirection($closes)
    {
        $sma20 = $this->sma($closes, 20);
        $sma50 = $this->sma($closes, 50);

        if ($sma20 > $sma50) {
            return 'up';
        } elseif ($sma20 < $sma50) {
            return 'down';
        }

        return 'sideways';
    }

    private function calculateTrendStrength($closes)
    {
        $returns = [];
        for ($i = 1; $i < count($closes); $i++) {
            $returns[] = ($closes[$i] - $closes[$i - 1]) / $closes[$i - 1];
        }

        $volatility = stats_standard_deviation($returns);
        $avgReturn = array_sum($returns) / count($returns);

        return abs($avgReturn / ($volatility ?: 0.0001));
    }

    private function calculateMovingAverages($closes)
    {
        return [
            'sma_20' => $this->sma($closes, 20),
            'sma_50' => $this->sma($closes, 50),
            'ema_12' => $this->ema($closes, 12),
        ];
    }

    private function calculateRSI($closes, $period = 14)
    {
        $changes = [];
        for ($i = 1; $i < count($closes); $i++) {
            $changes[] = $closes[$i] - $closes[$i - 1];
        }

        $gains = $losses = [];
        foreach ($changes as $change) {
            $gains[] = $change > 0 ? $change : 0;
            $losses[] = $change < 0 ? abs($change) : 0;
        }

        $avgGain = array_sum(array_slice($gains, 0, $period)) / $period;
        $avgLoss = array_sum(array_slice($losses, 0, $period)) / $period;

        for ($i = $period; $i < count($gains); $i++) {
            $avgGain = ($avgGain * ($period - 1) + $gains[$i]) / $period;
            $avgLoss = ($avgLoss * ($period - 1) + $losses[$i]) / $period;
        }

        if ($avgLoss == 0) return 100;

        $rs = $avgGain / $avgLoss;
        return 100 - (100 / (1 + $rs));
    }

    private function sma($data, $period)
    {
        $slice = array_slice($data, -$period);
        return array_sum($slice) / count($slice);
    }

    private function ema($data, $period)
    {
        $k = 2 / ($period + 1);
        $ema = array_slice($data, 0, $period);
        $ema = array_sum($ema) / $period;

        for ($i = $period; $i < count($data); $i++) {
            $ema = $data[$i] * $k + $ema * (1 - $k);
        }

        return $ema;
    }

    private function findSupportLevels($priceData)
    {
        return [];
    }
    private function findResistanceLevels($priceData)
    {
        return [];
    }
    private function generateSignal($closes)
    {
        return 'hold';
    }
}
