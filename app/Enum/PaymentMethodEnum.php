<?php

namespace App\Enum;

use Filament\Support\Contracts\HasLabel;

enum PaymentMethodEnum: int implements HasLabel
{
    case CREADIT_CARD = 1;
    case PIX = 2;
    case BANK_SLIP = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::CREADIT_CARD => "Catão de Crédito",
            self::PIX => "PIX",
            self::BANK_SLIP => "Boleto Bancário",
            default => "Método de pagamento não encontrado",
        };
    }

    public static function parse($status)
    {
        switch ($status) {
            case 'credit_card':
                return self::CREADIT_CARD;
            case 'bank_transfer':
                return self::PIX;
            case 'ticket':
                return self::BANK_SLIP;
            default:
                return null;
        }
    }

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, PaymentMethodEnum::cases());
    }
}
