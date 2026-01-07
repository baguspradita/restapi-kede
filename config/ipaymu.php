<?php

return [
    'va' => env('IPAYMU_VA', '0000009618506722'),
    'api_key' => env('IPAYMU_API_KEY', 'SANDBOXE77B33C8-B745-4BA1-961D-F334E4FFA726'),
    'sandbox' => env('IPAYMU_SANDBOX', true),
    'sandbox_base_url' => 'https://sandbox.ipaymu.com/api/v2/',
    'live_base_url' => 'https://my.ipaymu.com/api/v2/',
    'return_url' => env('IPAYMU_RETURN_URL', 'https://localhost:8000/payment/return'),
    'notify_url' => env('IPAYMU_NOTIFY_URL', 'https://localhost:8000/api/payments/webhook'),
    'cancel_url' => env('IPAYMU_CANCEL_URL', 'https://localhost:8000/payment/cancel'),
];
