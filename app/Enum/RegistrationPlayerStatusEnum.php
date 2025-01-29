<?php

namespace App\Enum;

use App\Models\RegistrationPlayer;
use Filament\Support\Contracts\HasLabel;

enum RegistrationPlayerStatusEnum: int implements HasLabel
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
            default => 'Status não encotrado',
        };
    }

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, RegistrationPlayerStatusEnum::cases());
    }

}
