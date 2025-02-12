<?php

namespace App\Contracts;

interface PaymentGatewayInterface
{
    /**
     * Metodo para gerar pagamento
     * @param array $data
     * @return array
     */
    public function process(array $data): array;
}
