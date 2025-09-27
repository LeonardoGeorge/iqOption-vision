<?php
// app/Providers/IqOptionMockServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class IqOptionMockServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Mock da API IQ Option para desenvolvimento
        $this->app->bind('iqoption.api', function () {
            return new class {
                public function connect()
                {
                    return ['connected' => true, 'message' => 'Modo demo ativo'];
                }

                public function getAssets()
                {
                    return [
                        'PETR4' => ['price' => 35.67, 'change' => 1.25],
                        'VALE3' => ['price' => 68.90, 'change' => -0.75],
                        'ITUB4' => ['price' => 32.15, 'change' => 0.45],
                        'BBDC4' => ['price' => 25.80, 'change' => 0.30],
                    ];
                }

                public function placeOrder($asset, $action, $amount)
                {
                    // Simula uma ordem bem-sucedida
                    return [
                        'success' => true,
                        'order_id' => 'DEMO_' . time(),
                        'asset' => $asset,
                        'action' => $action,
                        'amount' => $amount,
                        'result' => rand(0, 1) ? 'win' : 'loss',
                        'profit' => $action === 'call' ? $amount * 0.8 : -$amount * 0.8
                    ];
                }
            };
        });
    }
}
