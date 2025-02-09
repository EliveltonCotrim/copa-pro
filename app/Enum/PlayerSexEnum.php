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
            default => 'Gênero não encontrado',
        };
    }

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, PlayerSexEnum::cases());
    }

    public static function options()
    {
        return collect(PlayerSexEnum::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->getLabel()]);
    }

    public static function optionsArrayWithLabelAndValues(): array
    {
        return collect(PlayerSexEnum::cases())
            ->map(fn($case) => ['value' => $case->value, 'label' => $case->getLabel()])->toArray();
    }
}
