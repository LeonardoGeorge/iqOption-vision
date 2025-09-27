@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
                <div>
                    <button class="btn btn-outline-primary" wire:click="$refresh">
                        <i class="fas fa-sync-alt"></i> Atualizar
                    </button>
                </div>
            </div>
            <p class="text-muted">Monitoramento em tempo real dos ativos</p>
        </div>
    </div>

    <div class="row">
        <!-- Monitor em Tempo Real -->
        <div class="col-lg-8 col-md-12 mb-4">
            @livewire('real-time-monitor')
        </div>

        <!-- Painel de Trading -->
        <div class="col-lg-4 col-md-12 mb-4">
            @livewire('trading-panel')
        </div>
    </div>

    <!-- Últimos Sinais -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history"></i> Últimos Sinais (24h)
                    </h5>
                </div>
                <div class="card-body">
                    @if($recentSignals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Ativo</th>
                                        <th>Sinal</th>
                                        <th>Confiança</th>
                                        <th>Preço</th>
                                        <th>Horário</th>
                                        <th>Risco</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentSignals as $signal)
                                        <tr class="signal-{{ $signal->type }}">
                                            <td>
                                                <strong>{{ $signal->asset->symbol }}</strong>
                                            </td>
                                            <td>
                                                @if($signal->isBuySignal())
                                                    <span class="badge bg-success">COMPRAR</span>
                                                @elseif($signal->isSellSignal())
                                                    <span class="badge bg-danger">VENDER</span>
                                                @else
                                                    <span class="badge bg-secondary">MANTER</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="progress trend-strength">
                                                    <div class="progress-bar" 
                                                         style="width: {{ $signal->confidence * 100 }}%">
                                                    </div>
                                                </div>
                                                <small>{{ $signal->confidence_percentage }}</small>
                                            </td>
                                            <td>{{ number_format($signal->price, 5) }}</td>
                                            <td>{{ $signal->timestamp->format('H:i') }}</td>
                                            <td>
                                                @if($signal->risk_level == 'low')
                                                    <span class="badge bg-success">Baixo</span>
                                                @elseif($signal->risk_level == 'medium')
                                                    <span class="badge bg-warning">Médio</span>
                                                @else
                                                    <span class="badge bg-danger">Alto</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-info-circle fa-2x mb-2"></i>
                            <p>Nenhum sinal gerado nas últimas 24 horas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection