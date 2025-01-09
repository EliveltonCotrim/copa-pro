<?php

namespace App\Enum;

use Filament\Support\Contracts\HasLabel;

enum PlayerPlatformGameEnum: int implements HasLabel
{
    case PLAYSTATION = 1;
    case XBOX = 2;
    case PC = 3;
    case MOBILE = 4;

    public function getLabel(): ?string
    {
        return match ($this){
            self::PLAYSTATION => 'Playstation',
            self::XBOX => 'Xbox',
            self::PC => 'PC',
            self::MOBILE => 'Dispositivo Móvel',
            default => 'Plataforma não encotrada',
        };
    }
}
