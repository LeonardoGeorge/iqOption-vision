<?php

return [
    'credentials' => [
        'email' => env('IQOPTION_EMAIL'),
        'password' => env('IQOPTION_PASSWORD'),
        'account_type' => env('IQOPTION_ACCOUNT_TYPE', 'demo'),
    ],

    'api' => [
        'base_url' => env('IQOPTION_API_URL', 'https://api.iqoption.com/api/'),
        'ws_url' => env('IQOPTION_WS_URL', 'wss://iqoption.com/echo/websocket'),
        'timeout' => env('IQOPTION_API_TIMEOUT', 30),
    ],

    'trading' => [
        'default_balance_id' => env('IQOPTION_DEFAULT_BALANCE_ID', 1),
        'default_amount' => env('IQOPTION_DEFAULT_AMOUNT', 10),
        'default_duration' => env('IQOPTION_DEFAULT_DURATION', 5),
        'demo_mode' => env('IQOPTION_ACCOUNT_TYPE', 'demo') === 'demo',
    ],

    'analysis' => [
        'rsi_period' => 14,
        'sma_period_short' => 20,
        'sma_period_long' => 50,
        'minimum_confidence' => env('REQUIRED_CONFIDENCE', 70) / 100,
    ],

    'risk_management' => [
        'max_risk_percent' => env('MAX_RISK_PERCENT', 2),
        'max_trades_per_hour' => 10,
        'stop_loss_percent' => 2,
        'take_profit_percent' => 4,
    ],
];
