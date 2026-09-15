<?php

namespace App\Services\PaymentGateway\Connectors\MercadoPago;

use App\Enum\PaymentCheckoutProviderEnum;
use App\Enum\PaymentMethodEnum;
use App\Enum\PaymentStatusEnum;
use App\Services\PaymentGateway\Connectors\Concerns\HandleHttpError;
use App\Services\PaymentGateway\Connectors\MercadoPago\Concerns\HasFilter;
use App\Services\PaymentGateway\Contracts\{PaymentInterface};
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use MercadoPago\Client\Payment\PaymentClient;

class PaymentMp implements PaymentInterface
{
    use HasFilter, HandleHttpError;
    private PaymentClient $client;

    public function __construct(
    ) {
        $this->client = new PaymentClient();
    }

    public function list(array $filters = []): array
    {
        return [];
    }

    public function get(int|string $id): array
    {
        return [];
    }

    public function create(array $data): array
    {
        // 1. Limpa a formatação do documento e define se é CPF ou CNPJ
        $docType = strlen($data['cpfCnpj']) === 14 ? 'CNPJ' : 'CPF';

        // 2. Formata a data de vencimento para o padrão ISO 8601 exigido pelo MP
        // Adiciona o horário de 23:59:59 e o fuso horário de Brasília (-03:00)
        $expirationDate = $data['dueDate'] . 'T23:59:59.000-03:00';

        // 3. Constrói o array no formato que o SDK do Mercado Pago espera
        $mpPayload = [
            'transaction_amount' => (float) $data['value'],
            'payment_method_id' => 'pix', // Força 'pix' independente do case recebido
            'description' => $data['description'],
            'date_of_expiration' => $expirationDate,
            'payer' => [
                'email' => $data['customerEmail'],
                'first_name' => Str::before($data['customerName'], ' '),
                'last_name' => Str::after($data['customerName'], ' '),
                'identification' => [
                    'type' => $docType,
                    'number' => $data['cpfCnpj']
                ]
            ]
        ];

        try {
            $payment = $this->client->create($mpPayload);

            // Retorna mapeado para o padrão do seu sistema
            return [
                'transaction_id' => $payment->id,
                'description' => $data['description'],
                'value' => $payment->transaction_amount,
                'taxes_amount' => collect($payment->fee_details ?? [])->firstWhere('type', 'mercadopago_fee')?->amount ?? 0,
                'net_value' => $payment->transaction_details?->net_received_amount ?? 0,
                'checkout_provider' => PaymentCheckoutProviderEnum::MP->value,
                'date_created' => Carbon::parse($payment->date_created)->toDateTimeString(),
                'due_date' => Carbon::parse($payment->date_of_expiration)->toDateTimeString(),
                'status' => PaymentStatusEnum::parse(Str::upper($payment->status)),
                'billing_type' => PaymentMethodEnum::PIX->value,
                'qr_code' => $payment->point_of_interaction?->transaction_data?->qr_code ?? null,
                'qr_code_64' => $payment->point_of_interaction?->transaction_data?->qr_code_base64 ?? null,
                'ticket_url' => $payment->point_of_interaction?->transaction_data?->ticket_url ?? null,
            ];

        } catch (\MercadoPago\Exceptions\MPApiException $exception) {
            return $this->handle($exception);
        }
    }

    public function update(int|string $id, array $data): array
    {
        return [];
    }

    public function getPaymentStatus(int|string $id): array
    {
        return [];
    }

    public function getPixQrCode(int|string $id): array
    {
        return [];
    }

    public function delete(int|string $id): array
    {
        return [];
    }
}
