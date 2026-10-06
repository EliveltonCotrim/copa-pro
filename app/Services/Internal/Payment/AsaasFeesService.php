<?php

namespace App\Services\Internal\Payment;

use App\Enum\PaymentCheckoutProviderEnum;
use App\Services\PaymentGateway\PaymentGatewayFactory;
use Illuminate\Support\Facades\Cache;


class AsaasFeesService
{
    public const CACHE_KEY = 'asaas.account_fees'; // vao ser duas chaves diferentes Asaas e MP

    public function __construct(
        private PaymentGatewayFactory $factory, // ajuste para o nome real da sua factory
    ) {
    }

    public function get(): ?array
    {
        return Cache::remember(self::CACHE_KEY, now()->addHour(), function () {
            try {
                $response = $this->factory
                    ->make(PaymentCheckoutProviderEnum::ASAAS->value)
                    ->payment()
                    ->fees();

                // Formato de erro da API: {"errors": [{"code", "description"}]}
                return isset($response['errors']) ? null : $response;
            } catch (\Throwable $e) {
                report($e);

                return null;
            }
        });
    }

    public function refresh(): ?array
    {
        Cache::forget(self::CACHE_KEY);

        return $this->get();
    }
}
