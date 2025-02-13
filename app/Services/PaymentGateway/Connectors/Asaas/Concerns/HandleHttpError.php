<?php

namespace App\Services\PaymentGateway\Connectors\Asaas\Concerns;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;

trait HandleHttpError
{
    protected function handle(RequestException $requestException): array
    {
        return [
            'error' => true,
            'status' => $requestException->getCode(),
            'message' => $requestException->getMessage(),
            'response' => $requestException?->response?->reason() ?? null,
        ];
    }
}
