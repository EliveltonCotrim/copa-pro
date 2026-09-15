<?php

namespace App\Services\Internal\Payment;

use App\Enum\PaymentCheckoutProviderEnum;
use App\Enum\PaymentMethodEnum;
use App\Enum\PaymentStatusEnum;
use App\Services\PaymentGateway\PaymentGatewayFactory;
use Str;

class PaymentService
{
    public function __construct(
        protected PaymentGatewayFactory $factory
    ) {
    }

    public function processPixPayment(array $data, string $provider): array
    {
        $gateway = $this->factory->make($provider);

        $resolvedCustomer = $gateway->customer()->resolve($data, $data['customer'] ?? null);

        if (isset($resolvedCustomer['error']) && $resolvedCustomer['error'] === true) {
            return $resolvedCustomer;
        }

        $data['customer'] = $resolvedCustomer;

        $responsePayment = $gateway->payment()->create($data);

        return $responsePayment;
    }
}