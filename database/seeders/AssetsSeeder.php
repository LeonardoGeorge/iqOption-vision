<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asset;

class AssetsSeeder extends Seeder
{
    public function run()
    {
        $assets = [
            // Forex Majors
            ['symbol' => 'EURUSD', 'name' => 'Euro vs US Dollar', 'type' => 'forex'],
            ['symbol' => 'GBPUSD', 'name' => 'British Pound vs US Dollar', 'type' => 'forex'],
            ['symbol' => 'USDJPY', 'name' => 'US Dollar vs Japanese Yen', 'type' => 'forex'],
            ['symbol' => 'USDCHF', 'name' => 'US Dollar vs Swiss Franc', 'type' => 'forex'],
            ['symbol' => 'AUDUSD', 'name' => 'Australian Dollar vs US Dollar', 'type' => 'forex'],
            ['symbol' => 'USDCAD', 'name' => 'US Dollar vs Canadian Dollar', 'type' => 'forex'],
            ['symbol' => 'NZDUSD', 'name' => 'New Zealand Dollar vs US Dollar', 'type' => 'forex'],

            // Forex Minors
            ['symbol' => 'EURGBP', 'name' => 'Euro vs British Pound', 'type' => 'forex'],
            ['symbol' => 'EURJPY', 'name' => 'Euro vs Japanese Yen', 'type' => 'forex'],
            ['symbol' => 'GBPJPY', 'name' => 'British Pound vs Japanese Yen', 'type' => 'forex'],
            ['symbol' => 'EURCHF', 'name' => 'Euro vs Swiss Franc', 'type' => 'forex'],
            ['symbol' => 'AUDJPY', 'name' => 'Australian Dollar vs Japanese Yen', 'type' => 'forex'],

            // Exotic
            ['symbol' => 'USDTRY', 'name' => 'US Dollar vs Turkish Lira', 'type' => 'forex'],
            ['symbol' => 'USDZAR', 'name' => 'US Dollar vs South African Rand', 'type' => 'forex'],
            ['symbol' => 'USDBRL', 'name' => 'US Dollar vs Brazilian Real', 'type' => 'forex'],

            // Crypto (simulação)
            ['symbol' => 'BTCUSD', 'name' => 'Bitcoin vs US Dollar', 'type' => 'crypto'],
            ['symbol' => 'ETHUSD', 'name' => 'Ethereum vs US Dollar', 'type' => 'crypto'],
        ];

        foreach ($assets as $asset) {
            Asset::updateOrCreate(
                ['symbol' => $asset['symbol']],
                $asset
            );
        }

        $this->command->info(count($assets) . ' ativos foram seedados!');
    }
}
