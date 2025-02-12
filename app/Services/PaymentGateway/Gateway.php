<?php

namespace App\Services\PaymentGateway;

use App\Services\PaymentGateway\Connectors\Asaas\Customer;
use App\Services\PaymentGateway\Contracts\AdapterInterface;

class Gateway
{
    public function __construct(
        public AdapterInterface $adapter,
    ) {
    }

    public function customer(): Customer
    {
        return new Customer($this->adapter);
    }
}
