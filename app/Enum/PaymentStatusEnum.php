<?php

namespace App\Enum;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PaymentStatusEnum: int implements HasLabel, HasColor, HasIcon
{
    case PENDING = 1;
    case PAID = 2;
    case REJECTED = 3;
    case AUTHORIZED = 4;
    case IN_PROCESS = 5;
    case IN_MEDIATION = 6;
    case CHARGED_BACK = 7;
    case REFUNDED = 8;
    case CANCELLED = 9;


    public function getLabel(): ?string
    {
        return match ($this) {
            self::PENDING => 'Pendente',
            self::PAID => 'Pago',
            self::REJECTED => 'Rejeitado',
            self::AUTHORIZED => 'Autorizado',
            self::IN_PROCESS => 'Em processo',
            self::IN_MEDIATION => 'Em disputa',
            self::CHARGED_BACK => 'Charge Back',
            self::REFUNDED => 'Reembolsado',
            self::CANCELLED => 'Cancelado',
            default => 'Status não encontrado'
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::PAID => 'success',
            self::REJECTED => 'red',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this){
            self::PENDING => 'heroicon-m-clock',
            self::PAID => 'heroicon-m-check',
            self::REJECTED => 'heroicon-m-x',
        };
    }

}
