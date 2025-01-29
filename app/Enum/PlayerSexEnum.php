<?php

namespace App\Enum;
use Filament\Support\Contracts\HasLabel;

enum PlayerSexEnum: int implements HasLabel
{
    case MALE = 1;
    case FEMALE = 2;
    case OTHER = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::MALE => 'Masculino',
            self::FEMALE => 'Feminino',
            self::OTHER => 'Outro',
            default => 'Sexo não encotrado',
        };
    }

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, PlayerSexEnum::cases());
    }
}
