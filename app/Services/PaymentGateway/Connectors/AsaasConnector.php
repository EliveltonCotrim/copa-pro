<?php

declare(strict_types=1);

namespace App\Services\PaymentGateway\Connectors;

use App\Services\PaymentGateway\Connectors\Asaas\Concerns\AsaasHttpAdapter;
use App\Services\PaymentGateway\Connectors\Asaas\Customer;
use App\Services\PaymentGateway\Connectors\Asaas\Payment;
use App\Services\PaymentGateway\Contracts\CustomerInterface;
use App\Services\PaymentGateway\Contracts\GatewayProviderInterface;
use App\Services\PaymentGateway\Contracts\PaymentInterface;

class AsaasConnector implements GatewayProviderInterface
{
    protected AsaasHttpAdapter $adapter;

    public function __construct()
    {
        $this->adapter = new AsaasHttpAdapter();
    }

    public function customer(): CustomerInterface
    {
        return new Customer($this->adapter);
    }

    public function payment(): PaymentInterface
    {
        return new Payment($this->adapter);
    }
}
