<?php

declare(strict_types=1);

namespace App\Services\PaymentGateway\Connectors\Asaas;

use App\Services\PaymentGateway\Connectors\Asaas\Concerns\HasFilter;
use App\Services\PaymentGateway\Contracts\AdapterInterface;
use App\Services\PaymentGateway\Contracts\CustomerInterface;

class Customer implements CustomerInterface
{
    use HasFilter;

    public function __construct(
        public AdapterInterface $http,
    ) {}

    public function show(int|string $id): array
    {
        return $this->http->get((string) '/customers/'.$id);
    }

    public function create(array $data): array
    {
        return $this->http->post('/customers', $data);
    }

    public function list(array $filters = []): array
    {
        return $this->http->get((string) '/customers/'.$this->filter($filters));
    }

    public function update(int|string $id, array $data): array
    {
        return $this->http->put((string) '/customers/'.$id, $data);
    }

    public function delete(int|string $id): array
    {
        return $this->http->delete((string) '/customers/'.$id);
    }

    public function restore(int|string $id): array
    {
        return $this->http->post((string) '/customers/'.$id.'/restore', []);
    }
}
