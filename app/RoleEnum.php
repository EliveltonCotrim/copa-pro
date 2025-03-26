<?php

namespace App;

use Filament\Support\Contracts\HasLabel;

enum RoleEnum: string implements HasLabel
{
    case ADMIN = 'admin';
    case ORGANIZATION = 'organização';
    case PLAYER = 'jogador';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ADMIN => 'Administrador',
            self::ORGANIZATION => 'Organização',
            self::PLAYER => 'Jogador',
            default => 'Função não encontrada',
        };
    }
}
