<?php

namespace App\Services\PaymentGateway\Connectors\MercadoPago\Concerns;

use MercadoPago\MercadoPagoConfig as MercadoPagoConfigSdk;

trait MercadoPagoConfig
{
    public function bootMercadoPagoConfig()
    {
        MercadoPagoConfigSdk::setAccessToken(config('mercado-pago.token'));

        if (app()->isLocal()) {
            MercadoPagoConfigSdk::setRuntimeEnviroment(MercadoPagoConfigSdk::LOCAL);
        }
    }
}
