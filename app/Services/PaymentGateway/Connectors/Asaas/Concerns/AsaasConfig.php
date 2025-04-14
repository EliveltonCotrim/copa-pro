<?php

namespace App\Services\PaymentGateway\Connectors\Asaas\Concerns;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

trait AsaasConfig
{
    public function __construct(
        protected ?PendingRequest $http = null,
    ) {
        $enviroment = app()->isLocal() ? 'sandbox' : 'production';
        $token      = config("asaas.{$enviroment}.token");
        $baseUrl    = config("asaas.{$enviroment}.url");

        $this->http = Http::withHeader('access_token', $token)->baseUrl($baseUrl);
    }
}
