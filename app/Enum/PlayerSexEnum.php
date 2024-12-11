<?php

namespace App\Enum;

enum PlayerSexEnum: int
{
    case MALE = 1;
    case FEMALE = 2;
    case OTHER = 3;

    public function getName(): string
    {
        return match ($this) {
            self::MALE => 'Masculino',
            self::FEMALE => 'Feminino',
            self::OTHER => 'Outro',
            default => 'Sexo não encotrado',
        };
    }
}
