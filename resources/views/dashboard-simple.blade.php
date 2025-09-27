<!-- resources/views/dashboard-simple.blade.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Trading</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid py-4">
        <h1 class="text-center">🤖 Robô de Trading</h1>
        
        <div class="row mt-4">
            <div class="col-lg-8 col-md-12 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">📊 Monitor em Tempo Real</h5>
                    </div>
                    <div class="card-body">
                        <h3>PETR4: <span class="text-success">R$ 35,67</span></h3>
                        <p class="text-success">↑ +1.25% hoje</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-12 mb-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">🎯 Painel de Trading</h5>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-success w-100 mb-2">✅ COMPRAR PETR4</button>
                        <button class="btn btn-danger w-100">🚨 VENDER PETR4</button>
                        <div class="mt-3 p-2 bg-light rounded">
                            <small>Saldo: <strong>R$ 10.000,00</strong></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>