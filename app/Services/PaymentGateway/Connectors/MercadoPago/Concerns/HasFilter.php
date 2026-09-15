<?php

namespace App\Services\PaymentGateway\Connectors\MercadoPago\Concerns;

trait HasFilter
{
    public function filter(array $filters): string
    {
        return empty($filters) ? '' : '?' . http_build_query($filters);
    }
}
