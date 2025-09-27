<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Signal;
use App\Models\Asset;
use App\Services\RiskManager;

class TradingPanel extends Component
{
    public $recentSignals = [];
    public $accountBalance = 1000;
    public $selectedSignal = null;

    protected $listeners = ['signalGenerated' => 'addSignal'];

    public function mount()
    {
        $this->recentSignals = Signal::with('asset')
            ->recent(6)
            ->orderBy('timestamp', 'desc')
            ->limit(5)
            ->get()
            ->toArray();
    }

    public function addSignal($signalData)
    {
        $this->recentSignals = array_slice(
            array_merge([$signalData], $this->recentSignals),
            0,
            5
        );
    }

    public function executeTrade($signalId, $action)
    {
        $signal = Signal::find($signalId);
        if (!$signal) return;

        $riskManager = new RiskManager();

        $tradeData = [
            'signal_id' => $signal->id,
            'action' => $action,
            'executed_at' => now(),
            'status' => 'executed'
        ];

        // Aqui você integraria com a API real da IQ Option
        $this->emit('tradeExecuted', $tradeData);
    }

    public function render()
    {
        return view('livewire.trading-panel');
    }
}
