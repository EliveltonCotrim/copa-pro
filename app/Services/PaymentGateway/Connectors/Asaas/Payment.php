<?php

namespace App\Services\PaymentGateway\Connectors\Asaas;

use App\Services\PaymentGateway\Connectors\Asaas\Concerns\HasFilter;
use App\Services\PaymentGateway\Contracts\{AdapterInterface, PaymentInterface};

class Payment implements PaymentInterface
{
    use HasFilter;

    public function __construct(
        public AdapterInterface $http,
    ) {
    }

    public function list(array $filters = []): array
    {
        return $this->http->get((string) '/payments/' . $this->filter($filters));
    }

    public function get(int|string $id): array
    {
        return $this->http->get("/payments/$id");
    }

    public function create(array $data): array
    {
        return $this->http->post('/payments', $data);
    }

    public function update(int|string $id, array $data): array
    {
        return $this->http->put("/payments/{$id}", $data);
    }

    public function getPaymentStatus(int|string $id): array
    {
        return $this->http->get("/payments/{$id}/status");
    }

    public function getPixQrCode(int|string $id): array
    {
        return $this->http->get("/payments/{$id}/pixQrCode");
    }

    public function delete(int|string $id): array
    {
        return $this->http->delete("/payments/{$id}");
    }
}
