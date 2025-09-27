<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-chart-line"></i> Monitor em Tempo Real
            </h5>
            <div class="d-flex gap-2">
                <select wire:model="selectedAssetId" class="form-select form-select-sm" wire:change="refresh">
                    <option value="">Selecione um ativo</option>
                    @foreach($assets as $asset)
                        <option value="{{ $asset->id }}">{{ $asset->symbol }} - {{ $asset->name }}</option>
                    @endforeach
                </select>
                <button class="btn btn-sm btn-outline-secondary" wire:click="refresh" 
                        wire:loading.attr="disabled">
                    <i class="fas fa-sync-alt" wire:loading.class="fa-spin"></i>
                </button>
            </div>
        </div>
        
        <div class="card-body">
            @if($selectedAssetId && count($priceData) > 0)
                <!-- Gráfico -->
                <div class="mb-4">
                    <canvas id="priceChart" height="300"></canvas>
                </div>

                <!-- Informações do Sinal -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card signal-{{ $currentSignal['type'] }}">
                            <div class="card-body text-center">
                                <h6 class="card-title">Sinal Atual</h6>
                                @if($currentSignal['type'] == 'buy')
                                    <h2 class="text-success"><i class="fas fa-arrow-up"></i> COMPRAR</h2>
                                @elseif($currentSignal['type'] == 'sell')
                                    <h2 class="text-danger"><i class="fas fa-arrow-down"></i> VENDER</h2>
                                @else
                                    <h2 class="text-secondary"><i class="fas fa-pause"></i> MANTER</h2>
                                @endif
                                <p class="mb-0">Confiança: <strong>{{ $currentSignal['confidence'] }}%</strong></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Análise Técnica</h6>
                                <div class="mb-2">
                                    <small>Tendência: </small>
                                    <strong class="float-end text-uppercase">
                                        @if($analysis['trend_direction'] == 'up')
                                            <span class="text-success">ALTA ↑</span>
                                        @elseif($analysis['trend_direction'] == 'down')
                                            <span class="text-danger">BAIXA ↓</span>
                                        @else
                                            <span class="text-warning">LATERAL →</span>
                                        @endif
                                    </strong>
                                </div>
                                <div class="mb-2">
                                    <small>Força: </small>
                                    <div class="progress trend-strength float-end" style="width: 100px;">
                                        <div class="progress-bar bg-{{ $analysis['trend_strength'] > 0.6 ? 'success' : ($analysis['trend_strength'] > 0.3 ? 'warning' : 'danger') }}" 
                                             style="width: {{ $analysis['trend_strength'] * 100 }}%">
                                        </div>
                                    </div>
                                </div>
                                @if(isset($analysis['rsi']))
                                <div class="mb-0">
                                    <small>RSI: </small>
                                    <strong class="float-end {{ $analysis['rsi'] > 70 ? 'text-danger' : ($analysis['rsi'] < 30 ? 'text-success' : 'text-warning') }}">
                                        {{ number_format($analysis['rsi'], 2) }}
                                    </strong>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Últimos Preços -->
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Horário</th>
                                <th>Abertura</th>
                                <th>Máxima</th>
                                <th>Mínima</th>
                                <th>Fechamento</th>
                                <th>Volume</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(array_slice($priceData, -10) as $candle)
                                <tr>
                                    <td>{{ date('H:i', $candle['timestamp']) }}</td>
                                    <td>{{ number_format($candle['open'], 5) }}</td>
                                    <td class="text-success">{{ number_format($candle['high'], 5) }}</td>
                                    <td class="text-danger">{{ number_format($candle['low'], 5) }}</td>
                                    <td class="{{ $candle['close'] >= $candle['open'] ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($candle['close'], 5) }}
                                    </td>
                                    <td>{{ number_format($candle['volume']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center text-muted py-5">
                    <i class="fas fa-chart-line fa-3x mb-3"></i>
                    <p>Selecione um ativo para iniciar o monitoramento</p>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            const ctx = document.getElementById('priceChart')?.getContext('2d');
            if (!ctx) return;

            let chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Preço',
                        data: [],
                        borderColor: '#007bff',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: false }
                    }
                }
            });

            // Atualizar gráfico quando os dados mudarem
            Livewire.on('priceDataUpdated', (data) => {
                if (chart && data.priceData) {
                    const labels = data.priceData.map(item => 
                        new Date(item.timestamp * 1000).toLocaleTimeString()
                    );
                    const prices = data.priceData.map(item => item.close);
                    
                    chart.data.labels = labels;
                    chart.data.datasets[0].data = prices;
                    chart.update();
                }
            });

            // Disparar atualização quando o componente for carregado
            Livewire.hook('message.processed', (message) => {
                if (message.updateQueue[0]?.payload?.event === 'refreshData') {
                    setTimeout(() => {
                        Livewire.emit('priceDataUpdated', {
                            priceData: @this.priceData
                        });
                    }, 100);
                }
            });
        });
    </script>
    @endpush
</div>