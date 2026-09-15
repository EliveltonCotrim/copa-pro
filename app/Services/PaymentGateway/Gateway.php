<?php

namespace App\Services\PaymentGateway;

use App\Services\PaymentGateway\Connectors\Asaas\{Customer, Payment};
use App\Services\PaymentGateway\Contracts\AdapterInterface;
use App\Services\PaymentGateway\Contracts\CustomerInterface;
use App\Services\PaymentGateway\Contracts\GatewayProviderInterface;
use App\Services\PaymentGateway\Contracts\PaymentInterface;

class Gateway
{
    public function __construct(
        public GatewayProviderInterface $provider,
    ) {
    }

    public function customer(): CustomerInterface
    {
        return $this->provider->customer();
    }

    public function payment(): PaymentInterface
    {
        return $this->provider->payment();
    }
}
