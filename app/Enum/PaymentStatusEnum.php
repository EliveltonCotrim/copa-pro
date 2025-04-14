<?php

namespace App\Enum;

use Filament\Support\Contracts\{HasColor, HasIcon, HasLabel};

enum PaymentStatusEnum: int implements HasColor, HasIcon, HasLabel
{
    case PENDING                      = 1;
    case RECEIVED                     = 2;
    case CONFIRMED                    = 3;
    case OVERDUE                      = 4;
    case REFUNDED                     = 5;
    case RECEIVED_IN_CASH             = 6;
    case REFUND_REQUESTED             = 7;
    case REFUND_IN_PROGRESS           = 8;
    case CHARGEBACK_REQUESTED         = 9;
    case CHARGEBACK_DISPUTE           = 10;
    case AWAITING_CHARGEBACK_REVERSAL = 11;
    case DUNNING_REQUESTED            = 12;
    case DUNNING_RECEIVED             = 13;
    case AWAITING_RISK_ANALYSIS       = 14;
    case PAYMENT_CREATED              = 15;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PENDING                      => 'Pendente',
            self::RECEIVED                     => 'Recebido',
            self::CONFIRMED                    => 'Confirmado',
            self::OVERDUE                      => 'Vencido',
            self::REFUNDED                     => 'Reembolsado',
            self::RECEIVED_IN_CASH             => 'Recebido em dinheiro',
            self::REFUND_REQUESTED             => 'Reembolso solicitado',
            self::REFUND_IN_PROGRESS           => 'Reembolso em andamento',
            self::CHARGEBACK_REQUESTED         => 'Reembolso solicitado',
            self::CHARGEBACK_DISPUTE           => 'Reembolso em disputa',
            self::AWAITING_CHARGEBACK_REVERSAL => 'Aguardando reversão de reembolso',
            self::DUNNING_REQUESTED            => 'Dunning solicitado',
            self::DUNNING_RECEIVED             => 'Dunning recebido',
            self::AWAITING_RISK_ANALYSIS       => 'Aguardando análise de risco',
            self::PAYMENT_CREATED              => 'Cobrança criada',
            default                            => 'Status não encontrado'
        };
    }

    public static function parse(string $status)
    {
        switch ($status) {
            case 'PENDING':
                return self::PENDING;
            case 'RECEIVED':
                return self::RECEIVED;
            case 'CONFIRMED':
                return self::CONFIRMED;
            case 'OVERDUE':
                return self::OVERDUE;
            case 'REFUNDED':
                return self::REFUNDED;
            case 'RECEIVED_IN_CASH':
                return self::RECEIVED_IN_CASH;
            case 'REFUND_REQUESTED':
                return self::REFUND_REQUESTED;
            case 'REFUND_IN_PROGRESS':
                return self::REFUND_IN_PROGRESS;
            case 'CHARGEBACK_REQUESTED':
                return self::CHARGEBACK_REQUESTED;
            case 'CHARGEBACK_DISPUTE':
                return self::CHARGEBACK_DISPUTE;
            case 'AWAITING_CHARGEBACK_REVERSAL':
                return self::AWAITING_CHARGEBACK_REVERSAL;
            case 'DUNNING_REQUESTED':
                return self::DUNNING_REQUESTED;
            case 'DUNNING_RECEIVED':
                return self::DUNNING_RECEIVED;
            case 'AWAITING_RISK_ANALYSIS':
                return self::AWAITING_RISK_ANALYSIS;
            case 'PAYMENT_CREATED':
                return self::PAYMENT_CREATED;
            default:
                return null;
        }
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PENDING                      => 'warning',
            self::RECEIVED                     => 'success',
            self::CONFIRMED                    => 'success',
            self::OVERDUE                      => 'danger',
            self::REFUNDED                     => 'success',
            self::RECEIVED_IN_CASH             => 'success',
            self::REFUND_REQUESTED             => 'warning',
            self::REFUND_IN_PROGRESS           => 'warning',
            self::CHARGEBACK_REQUESTED         => 'warning',
            self::CHARGEBACK_DISPUTE           => 'warning',
            self::AWAITING_CHARGEBACK_REVERSAL => 'warning',
            self::DUNNING_REQUESTED            => 'warning',
            self::DUNNING_RECEIVED             => 'warning',
            self::AWAITING_RISK_ANALYSIS       => 'warning',
            self::PAYMENT_CREATED              => 'warning',
            default                            => 'Color não encontrado'
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::PENDING                      => 'heroicon-m-clock',
            self::RECEIVED                     => 'heroicon-m-check',
            self::CONFIRMED                    => 'heroicon-m-check',
            self::OVERDUE                      => 'heroicon-m-x-mark',
            self::REFUNDED                     => 'heroicon-m-check',
            self::RECEIVED_IN_CASH             => 'heroicon-m-check',
            self::REFUND_REQUESTED             => 'heroicon-m-clock',
            self::REFUND_IN_PROGRESS           => 'heroicon-m-clock',
            self::CHARGEBACK_REQUESTED         => 'heroicon-m-clock',
            self::CHARGEBACK_DISPUTE           => 'heroicon-m-clock',
            self::AWAITING_CHARGEBACK_REVERSAL => 'heroicon-m-clock',
            self::DUNNING_REQUESTED            => 'heroicon-m-check',
            self::DUNNING_RECEIVED             => 'heroicon-m-check',
            self::AWAITING_RISK_ANALYSIS       => 'heroicon-m-clock',
            self::PAYMENT_CREATED              => 'heroicon-m-check',
            default                            => 'Icon não encontrado'
        };
    }

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, PaymentStatusEnum::cases());
    }
}
