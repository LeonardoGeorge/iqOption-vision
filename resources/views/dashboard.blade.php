<!-- resources/views/dashboard.blade.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Trading</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Inclua o CSS do Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="container-fluid py-4">
        <h1 class="text-center mb-4">🤖 Robô de Trading - IQ Option Vision</h1>
        
        <div class="row">
            <!-- Monitor em Tempo Real -->
            <div class="col-lg-8 col-md-12 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">📊 Monitor em Tempo Real</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4 mb-3">
                                <div class="p-3 border rounded">
                                    <strong>PETR4</strong><br>
                                    <span class="h4 text-success">R$ 35,67</span><br>
                                    <span class="badge bg-success">+1.25%</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="p-3 border rounded">
                                    <strong>VALE3</strong><br>
                                    <span class="h4 text-danger">R$ 68,90</span><br>
                                    <span class="badge bg-danger">-0.75%</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="p-3 border rounded">
                                    <strong>ITUB4</strong><br>
                                    <span class="h4 text-success">R$ 32,15</span><br>
                                    <span class="badge bg-success">+0.45%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Painel de Trading -->
            <div class="col-lg-4 col-md-12 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">🎯 Painel de Trading</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Selecione o Ativo:</label>
                            <select class="form-select">
                                <option value="PETR4">PETR4 - Petrobras</option>
                                <option value="VALE3">VALE3 - Vale</option>
                                <option value="ITUB4">ITUB4 - Itaú</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Quantidade:</label>
                            <input type="number" class="form-control" value="100">
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button class="btn btn-success btn-lg">
                                ✅ COMPRAR AGORA
                            </button>
                            <button class="btn btn-danger btn-lg">
                                🚨 VENDER AGORA
                            </button>
                        </div>
                        
                        <div class="mt-3 p-2 bg-light rounded">
                            <small>Saldo disponível: <strong>R$ 10.000,00</strong></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos e Análises -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0">📈 Análise Técnica</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="p-2">
                                    <strong>RSI</strong><br>
                                    <span class="h5">54.2</span><br>
                                    <span class="badge bg-warning">Neutro</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-2">
                                    <strong>MACD</strong><br>
                                    <span class="h5">0.45</span><br>
                                    <span class="badge bg-success">Compra</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-2">
                                    <strong>Suporte</strong><br>
                                    <span class="h5">R$ 34,50</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-2">
                                    <strong>Resistência</strong><br>
                                    <span class="h5">R$ 36,80</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>