<?php

namespace App\Enum;

use Filament\Support\Contracts\HasLabel;

enum PlayerPlatformGameEnum: int implements HasLabel
{
    case PLAYSTATION = 1;
    case XBOX = 2;
    case PC = 3;
    case MOBILE = 4;
    case ALL = 5;
    case OTHER = 6;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PLAYSTATION => 'Playstation',
            self::XBOX => 'Xbox',
            self::PC => 'PC',
            self::MOBILE => 'Dispositivo Móvel',
            self::ALL => 'Todas as plataformas',
            self::OTHER => 'Outra',
            default => 'Plataforma não encotrada',
        };
    }

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, PlayerPlatformGameEnum::cases());
    }
}
