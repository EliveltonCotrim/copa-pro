<?php

namespace App\Enum;

enum PlayerStatusEnum: int
{
    case REGISTERED = 1;
    case PENDING = 2;
    case APPROVED = 3;

    public function getName(): string
    {
        return match ($this) {
            self::REGISTERED => 'INSCRITO',
            self::PENDING => 'PENDENTE',
            self::APPROVED => 'APROVADO',
            default => 'Status não encotrado',
        };
    }
}
