<?php

use App\Http\Controllers\AsaasWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('asaas/webhook', AsaasWebhookController::class)
    ->middleware(
        'verify-asaas-webhook',
        'throttle:35,1'
    );
