<?php

namespace App\Services\Internal\Payment;

use App\Models\PaymentGatewaySetting;
use App\Services\PaymentGateway\PaymentGatewayFactory;
use Str;

class PaymentService
{
    public function __construct(
        protected PaymentGatewayFactory $factory
    ) {
    }

    public function processPixPayment(array $data): array
    {
        $provider = PaymentGatewaySetting::currentProvider();
        $gateway = $this->factory->make($provider);

        // trata o cliente, caso seja necessário
        $resolvedCustomer = $gateway->customer()->resolve($data, $data['customer'] ?? null);

        if (isset($resolvedCustomer['error']) && $resolvedCustomer['error'] === true) {
            return $resolvedCustomer;
        }

        $data['customer'] = $resolvedCustomer;

        $responsePayment = $gateway->payment()->create($data);

        return $responsePayment;
    }
}