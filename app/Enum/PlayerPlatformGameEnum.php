<?php

namespace App\Enum;

enum PlayerPlatformGameEnum: int
{
    case PLAYSTATION = 1;
    case XBOX = 3;
    case PC = 2;
    case MOBILE = 3;

    public function getName()
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
