<?php

namespace App\Enum;

enum PaymentMethodEnum: int
{
    case CREADIT_CARD = 1;
    case PIX = 2;
    case BANK_SLIP = 3;

    public function getName(): string
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
}
