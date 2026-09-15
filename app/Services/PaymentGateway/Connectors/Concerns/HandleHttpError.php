<?php

namespace App\Services\PaymentGateway\Connectors\Concerns;

use App\Exceptions\PaymentGatewayException;
use Exception;
use Illuminate\Http\Client\RequestException;
use MercadoPago\Exceptions\MPApiException;

trait HandleHttpError
{
    protected function handle(Exception $exception): array
    {
        // 1. Iniciamos garantindo que é uma string pura
        $errorMessage = 'Erro genérico de comunicação com o Gateway de Pagamento.';

        if ($exception instanceof RequestException) {

            $extracted = $exception->response->json('errors.0.description');
            if (!empty($extracted) && is_string($extracted)) {
                $errorMessage = $extracted;
            }

        } elseif ($exception instanceof MPApiException) {

            $response = $exception->getApiResponse();
            $content = $response ? $response->getContent() : [];

            // Pega a mensagem do MP. Se por um acaso bizarro ela for um array, transforma em JSON (string)
            $mpMessage = $content['message'] ?? $exception->getMessage();
            $errorMessage = is_array($mpMessage) ? json_encode($mpMessage) : (string) $mpMessage;

            // Concatena as causas (detalhes do erro)
            if (isset($content['cause']) && is_array($content['cause'])) {
                foreach ($content['cause'] as $cause) {
                    $description = $cause['description'] ?? '';
                    if (is_string($description) && !empty($description)) {
                        $errorMessage .= ' - Detalhe: ' . $description;
                    }
                }
            }

        } else {
            $errorMessage = $exception->getMessage();
        }

        // Blindagem final: se por algum motivo ainda for array, converte para string
        if (is_array($errorMessage)) {
            $errorMessage = json_encode($errorMessage);
        }

        // Agora temos 100% de certeza que é uma string!
        throw new PaymentGatewayException((string) $errorMessage);
    }
}
