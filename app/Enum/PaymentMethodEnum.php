<?php

namespace App\Enum;

use Filament\Support\Contracts\HasLabel;

enum PaymentMethodEnum: string implements HasLabel
{
    case UNDEFINED = 'UNDEFINED';
    case BOLETO = 'BOLETO';
    case CREDIT_CARD = 'CREDIT_CARD';
    case PIX = 'PIX';
    case IN_CASH = 'IN_CASH';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::UNDEFINED => 'Indefinido',
            self::BOLETO => 'Boleto',
            self::CREDIT_CARD => 'Cartão de Crédito',
            self::PIX => 'PIX',
            self::IN_CASH => 'Em dinheiro',
            default => 'Método de pagamento não encontrado',
        };
    }

    // public static function parse(int $method)
    // {
    //     switch ($method) {
    //         case 'PENDING':
    //             return self::PENDING;
    //         case 'RECEIVED':
    //             return self::RECEIVED;
    //         default:
    //             return null;
    //     }
    // }

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, PaymentMethodEnum::cases());
    }
}
