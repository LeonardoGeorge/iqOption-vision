<?php

return [
    'api' => [
        'base_url' => env('IQOPTION_API_URL', 'https://api.iqoption.com/api/'),
        'timeout' => env('IQOPTION_API_TIMEOUT', 30),
    ],

    'trading' => [
        'demo_mode' => env('IQOPTION_DEMO_MODE', true),
        'max_risk_percent' => env('MAX_RISK_PERCENT', 2),
        'default_timeframe' => env('DEFAULT_TIMEFRAME', '5m'),
    ],

    'analysis' => [
        'rsi_period' => 14,
        'sma_period_short' => 20,
        'sma_period_long' => 50,
        'minimum_confidence' => 0.7,
    ],

    'notifications' => [
        'enable_email' => env('ENABLE_EMAIL_NOTIFICATIONS', false),
        'enable_push' => env('ENABLE_PUSH_NOTIFICATIONS', true),
    ],
];
