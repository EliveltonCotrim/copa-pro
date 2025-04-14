<?php

declare(strict_types = 1);

namespace App\Services\PaymentGateway\Connectors;

use App\Services\PaymentGateway\Connectors\Asaas\Concerns\{AsaasConfig, HandleHttpError};
use App\Services\PaymentGateway\Contracts\AdapterInterface;
use Illuminate\Http\Client\RequestException;

class AsaasConnector implements AdapterInterface
{
    use AsaasConfig;
    use HandleHttpError;

    public function get(string $url): array
    {
        $request = $this->http->get($url);

        try {
            return $request
                ->throw()
                ->json();
        } catch (RequestException $exception) {
            return $this->handle($exception);
        }
    }

    public function post(string $url, array $params): array
    {

        $request = $this->http->post($url, $params);

        try {
            return $request
                ->throw()
                ->json();
        } catch (RequestException $exception) {
            return $this->handle($exception);
        }
    }

    public function delete(string $url): array
    {
        $request = $this->http->delete($url);

        try {
            return $request
                ->throw()
                ->json();
        } catch (RequestException $exception) {
            return $this->handle($exception);
        }
    }

    public function put(string $url, array $params): array
    {
        $request = $this->http->put($url, $params);

        try {
            return $request
                ->throw()
                ->json();
        } catch (RequestException $exception) {
            return $this->handle($exception);
        }
    }
}
