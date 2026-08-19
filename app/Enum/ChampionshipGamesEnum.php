<?php

namespace App\Enum;

use Filament\Support\Contracts\HasLabel;

enum ChampionshipGamesEnum: int implements HasLabel
{
    case EFOOTBALL = 1;
    case FIFA      = 2;
    case UFL       = 3;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::EFOOTBALL => 'eFootball',
            self::FIFA      => 'FIFA',
            self::UFL       => 'UFL',
            default         => 'Game não encontrado',
        };
    }

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, ChampionshipGamesEnum::cases());
    }
}
