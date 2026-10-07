<?php

namespace App\Services\PaymentGateway\Connectors\MercadoPago;

use App\Services\PaymentGateway\Connectors\Asaas\Concerns\HasFilter;
use App\Services\PaymentGateway\Contracts\CustomerInterface;
use Str;

class CustomerMp implements CustomerInterface
{
    use HasFilter;

    public function show(int|string $id): array
    {
        return [];
    }

    public function create(array $data): array
    {
        return [];
    }

    public function list(array $filters = []): array
    {
        return [];
    }

    public function update(int|string $id, array $data): array
    {
        return [];
    }

    public function delete(int|string $id): array
    {
        return [];
    }

    public function restore(int|string $id): array
    {
        return [];
    }

    public function resolve(array $customerData, ?string $gatewayId = null): string|array
    {
        $docType = strlen($customerData['cpfCnpj']) > 11 ? 'CNPJ' : 'CPF';

        // Retorna apenas o array formatado
        return [
            'email' => $customerData['customerEmail'],
            'first_name' => Str::before($customerData['customerName'], ' '),
            'last_name' => Str::after($customerData['customerName'], ' '),
            'identification' => [
                'type' => $docType,
                'number' => $customerData['cpfCnpj']
            ]
        ];
    }
}
