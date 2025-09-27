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
        $this->loadRecentSignals();
    }

    public function loadRecentSignals()
    {
        $this->recentSignals = Signal::with('asset')
            ->recent(6) // Últimas 6 horas
            ->orderBy('timestamp', 'desc')
            ->limit(5)
            ->get()
            ->toArray();
    }

    public function addSignal($signalData)
    {
        // Adicionar novo sinal ao início da lista
        array_unshift($this->recentSignals, $signalData);

        // Manter apenas os 5 mais recentes
        $this->recentSignals = array_slice($this->recentSignals, 0, 5);
    }

    public function executeTrade($signalId, $action)
    {
        $signal = Signal::find($signalId);
        if (!$signal) {
            $this->dispatchBrowserEvent('show-alert', [
                'type' => 'error',
                'message' => 'Sinal não encontrado!'
            ]);
            return;
        }

        $riskManager = new RiskManager();

        // Simular execução do trade
        $tradeData = [
            'signal_id' => $signal->id,
            'action' => $action,
            'executed_at' => now(),
            'status' => 'executed',
            'position_size' => $riskManager->calculatePositionSize(
                $this->accountBalance,
                $signal->price,
                $signal->stop_loss
            )
        ];

        $this->dispatchBrowserEvent('show-alert', [
            'type' => 'success',
            'message' => "Trade executado! Ação: {$action}, Ativo: {$signal->asset->symbol}"
        ]);

        $this->emit('tradeExecuted', $tradeData);
    }

    public function render()
    {
        return view('livewire.trading-panel');
    }
}
