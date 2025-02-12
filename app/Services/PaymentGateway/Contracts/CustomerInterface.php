<?php

namespace App\Services\PaymentGateway\Contracts;

interface CustomerInterface
{
    public function create(array $data): array;
    public function list(array $filters = []): array;
    public function show(int|string $id): array;
    public function update(int|string $id, array $data): array;
    public function delete(int|string $id): array;
}
