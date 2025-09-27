<!-- resources/views/system-test.blade.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste do Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">🧪 Teste do Sistema - IQ Option Vision</h1>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Status do Sistema</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Banco de Dados:</strong> {{ $dbStatus }}</p>
                        <p><strong>Ambiente:</strong> {{ $envStatus }}</p>
                        <p><strong>Modo API:</strong> {{ $apiStatus }}</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Ações Rápidas</h5>
                    </div>
                    <div class="card-body">
                        <a href="/dashboard" class="btn btn-primary w-100 mb-2">📊 Ir para Dashboard</a>
                        <a href="/" class="btn btn-secondary w-100">🏠 Página Inicial</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">📊 Dados de Mercado (Simulados)</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    @foreach($assets as $asset => $data)
                    <div class="col-md-4 mb-3">
                        <div class="p-3 border rounded bg-light">
                            <strong class="h5">{{ $asset }}</strong><br>
                            <span class="h4 text-primary">R$ {{ number_format($data['price'], 2) }}</span><br>
                            <span class="badge bg-{{ $data['change'] >= 0 ? 'success' : 'danger' }}">
                                {{ $data['change'] >= 0 ? '↑' : '↓' }} {{ number_format(abs($data['change']), 2) }}%
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</body>
</html>