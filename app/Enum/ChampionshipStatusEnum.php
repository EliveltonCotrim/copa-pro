<?php

namespace App\Enum;

enum ChampionshipStatusEnum: int
{
    case ACTIVE = 1;
    case INACTIVE = 2;
    case FINISHED = 3;

    public function getName(): string
    {
        return match ($this) {
            self::ACTIVE => 'active',
            self::INACTIVE => 'inactive',
            self::FINISHED => 'finished',
            default => 'Status não encotrado',

        };
    }
}
