<?php

declare(strict_types=1);

namespace App\Services\PaymentGateway\Connectors;

use App\Services\PaymentGateway\Connectors\Asaas\Concerns\AsaasConfig;
use App\Services\PaymentGateway\Connectors\Asaas\Concerns\HandleHttpError;
use App\Services\PaymentGateway\Contracts\AdapterInterface;
use Http;
use Illuminate\Http\Client\RequestException;

class AsaasConnector implements AdapterInterface
{
    use AsaasConfig, HandleHttpError;

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

    public function post(string $url, array $params)
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

    public function delete(string $url)
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

    public function put(string $url, array $params)
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
