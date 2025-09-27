<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Asset;
use App\Services\IQOptionService;
use App\Services\TrendAnalyzer;

class RealTimeMonitor extends Component
{
    public $selectedAssetId;
    public $assets;
    public $priceData = [];
    public $analysis = [];
    public $currentSignal = [];

    protected $listeners = ['refreshData' => 'refresh'];

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
            $iqOptionService = new IQOptionService();
            $trendAnalyzer = new TrendAnalyzer();

            $priceResponse = $iqOptionService->getPriceData($asset->symbol, 5, 50);
            $this->priceData = $priceResponse['data'] ?? [];
            $this->analysis = $trendAnalyzer->analyzePriceData($this->priceData);
            $this->currentSignal = $this->generateSignal($this->analysis);
        }
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
