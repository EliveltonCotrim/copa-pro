<?php

namespace App\Enum;

use App\Models\RegistrationPlayer;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum RegistrationPlayerStatusEnum: int implements HasLabel, HasColor, HasIcon
{
    case REGISTERED = 1;
    case PENDING = 2;
    case APPROVED = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::REGISTERED => 'INSCRITO',
            self::PENDING => 'PENDENTE',
            self::APPROVED => 'APROVADO',
            default => 'Status não encontrado',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::REGISTERED => 'primary',
            self::PENDING => 'danger',
            self::APPROVED => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::REGISTERED => 'heroicon-m-check',
            self::PENDING => 'heroicon-m-clock',
            self::APPROVED => 'heroicon-m-check',
        };
    }

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, RegistrationPlayerStatusEnum::cases());
    }

}
