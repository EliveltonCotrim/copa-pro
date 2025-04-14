<?php

namespace App\Enum;

use Filament\Support\Contracts\{HasColor, HasIcon, HasLabel};

enum ChampionshipStatusEnum: int implements HasColor, HasIcon, HasLabel
{
    case REGISTRATION_OPEN   = 1;   // Inscrições abertas
    case REGISTRATION_CLOSED = 2; // Inscrições encerradas
    case IN_PROGRESS         = 3;         // Campeonato em andamento
    case FINISHED            = 4;            // Campeonato finalizado
    case CANCELED            = 5;            // Campeonato cancelado
    case ON_HOLD             = 6;             // Campeonato pausado
    case INACTIVE            = 7;            // Campeonato inativo

    public function getLabel(): ?string
    {
        return match ($this) {
            self::REGISTRATION_OPEN   => 'Inscrições abertas',
            self::REGISTRATION_CLOSED => 'Inscrições encerradas',
            self::IN_PROGRESS         => 'Em andamento',
            self::FINISHED            => 'Finalizado',
            self::CANCELED            => 'Cancelado',
            self::ON_HOLD             => 'Pausado',
            self::INACTIVE            => 'Inativo',
            default                   => 'Status não encontrado',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::REGISTRATION_OPEN   => 'success',
            self::REGISTRATION_CLOSED => 'gray',
            self::IN_PROGRESS         => 'primary',
            self::FINISHED            => 'danger',
            self::CANCELED            => 'red',
            self::ON_HOLD             => 'yellow',
            self::INACTIVE            => 'warning',
            default                   => 'Color não encontrado',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::REGISTRATION_OPEN   => 'heroicon-m-document-text',
            self::REGISTRATION_CLOSED => 'heroicon-m-x-circle',
            self::IN_PROGRESS         => 'heroicon-m-clock',
            self::FINISHED            => 'heroicon-m-flag',
            self::CANCELED            => 'heroicon-m-trash',
            self::ON_HOLD             => 'heroicon-m-pause',
            self::INACTIVE            => 'heroicon-m-x-circle',
            default                   => 'Icon não encontrado',
        };
    }

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, ChampionshipStatusEnum::cases());
    }
}
