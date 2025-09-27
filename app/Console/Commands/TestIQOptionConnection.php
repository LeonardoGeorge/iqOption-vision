<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\IQOptionAPIService;

class TestIQOptionConnection extends Command
{
    protected $signature = 'iqoption:test';
    protected $description = 'Testar conexão com a IQ Option API';

    public function handle()
    {
        $this->info('Testando conexão com IQ Option API...');

        $apiService = new IQOptionAPIService();

        if ($apiService->login()) {
            $this->info('✅ Login realizado com sucesso!');

            // Testar obtenção de perfil
            $profile = $apiService->getProfile();
            $this->info('✅ Perfil obtido: ' . ($profile['email'] ?? 'N/A'));

            // Testar saldo
            $balance = $apiService->getBalance();
            $this->info("✅ Saldo: {$balance}");

            // Testar ativos
            $assets = $apiService->getAssets();
            $this->info("✅ Ativos disponíveis: " . count($assets));
        } else {
            $this->error('❌ Falha no login. Verifique suas credenciais no .env');
        }
    }
}
