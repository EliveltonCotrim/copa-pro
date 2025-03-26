<?php

namespace App\Enum;

use Filament\Support\Contracts\HasLabel;

enum ChampionshipFormatEnum: int implements HasLabel
{
    case LEAGUE = 1;
    case KNOCKOUT = 2;
    case CUP = 3;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::LEAGUE => 'Liga',
            self::KNOCKOUT => 'Mata-mata',
            self::CUP => 'Copa',
            default => 'Formato não encontrado',
        };
    }

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, ChampionshipFormatEnum::cases());
    }
}
