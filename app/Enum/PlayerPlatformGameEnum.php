<?php

namespace App\Enum;

use Filament\Support\Contracts\HasLabel;

enum PlayerPlatformGameEnum: int implements HasLabel
{
    case PLAYSTATION = 1;
    case XBOX        = 2;
    case PC          = 3;
    case MOBILE      = 4;
    case ALL         = 5;
    case OTHER       = 6;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PLAYSTATION => 'Playstation',
            self::XBOX        => 'Xbox',
            self::PC          => 'PC',
            self::MOBILE      => 'Dispositivo Móvel',
            self::ALL         => 'Todas as plataformas',
            self::OTHER       => 'Outra',
            default           => 'Plataforma não encontrada',
        };
    }

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, PlayerPlatformGameEnum::cases());
    }

    public static function options()
    {
        return collect(PlayerPlatformGameEnum::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->getLabel()]);
    }

    public static function optionsArrayWithLabelAndValues(): array
    {
        return collect(PlayerPlatformGameEnum::cases())
            ->map(fn ($cass) => ['value' => $cass->value, 'label' => $cass->getLabel()])->toArray();
    }
}
