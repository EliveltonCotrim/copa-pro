<?php

namespace App\Services\PaymentGateway;

use App\Enum\PaymentCheckoutProviderEnum;
use App\Services\PaymentGateway\Connectors\AsaasConnector;
use App\Services\PaymentGateway\Connectors\MercadoPagoConnector;
use InvalidArgumentException;

class PaymentGatewayFactory
{
    public function make(string $provider): Gateway
    {
        $connector = match ($provider) {
            PaymentCheckoutProviderEnum::ASAAS->value => app(AsaasConnector::class),
            PaymentCheckoutProviderEnum::MP->value => app(MercadoPagoConnector::class),
            default => throw new InvalidArgumentException("Provedor de pagamento [{$provider}] não suportado."),
        };

        return new Gateway($connector);
    }
}
