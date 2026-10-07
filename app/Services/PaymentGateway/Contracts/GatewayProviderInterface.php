<?php

namespace App\Services\PaymentGateway\Contracts;

interface GatewayProviderInterface
{
    public function customer(): CustomerInterface;
    public function payment(): PaymentInterface;
}
