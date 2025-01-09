<?php

namespace App\Enum;

use Filament\Support\Contracts\HasLabel;

enum ChampionshipGamesEnum: int implements HasLabel
{
    case EFOOTBALL = 1;
    case FIFA = 2;
    case UFL = 3;

    public function getLabel(): ?string
    {
        return match ($this){
            self::EFOOTBALL => 'EFOOTBALL',
            self::FIFA => 'FIFA',
            self::UFL => 'UFL',
            default => 'Game não encotrado',
        };
    }
}
