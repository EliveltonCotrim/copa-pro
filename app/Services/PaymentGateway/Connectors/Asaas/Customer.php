<?php

declare(strict_types=1);

namespace App\Services\PaymentGateway\Connectors\Asaas;

use App\Services\PaymentGateway\Connectors\Asaas\Concerns\HasFilter;
use App\Services\PaymentGateway\Contracts\{AdapterInterface, CustomerInterface};

class Customer implements CustomerInterface
{
    use HasFilter;

    public function __construct(
        public AdapterInterface $http,
    ) {
    }

    public function show(int|string $id): array
    {
        return $this->http->get((string) '/customers/' . $id);
    }

    public function create(array $data): array
    {
        return $this->http->post('/customers', $data);
    }

    public function list(array $filters = []): array
    {
        return $this->http->get((string) '/customers/' . $this->filter($filters));
    }

    public function update(int|string $id, array $data): array
    {
        return $this->http->put((string) '/customers/' . $id, $data);
    }

    public function delete(int|string $id): array
    {
        return $this->http->delete((string) '/customers/' . $id);
    }

    public function restore(int|string $id): array
    {
        return $this->http->post((string) '/customers/' . $id . '/restore', []);
    }

    public function resolve(array $customerData, ?string $gatewayId = null): string|array
    {
        if ($gatewayId) {
            $asaasCustomer = $this->show($gatewayId);

            if (isset($asaasCustomer['error']) && $asaasCustomer['error'] === true) {
                return $asaasCustomer;
            }

            // Se o cliente foi deletado no Asaas, criamos um novo
            if (isset($asaasCustomer['deleted']) && $asaasCustomer['deleted'] === true) {
                $newCustomer = $this->create($this->formatPayload($customerData));

                if (isset($newCustomer['error']) && $newCustomer['error'] === true) {
                    return $newCustomer;
                }

                return $newCustomer['id'];
            }

            // Se achou e está válido, retorna o próprio ID
            if (isset($asaasCustomer['id'])) {
                return $asaasCustomer['id'];
            }
        }

        // Se não veio ID nenhum (cliente novo), cria no Asaas
        $newCustomer = $this->create($this->formatPayload($customerData));

        if (isset($newCustomer['error']) && $newCustomer['error'] === true) {
            return $newCustomer;
        }

        return $newCustomer['id'];
    }

    private function formatPayload(array $data)
    {
        return [
            'name' => $data['customerName'],
            'email' => $data['customerEmail'],
            'cpfCnpj' => clear_string($data['cpfCnpj']),
            'phone' => $data['customerPhone'] ?? null,
        ];
    }
}
