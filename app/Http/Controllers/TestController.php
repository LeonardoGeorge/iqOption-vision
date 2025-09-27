<?php
// app/Http\Controllers/TestController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // ← ADICIONE ESTA LINHA

class TestController extends Controller
{
    public function testSystem()
    {
        // Teste de conexão com banco - CORRIGIDO
        try {
            DB::connection()->getPdo(); // ← REMOVE A BARRA INVERTIDA
            $dbStatus = '✅ Conectado';
        } catch (\Exception $e) {
            $dbStatus = '❌ Erro: ' . $e->getMessage();
        }

        // Teste de ambiente
        $envStatus = app()->environment('local') ? '✅ Local' : '❌ Produção';

        // Teste de configuração
        $apiStatus = config('app.env') === 'local' ? '✅ Modo Demo' : '❌ Modo Real';

        // Dados simulados (remove a chamada app('iqoption.api') por enquanto)
        $assets = [
            'PETR4' => ['price' => 35.67, 'change' => 1.25],
            'VALE3' => ['price' => 68.90, 'change' => -0.75],
            'ITUB4' => ['price' => 32.15, 'change' => 0.45],
        ];

        return view('system-test', [
            'dbStatus' => $dbStatus,
            'envStatus' => $envStatus,
            'apiStatus' => $apiStatus,
            'assets' => $assets // ← USA OS DADOS SIMULADOS
        ]);
    }
}
