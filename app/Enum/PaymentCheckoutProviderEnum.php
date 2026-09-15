<?php

namespace App\Enum;

use Filament\Support\Contracts\HasLabel;

enum PaymentCheckoutProviderEnum: string implements HasLabel
{
    case MP = 'MERCADO_PAGO';
    case ASAAS = 'ASAAS';
    case NONE = 'NONE';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::MP => 'Mercado Pago',
            self::ASAAS => 'Asaas',
            self::NONE => 'Nenhum',
            default => 'Checkout provider nao encontrado',
        };
    }

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, PaymentCheckoutProviderEnum::cases());
    }
}
