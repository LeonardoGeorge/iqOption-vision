<?php

namespace App\Services;

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
            'signal' => $this->generateSignal($closes),
            'volatility' => $this->calculateVolatility($closes)
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

        $volatility = $this->standardDeviation($returns);
        $avgReturn = array_sum($returns) / count($returns);

        // Evitar divisão por zero
        if ($volatility == 0) {
            return 0;
        }

        return min(abs($avgReturn / $volatility), 1.0);
    }

    private function calculateMovingAverages($closes)
    {
        return [
            'sma_20' => $this->sma($closes, 20),
            'sma_50' => $this->sma($closes, 50),
            'ema_12' => $this->ema($closes, 12),
            'ema_26' => $this->ema($closes, 26),
        ];
    }

    private function calculateRSI($closes, $period = 14)
    {
        if (count($closes) < $period + 1) {
            return 50; // Valor neutro se não há dados suficientes
        }

        $gains = [];
        $losses = [];

        for ($i = 1; $i < count($closes); $i++) {
            $change = $closes[$i] - $closes[$i - 1];
            if ($change > 0) {
                $gains[] = $change;
                $losses[] = 0;
            } else {
                $gains[] = 0;
                $losses[] = abs($change);
            }
        }

        // Médias iniciais
        $avgGain = array_sum(array_slice($gains, 0, $period)) / $period;
        $avgLoss = array_sum(array_slice($losses, 0, $period)) / $period;

        // Calcular RSI suavizado
        for ($i = $period; $i < count($gains); $i++) {
            $avgGain = (($avgGain * ($period - 1)) + $gains[$i]) / $period;
            $avgLoss = (($avgLoss * ($period - 1)) + $losses[$i]) / $period;
        }

        if ($avgLoss == 0) {
            return 100;
        }

        $rs = $avgGain / $avgLoss;
        return 100 - (100 / (1 + $rs));
    }

    private function calculateVolatility($closes)
    {
        $returns = [];
        for ($i = 1; $i < count($closes); $i++) {
            $returns[] = log($closes[$i] / $closes[$i - 1]);
        }

        return $this->standardDeviation($returns) * sqrt(252); // Volatilidade anualizada
    }

    private function standardDeviation($array)
    {
        $size = count($array);
        if ($size === 0) {
            return 0;
        }

        $mean = array_sum($array) / $size;
        $carry = 0.0;

        foreach ($array as $val) {
            $d = $val - $mean;
            $carry += $d * $d;
        }

        return sqrt($carry / $size);
    }

    private function sma($data, $period)
    {
        if (count($data) < $period) {
            return 0;
        }

        $slice = array_slice($data, -$period);
        return array_sum($slice) / count($slice);
    }

    private function ema($data, $period)
    {
        if (count($data) < $period) {
            return 0;
        }

        $k = 2 / ($period + 1);
        $ema = array_sum(array_slice($data, 0, $period)) / $period;

        for ($i = $period; $i < count($data); $i++) {
            $ema = $data[$i] * $k + $ema * (1 - $k);
        }

        return $ema;
    }

    private function findSupportLevels($priceData)
    {
        // Implementação simplificada de suporte
        $lows = array_column($priceData, 'low');
        $supportLevels = [];

        if (count($lows) >= 5) {
            $min = min($lows);
            $supportLevels[] = $min;
            $supportLevels[] = $min * 0.995; // Suporte secundário
        }

        return $supportLevels;
    }

    private function findResistanceLevels($priceData)
    {
        // Implementação simplificada de resistência
        $highs = array_column($priceData, 'high');
        $resistanceLevels = [];

        if (count($highs) >= 5) {
            $max = max($highs);
            $resistanceLevels[] = $max;
            $resistanceLevels[] = $max * 1.005; // Resistência secundária
        }

        return $resistanceLevels;
    }

    private function generateSignal($closes)
    {
        if (count($closes) < 20) {
            return 'hold';
        }

        $rsi = $this->calculateRSI($closes);
        $sma20 = $this->sma($closes, 20);
        $currentPrice = end($closes);

        // Lógica básica de sinal
        if ($rsi < 30 && $currentPrice > $sma20) {
            return 'buy';
        } elseif ($rsi > 70 && $currentPrice < $sma20) {
            return 'sell';
        }

        return 'hold';
    }
}
