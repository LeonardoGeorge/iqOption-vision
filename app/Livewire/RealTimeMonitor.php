<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Asset;

class RealTimeMonitor extends Component
{
    public $selectedAssetId;
    public $assets;
    public $priceData = [];
    public $analysis = [];
    public $currentSignal = [];

    public function mount()
    {
        $this->assets = Asset::active()->get();
        $this->selectedAssetId = $this->assets->first()->id ?? null;
        $this->refresh();
    }

    public function refresh()
    {
        if ($this->selectedAssetId) {
            $asset = Asset::find($this->selectedAssetId);

            // Dados simulados para teste
            $this->priceData = $this->getSimulatedPriceData($asset->symbol);
            $this->analysis = $this->analyzePriceData($this->priceData);
            $this->currentSignal = $this->generateSignal($this->analysis);

            // Emitir evento para atualizar o gráfico
            $this->dispatch('priceDataUpdated', priceData: $this->priceData);
        }
    }

    private function getSimulatedPriceData($symbol)
    {
        $data = [];
        $basePrice = rand(10000, 50000);

        for ($i = 50; $i > 0; $i--) {
            $timestamp = now()->subMinutes($i * 5)->timestamp;
            $open = $basePrice + rand(-100, 100);
            $close = $open + rand(-50, 50);
            $high = max($open, $close) + rand(0, 30);
            $low = min($open, $close) - rand(0, 30);

            $data[] = [
                'timestamp' => $timestamp,
                'open' => $open,
                'high' => $high,
                'low' => $low,
                'close' => $close,
                'volume' => rand(1000, 10000)
            ];

            $basePrice = $close;
        }

        return $data;
    }

    private function analyzePriceData($priceData)
    {
        if (empty($priceData)) {
            return [
                'trend_direction' => 'neutral',
                'trend_strength' => 0,
                'rsi' => 50
            ];
        }

        $recentPrices = array_column(array_slice($priceData, -14), 'close');
        $olderPrices = array_column(array_slice($priceData, -28, 14), 'close');

        $recentAvg = array_sum($recentPrices) / count($recentPrices);
        $olderAvg = array_sum($olderPrices) / count($olderPrices);

        $trendStrength = abs($recentAvg - $olderAvg) / $olderAvg;

        return [
            'trend_direction' => $recentAvg > $olderAvg ? 'up' : ($recentAvg < $olderAvg ? 'down' : 'neutral'),
            'trend_strength' => min($trendStrength, 1),
            'rsi' => rand(20, 80) // Simulado
        ];
    }

    private function generateSignal($analysis)
    {
        if (empty($analysis)) {
            return ['type' => 'hold', 'confidence' => 0];
        }

        $confidence = $analysis['trend_strength'] * 100;

        if ($analysis['trend_direction'] === 'up' && $confidence > 60) {
            return ['type' => 'buy', 'confidence' => round($confidence, 2)];
        } elseif ($analysis['trend_direction'] === 'down' && $confidence > 60) {
            return ['type' => 'sell', 'confidence' => round($confidence, 2)];
        }

        return ['type' => 'hold', 'confidence' => round($confidence, 2)];
    }

    public function updatedSelectedAssetId()
    {
        $this->refresh();
    }

    public function render()
    {
        return view('livewire.real-time-monitor');
    }
}
