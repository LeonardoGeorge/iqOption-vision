<div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-tradingview"></i> Painel de Trading
            </h5>
        </div>
        <div class="card-body">
            <!-- Saldo da Conta -->
            <div class="mb-4">
                <label class="form-label">Saldo da Conta (Simulação)</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" class="form-control" wire:model="accountBalance" min="100" step="100">
                </div>
                <small class="text-muted">Valor simulado para cálculo de posição</small>
            </div>

            <!-- Sinais Recentes -->
            <h6 class="border-bottom pb-2">Sinais Recentes</h6>
            
            @if(count($recentSignals) > 0)
                <div class="list-group list-group-flush">
                    @foreach($recentSignals as $signal)
                        <div class="list-group-item signal-{{ $signal['type'] }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>{{ $signal['asset']['symbol'] }}</strong>
                                    <br>
                                    <small>
                                        @if($signal['type'] == 'buy')
                                            <span class="text-success">COMPRAR</span>
                                        @elseif($signal['type'] == 'sell')
                                            <span class="text-danger">VENDER</span>
                                        @else
                                            <span class="text-secondary">MANTER</span>
                                        @endif
                                        • {{ number_format($signal['price'], 5) }}
                                    </small>
                                    <br>
                                    <small class="text-muted">
                                        {{ new Date(signal['timestamp']).toLocaleTimeString() }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <div class="mb-1">
                                        <span class="badge bg-{{ $signal['confidence'] > 0.7 ? 'success' : ($signal['confidence'] > 0.5 ? 'warning' : 'danger') }}">
                                            {{ (signal['confidence'] * 100).toFixed(0) }}%
                                        </span>
                                    </div>
                                    @if($signal['type'] !== 'hold')
                                        <button class="btn btn-sm btn-outline-primary" 
                                                wire:click="executeTrade({{ $signal['id'] }}, '{{ $signal['type'] }}')">
                                            Executar
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-muted py-3">
                    <i class="fas fa-bell-slash fa-2x mb-2"></i>
                    <p>Nenhum sinal recente</p>
                </div>
            @endif

            <!-- Aviso de Risco -->
            <div class="alert alert-warning mt-3 small">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Atenção:</strong> Este é um sistema de simulação. 
                Trading real envolve riscos significativos.
            </div>
        </div>
    </div>
</div>