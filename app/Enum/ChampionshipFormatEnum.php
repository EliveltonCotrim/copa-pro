<?php

namespace App\Enum;

enum ChampionshipFormatEnum: int
{
    case LEAGUE = 1;
    case KNOCKOUT = 2;
    case CUP = 3;

    public function getName()
    {
        return match ($this){
            self::LEAGUE => 'Liga',
            self::KNOCKOUT => 'Mata-mata',
            self::CUP => 'Copa',
            default => 'Formato não encotrado',
        };
    }
}
