<?php

namespace App\Enum;

use Filament\Support\Contracts\{HasColor, HasIcon, HasLabel};

enum RegistrationPlayerStatusEnum: int implements HasColor, HasIcon, HasLabel
{
    case REGISTERED = 1;
    case PENDING    = 2;
    case APPROVED   = 3;
    case REJECTED   = 4;

    public function getLabel(): string
    {
        return match ($this) {
            self::REGISTERED => 'INSCRITO',
            self::PENDING    => 'PENDENTE',
            self::APPROVED   => 'APROVADO',
            self::REJECTED   => 'REJEITADO',
            default          => 'Status não encontrado',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::REGISTERED => 'primary',
            self::PENDING    => 'danger',
            self::APPROVED   => 'success',
            self::REJECTED   => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::REGISTERED => 'heroicon-m-check',
            self::PENDING    => 'heroicon-m-clock',
            self::APPROVED   => 'heroicon-m-check',
            self::REJECTED   => 'heroicon-m-x-mark',
        };
    }

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, RegistrationPlayerStatusEnum::cases());
    }
}
