<?php

namespace App\Services;

class RiskManager
{
    private $maxRiskPercent = 2; // 2% do capital por trade

    public function calculatePositionSize($accountBalance, $entryPrice, $stopLossPrice, $riskPercent = null)
    {
        $riskPercent = $riskPercent ?: $this->maxRiskPercent;
        $riskAmount = $accountBalance * ($riskPercent / 100);

        $priceDifference = abs($entryPrice - $stopLossPrice);
        if ($priceDifference == 0) return 0;

        $positionSize = $riskAmount / $priceDifference;

        return round($positionSize, 2);
    }

    public function calculateRiskLevel($trendStrength, $volatility, $signalConfidence)
    {
        $riskScore = 0;

        // Trend strength (0-1)
        $riskScore += (1 - $trendStrength) * 40;

        // Volatility (higher = more risk)
        $riskScore += min($volatility * 100, 30);

        // Signal confidence (inverse)
        $riskScore += (1 - $signalConfidence) * 30;

        if ($riskScore <= 30) return 'low';
        if ($riskScore <= 60) return 'medium';
        return 'high';
    }

    public function calculateStopLossTakeProfit($entryPrice, $signalType, $volatility, $timeframe)
    {
        $atrMultiplier = $this->getAtrMultiplier($timeframe);
        $volatilityFactor = $volatility * $atrMultiplier;

        if ($signalType === 'buy') {
            $stopLoss = $entryPrice * (1 - $volatilityFactor);
            $takeProfit = $entryPrice * (1 + $volatilityFactor * 1.5);
        } else {
            $stopLoss = $entryPrice * (1 + $volatilityFactor);
            $takeProfit = $entryPrice * (1 - $volatilityFactor * 1.5);
        }

        return [
            'stop_loss' => round($stopLoss, 5),
            'take_profit' => round($takeProfit, 5)
        ];
    }

    private function getAtrMultiplier($timeframe)
    {
        $multipliers = [
            '1m' => 0.001,
            '5m' => 0.002,
            '15m' => 0.003,
            '1h' => 0.005,
            '4h' => 0.008,
            '1d' => 0.015
        ];

        return $multipliers[$timeframe] ?? 0.005;
    }
}
