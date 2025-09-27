<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iqOption Vision - Monitor de Tendências</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        .navbar-brand { font-weight: bold; }
        .signal-buy { background-color: #d4edda !important; }
        .signal-sell { background-color: #f8d7da !important; }
        .signal-hold { background-color: #e2e3e5 !important; }
        .price-up { color: #28a745; }
        .price-down { color: #dc3545; }
        .trend-strength { height: 10px; }
        .asset-card { transition: transform 0.2s; }
        .asset-card:hover { transform: translateY(-2px); }
    </style>
    
    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-chart-line"></i> iqOption Vision
            </a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a class="nav-link" href="{{ route('assets.index') }}">
                    <i class="fas fa-coins"></i> Ativos
                </a>
                <a class="nav-link" href="{{ route('signals.index') }}">
                    <i class="fas fa-bell"></i> Sinais
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white mt-5">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-exclamation-triangle"></i> Aviso Importante</h5>
                    <p class="small">
                        Este sistema é para fins educacionais e de análise. Trading envolve riscos. 
                        Não nos responsabilizamos por perdas financeiras.
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="small mb-0">
                        iqOption Vision &copy; {{ date('Y') }} - Sistema de Monitoramento
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Livewire Scripts -->
    @livewireScripts
    
    <!-- Custom JS -->
    @stack('scripts')
</body>
</html>