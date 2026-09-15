<?php

namespace App\Services\PaymentGateway\Connectors;

use App\Services\PaymentGateway\Connectors\Concerns\HandleHttpError;
use App\Services\PaymentGateway\Connectors\MercadoPago\Concerns\MercadoPagoConfig;
use App\Services\PaymentGateway\Connectors\MercadoPago\CustomerMp;
use App\Services\PaymentGateway\Connectors\MercadoPago\PaymentMp;
use App\Services\PaymentGateway\Contracts\CustomerInterface;
use App\Services\PaymentGateway\Contracts\GatewayProviderInterface;
use App\Services\PaymentGateway\Contracts\PaymentInterface;
use MercadoPago\Client\Customer\CustomerClient;
use MercadoPago\Client\Payment\PaymentClient;

class MercadoPagoConnector implements GatewayProviderInterface
{
    use MercadoPagoConfig;
    use HandleHttpError;

    protected PaymentClient $paymentClient;
    protected CustomerClient $customerClient;

    public function __construct()
    {
        $this->bootMercadoPagoConfig();
    }

    public function customer(): CustomerInterface
    {
        // O SDK já gerencia a rede, então não precisamos injetar um AdapterInterface aqui
        return new CustomerMp();
    }

    public function payment(): PaymentInterface
    {
        return new PaymentMp();
    }

}
