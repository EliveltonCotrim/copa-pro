<?php

namespace App\Enum;
use Filament\Support\Contracts\HasLabel;

enum PlayerExperienceLevelEnum: int implements HasLabel
{
    case BEGINNER = 1;
    case AMATEUR = 2;
    case NORMAL = 3;
    case PROFESSIONAL = 4;
    case STAR = 5;
    case SUPERSTAR = 6;
    case LEGEND = 7;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::BEGINNER => 'Iniciante',
            self::AMATEUR => 'Amador',
            self::NORMAL => 'Normal',
            self::PROFESSIONAL => 'Profissional',
            self::STAR => 'Craque',
            self::SUPERSTAR => 'Superastro',
            self::LEGEND => 'Lenda',
            default => 'Nível não encotrado',
        };
    }

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, PlayerExperienceLevelEnum::cases());
    }

    public static function options()
    {
        return collect(PlayerExperienceLevelEnum::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->getLabel()]);
    }

    public static function optionsArrayWithLabelAndValues(): array
    {
        return collect(PlayerExperienceLevelEnum::cases())
            ->map(fn($cass) => ['value' => $cass->value, 'label' => $cass->getLabel()])->toArray();
    }
}
