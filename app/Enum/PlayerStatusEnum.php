<?php

namespace App\Enum;

use Filament\Support\Contracts\HasLabel;

enum PlayerStatusEnum: int implements HasLabel
{
    case ACTIVE   = 1;
    case BANNED   = 2;
    case INACTIVE = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::ACTIVE   => 'Ativo',
            self::BANNED   => 'Banido',
            self::INACTIVE => 'Inativo',
            default        => 'Status não encontrado',
        };
    }

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, PlayerSexEnum::cases());
    }
}
