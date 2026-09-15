<?php

namespace App\Services\PaymentGateway\Connectors\Asaas;

use App\Enum\PaymentCheckoutProviderEnum;
use App\Enum\PaymentMethodEnum;
use App\Enum\PaymentStatusEnum;
use App\Services\PaymentGateway\Connectors\Asaas\Concerns\HasFilter;
use App\Services\PaymentGateway\Connectors\Asaas\Concerns\InteractsWithGatewayResponses;
use App\Services\PaymentGateway\Contracts\{AdapterInterface, PaymentInterface};
use Illuminate\Support\Str;

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
        // Criar o pagamento
        $responsePayment = $this->http->post('/payments', $data);

        // Gerar QR Code Pix
        $responseQrCodepix = $this->getPixQrCode($responsePayment['id']);

        $responsePayment['pixQrCode'] = $responseQrCodepix;

        return [
            'transaction_id' => $responsePayment['id'],
            'description' => $data['description'],
            'value' => $responsePayment['value'],
            'taxes_amount' => $responsePayment['discount']['value'],
            'net_value' => $responsePayment['netValue'],
            'checkout_provider' => PaymentCheckoutProviderEnum::ASAAS->value,
            'date_created' => $responsePayment['dateCreated'],
            'due_date' => $responsePayment['dueDate'],
            'status' => PaymentStatusEnum::parse(Str::upper($responsePayment['status'])),
            'billing_type' => PaymentMethodEnum::PIX->value,
            'qr_code' => $responseQrCodepix['payload'],
            'qr_code_64' => $responseQrCodepix['encodedImage'],
            'expiration_date' => $responseQrCodepix['expirationDate'],
            'ticket_url' => null,
        ];
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
