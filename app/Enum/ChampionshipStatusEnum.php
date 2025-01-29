<?php

namespace App\Enum;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ChampionshipStatusEnum: int implements HasLabel, HasColor, HasIcon
{
    case ACTIVE = 1;
    case INACTIVE = 2;
    case FINISHED = 3;
    case IN_PROGRESS = 4;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ACTIVE => 'Ativo',
            self::INACTIVE => 'Inativo',
            self::FINISHED => 'Finalizado',
            self::IN_PROGRESS => 'Em andamento',
            default => 'Status não encotrado',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::INACTIVE => 'warning',
            self::FINISHED => 'danger',
            self::IN_PROGRESS => 'primary',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this){
            self::ACTIVE => 'heroicon-m-check-circle',
            self::INACTIVE => 'heroicon-m-x-circle',
            self::FINISHED => 'heroicon-m-flag',
            self::IN_PROGRESS => 'heroicon-m-clock',
        };
    }

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, ChampionshipStatusEnum::cases());
    }
}
