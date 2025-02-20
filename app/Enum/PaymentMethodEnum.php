<?php

namespace App\Enum;

enum PaymentMethodEnum: string
{
    case UNDEFINED = 'UNDEFINED';
    case BOLETO = 'BOLETO';
    case CREDIT_CARD = 'CREDIT_CARD';
    case PIX = 'PIX';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::UNDEFINED => 'Indefinido',
            self::BOLETO => 'Boleto',
            self::CREDIT_CARD => 'Cartão de Crédito',
            self::PIX => 'PIX',
            default => 'Método de pagamento não encontrado',
        };
    }

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, PaymentMethodEnum::cases());
    }
}
