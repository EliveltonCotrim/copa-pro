<?php

namespace App\Services\PaymentGateway;

use App\Services\PaymentGateway\Connectors\Asaas\{Customer, Payment};
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

    public function payment(): Payment
    {
        return new Payment($this->adapter);
    }
}
