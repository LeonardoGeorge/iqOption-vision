<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class IQOptionAPIService
{
    private $client;
    private $ssid;
    private $isLoggedIn = false;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('iqoption.api.base_url'),
            'timeout' => config('iqoption.api.timeout'),
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'Accept' => 'application/json',
            ],
        ]);

        $this->ssid = Cache::get('iqoption_ssid');
    }

    /**
     * Login na IQ Option
     */
    public function login()
    {
        try {
            // Se já tem SSID válido, verifica se ainda está ativo
            if ($this->ssid && $this->checkSession()) {
                $this->isLoggedIn = true;
                return true;
            }

            $response = $this->client->post('login', [
                'form_params' => [
                    'email' => config('iqoption.credentials.email'),
                    'password' => config('iqoption.credentials.password'),
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            if ($data['status'] === 'success' && isset($data['data']['ssid'])) {
                $this->ssid = $data['data']['ssid'];
                $this->isLoggedIn = true;

                // Salva o SSID no cache por 1 hora
                Cache::put('iqoption_ssid', $this->ssid, 3600);

                Log::info('Login IQ Option realizado com sucesso');
                return true;
            }

            Log::error('Falha no login IQ Option: ' . ($data['message'] ?? 'Unknown error'));
            return false;
        } catch (RequestException $e) {
            Log::error('Erro no login IQ Option: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica se a sessão está ativa
     */
    private function checkSession()
    {
        try {
            $response = $this->client->get('profile', [
                'headers' => [
                    'Cookie' => 'ssid=' . $this->ssid,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            return $data['status'] === 'success';
        } catch (RequestException $e) {
            return false;
        }
    }

    /**
     * Obtém dados de candles em tempo real
     */
    public function getCandles($asset, $timeframe, $count = 100)
    {
        if (!$this->isLoggedIn && !$this->login()) {
            throw new \Exception('Não foi possível fazer login na IQ Option');
        }

        try {
            $response = $this->client->get("candles/{$asset}/{$timeframe}", [
                'headers' => [
                    'Cookie' => 'ssid=' . $this->ssid,
                ],
                'query' => [
                    'count' => $count,
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            if ($data['status'] === 'success') {
                return $data['data'];
            }

            throw new \Exception($data['message'] ?? 'Erro ao obter candles');
        } catch (RequestException $e) {
            Log::error('Erro ao obter candles: ' . $e->getMessage());
            throw new \Exception('Erro de conexão com a IQ Option');
        }
    }

    /**
     * Obtém lista de ativos disponíveis
     */
    public function getAssets()
    {
        if (!$this->isLoggedIn && !$this->login()) {
            throw new \Exception('Não foi possível fazer login na IQ Option');
        }

        try {
            $response = $this->client->get('assets', [
                'headers' => [
                    'Cookie' => 'ssid=' . $this->ssid,
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            if ($data['status'] === 'success') {
                return $data['data'];
            }

            throw new \Exception($data['message'] ?? 'Erro ao obter ativos');
        } catch (RequestException $e) {
            Log::error('Erro ao obter ativos: ' . $e->getMessage());
            throw new \Exception('Erro de conexão com a IQ Option');
        }
    }

    /**
     * Executa um trade
     */
    public function placeTrade($asset, $direction, $amount, $duration)
    {
        if (!$this->isLoggedIn && !$this->login()) {
            throw new \Exception('Não foi possível fazer login na IQ Option');
        }

        try {
            $response = $this->client->post('order', [
                'headers' => [
                    'Cookie' => 'ssid=' . $this->ssid,
                ],
                'form_params' => [
                    'asset' => $asset,
                    'direction' => $direction, // 'call' ou 'put'
                    'amount' => $amount,
                    'duration' => $duration,
                    'balance_id' => config('iqoption.trading.default_balance_id'),
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            if ($data['status'] === 'success') {
                Log::info("Trade executado: {$asset} - {$direction} - {$amount}");
                return $data['data'];
            }

            throw new \Exception($data['message'] ?? 'Erro ao executar trade');
        } catch (RequestException $e) {
            Log::error('Erro ao executar trade: ' . $e->getMessage());
            throw new \Exception('Erro de conexão com a IQ Option');
        }
    }

    /**
     * Obtém o perfil do usuário
     */
    public function getProfile()
    {
        if (!$this->isLoggedIn && !$this->login()) {
            throw new \Exception('Não foi possível fazer login na IQ Option');
        }

        try {
            $response = $this->client->get('profile', [
                'headers' => [
                    'Cookie' => 'ssid=' . $this->ssid,
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            if ($data['status'] === 'success') {
                return $data['data'];
            }

            throw new \Exception($data['message'] ?? 'Erro ao obter perfil');
        } catch (RequestException $e) {
            Log::error('Erro ao obter perfil: ' . $e->getMessage());
            throw new \Exception('Erro de conexão com a IQ Option');
        }
    }

    /**
     * Obtém o saldo da conta
     */
    public function getBalance()
    {
        $profile = $this->getProfile();
        $balanceType = config('iqoption.trading.default_balance_id');

        return $balanceType == 0 ?
            ($profile['balance'] ?? 0) : ($profile['demo_balance'] ?? 0);
    }

    /**
     * Obtém dados em tempo real via WebSocket (simulação)
     */
    public function getRealTimeData($asset, $timeframe = 60)
    {
        // Para uma implementação real, você precisaria de WebSockets
        // Esta é uma versão simplificada que puxa dados recentes
        return $this->getCandles($asset, $timeframe, 50);
    }
}
