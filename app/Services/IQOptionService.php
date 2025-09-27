<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Models\Asset;

class IQOptionService
{
    private $client;
    private $baseUrl = 'https://api.iqoption.com/api/';

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => 30,
            'verify' => false
        ]);
    }

    public function getPriceData($assetSymbol, $timeframe = 60, $count = 100)
    {
        try {
            // Simulação de dados - substituir por API real da IQ Option
            $response = [
                'success' => true,
                'data' => $this->generateMockPriceData($assetSymbol, $timeframe, $count)
            ];

            return $response;
        } catch (\Exception $e) {
            Log::error('IQOption API Error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getAvailableAssets()
    {
        // Assets Forex mais populares na IQ Option
        return [
            ['symbol' => 'EURUSD', 'name' => 'Euro vs US Dollar', 'type' => 'forex'],
            ['symbol' => 'GBPUSD', 'name' => 'British Pound vs US Dollar', 'type' => 'forex'],
            ['symbol' => 'USDJPY', 'name' => 'US Dollar vs Japanese Yen', 'type' => 'forex'],
            ['symbol' => 'USDCHF', 'name' => 'US Dollar vs Swiss Franc', 'type' => 'forex'],
            ['symbol' => 'AUDUSD', 'name' => 'Australian Dollar vs US Dollar', 'type' => 'forex'],
            ['symbol' => 'USDCAD', 'name' => 'US Dollar vs Canadian Dollar', 'type' => 'forex'],
            ['symbol' => 'EURGBP', 'name' => 'Euro vs British Pound', 'type' => 'forex'],
            ['symbol' => 'EURJPY', 'name' => 'Euro vs Japanese Yen', 'type' => 'forex'],
        ];
    }

    private function generateMockPriceData($symbol, $timeframe, $count)
    {
        $data = [];
        $basePrice = mt_rand(10000, 20000) / 100; // Preço base aleatório

        for ($i = $count; $i > 0; $i--) {
            $timestamp = now()->subMinutes($timeframe * $i);
            $open = $basePrice + mt_rand(-50, 50) / 100;
            $close = $open + mt_rand(-30, 30) / 100;
            $high = max($open, $close) + mt_rand(0, 20) / 100;
            $low = min($open, $close) - mt_rand(0, 20) / 100;
            $volume = mt_rand(1000, 10000);

            $data[] = [
                'timestamp' => $timestamp->timestamp,
                'open' => $open,
                'high' => $high,
                'low' => $low,
                'close' => $close,
                'volume' => $volume
            ];

            $basePrice = $close;
        }

        return $data;
    }
}
