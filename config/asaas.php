<?php

$version = env('ASAAS_API_VERSION');

return [
    'sandbox' => [
        'url' => env('ASAAS_SANDBOX_URL') . '/' . $version,
        'token' => env('ASAAS_SANDBOX_TOKEN'),
        'webhook_token' => env('ASAAS_SANDBOX_WEBHOOK_TOKEN'),
    ],
    'production' => [
        'url' => env('ASAAS_PRODUCTION_URL') . '/' . $version,
        'token' => env('ASAAS_PRODUCTION_TOKEN'),
        'webhook_token' => env('ASAAS_PRODUCTION_WEBHOOK_TOKEN'),
    ],
];
