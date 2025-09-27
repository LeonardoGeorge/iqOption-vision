<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Models\Asset;

class IQOptionService
{
    private $apiService;

    public function __construct()
    {
        $this->apiService = new IQOptionAPIService();
    }

    /**
     * Obtém dados de preço para um ativo
     */
    public function getPriceData($assetSymbol, $timeframe = 60, $count = 100)
    {
        try {
            // Tenta converter símbolo para formato IQ Option
            $iqSymbol = $this->convertSymbolToIQFormat($assetSymbol);

            $candles = $this->apiService->getCandles($iqSymbol, $timeframe, $count);

            return [
                'success' => true,
                'data' => $this->formatCandleData($candles),
                'symbol' => $assetSymbol,
                'timeframe' => $timeframe
            ];
        } catch (\Exception $e) {
            Log::error('Erro ao obter dados de preço: ' . $e->getMessage());

            // Fallback para dados mock se a API falhar
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'data' => $this->generateMockPriceData($assetSymbol, $timeframe, $count),
                'symbol' => $assetSymbol,
                'timeframe' => $timeframe
            ];
        }
    }

    /**
     * Obtém ativos disponíveis da IQ Option
     */
    public function getAvailableAssets()
    {
        try {
            $assets = $this->apiService->getAssets();
            return $this->formatAssets($assets);
        } catch (\Exception $e) {
            Log::error('Erro ao obter ativos: ' . $e->getMessage());

            // Fallback para lista padrão
            return $this->getDefaultAssets();
        }
    }

    /**
     * Executa um trade baseado em um sinal
     */
    public function executeSignal($signal, $amount = null)
    {
        try {
            $amount = $amount ?: config('iqoption.trading.default_amount');
            $direction = $signal->type === 'buy' ? 'call' : 'put';
            $iqSymbol = $this->convertSymbolToIQFormat($signal->asset->symbol);

            $result = $this->apiService->placeTrade(
                $iqSymbol,
                $direction,
                $amount,
                config('iqoption.trading.default_duration')
            );

            return [
                'success' => true,
                'trade_id' => $result['id'] ?? null,
                'message' => 'Trade executado com sucesso'
            ];
        } catch (\Exception $e) {
            Log::error('Erro ao executar trade: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Converte símbolo para formato IQ Option
     */
    private function convertSymbolToIQFormat($symbol)
    {
        $conversions = [
            'EURUSD' => 'EURUSD',
            'GBPUSD' => 'GBPUSD',
            'USDJPY' => 'USDJPY',
            'USDCHF' => 'USDCHF',
            'AUDUSD' => 'AUDUSD',
            'USDCAD' => 'USDCAD',
            'EURGBP' => 'EURGBP',
            'EURJPY' => 'EURJPY',
            'GBPJPY' => 'GBPJPY',
            'USDTRY' => 'USDTRY',
            'USDZAR' => 'USDZAR',
            'USDBRL' => 'USDBRL',
            'BTCUSD' => 'BTCUSD',
            'ETHUSD' => 'ETHUSD',
        ];

        return $conversions[$symbol] ?? $symbol;
    }

    /**
     * Formata dados de candles
     */
    private function formatCandleData($candles)
    {
        $formatted = [];

        foreach ($candles as $candle) {
            $formatted[] = [
                'timestamp' => $candle['from'],
                'open' => $candle['open'],
                'high' => $candle['max'],
                'low' => $candle['min'],
                'close' => $candle['close'],
                'volume' => $candle['volume'] ?? 0,
            ];
        }

        return $formatted;
    }

    /**
     * Formata lista de ativos
     */
    private function formatAssets($assets)
    {
        $formatted = [];

        foreach ($assets as $asset) {
            if ($asset['active'] && in_array($asset['type'], ['forex', 'crypto'])) {
                $formatted[] = [
                    'symbol' => $asset['name'],
                    'name' => $asset['description'] ?? $asset['name'],
                    'type' => $asset['type'],
                    'iqoption_id' => $asset['id'],
                    'active' => $asset['active'],
                ];
            }
        }

        return $formatted;
    }

    /**
     * Lista padrão de ativos (fallback)
     */
    private function getDefaultAssets()
    {
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

    /**
     * Gera dados mock (fallback)
     */
    private function generateMockPriceData($symbol, $timeframe, $count)
    {
        $data = [];
        $basePrice = mt_rand(10000, 20000) / 100;

        for ($i = $count; $i > 0; $i--) {
            $timestamp = now()->subMinutes($timeframe * $i)->timestamp;
            $open = $basePrice + mt_rand(-50, 50) / 100;
            $close = $open + mt_rand(-30, 30) / 100;
            $high = max($open, $close) + mt_rand(0, 20) / 100;
            $low = min($open, $close) - mt_rand(0, 20) / 100;

            $data[] = [
                'timestamp' => $timestamp,
                'open' => $open,
                'high' => $high,
                'low' => $low,
                'close' => $close,
                'volume' => mt_rand(1000, 10000),
            ];

            $basePrice = $close;
        }

        return $data;
    }
}
