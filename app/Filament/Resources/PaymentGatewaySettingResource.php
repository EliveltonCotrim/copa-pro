<?php

namespace App\Filament\Resources;

use App\Enum\PaymentCheckoutProviderEnum;
use App\Filament\Resources\PaymentGatewaySettingResource\Pages;
use App\Models\PaymentGatewaySetting;
use App\Services\Internal\Payment\AsaasFeesService;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Infolists\Components\Section as InfolistSection;
use Illuminate\Support\Carbon;

class PaymentGatewaySettingResource extends Resource
{
    protected static ?string $model = PaymentGatewaySetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Configurações';
    protected static ?string $modelLabel = 'Gateway de pagamento';
    protected static ?string $pluralModelLabel = 'Gateway de pagamento';
    protected static ?string $navigationLabel = 'Gateway de pagamento';
    protected static ?int $navigationSort = 7;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function getNavigationUrl(): string
    {
        $record = PaymentGatewaySetting::query()->first();

        return $record
            ? static::getUrl('view', ['record' => $record])
            : static::getUrl('index');
    }


    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Gateway de pagamento')
                ->description('Define qual provedor é usado no checkout.')
                ->columns(2)
                ->schema([
                    Select::make('provider')
                        ->label('Provedor')
                        ->options(PaymentCheckoutProviderEnum::class)
                        ->native(false)
                        ->required()
                        ->columnSpanFull(),

                    // TextInput::make('public_key')
                    //     ->label('Public key')
                    //     ->password()
                    //     ->revealable()
                    //     ->required(),

                    // TextInput::make('access_token')
                    //     ->label('Access token')
                    //     ->password()
                    //     ->revealable()
                    //     ->required(),

                    // TextInput::make('webhook_token')
                    //     ->label('Webhook token')
                    //     ->password()
                    //     ->revealable()
                    //     ->columnSpanFull(),
                ]),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            InfolistSection::make('Gateway configurado')
                ->description('Provedor usado no checkout.')
                ->icon('heroicon-o-credit-card')
                // ->columns(2)
                ->schema([
                    Grid::make(2)->schema([
                        TextEntry::make('provider')
                            ->label('Provedor ativo')
                            ->badge()
                            ->size(TextEntry\TextEntrySize::Large),

                        TextEntry::make('updated_at')
                            ->label('Última atualização')
                            ->icon('heroicon-o-clock')
                            ->dateTime('d/m/Y H:i')
                            ->helperText(fn($record) => $record->updated_at?->diffForHumans()),
                    ]),

                    // TextEntry::make('public_key')
                    //     ->label('Public key')
                    //     ->formatStateUsing(fn(?string $state) => self::mask($state))
                    //     ->copyable(false),

                    // TextEntry::make('access_token')
                    //     ->label('Access token')
                    //     ->formatStateUsing(fn(?string $state) => self::mask($state)),

                    // TextEntry::make('webhook_token')
                    //     ->label('Webhook token')
                    //     ->formatStateUsing(fn(?string $state) => self::mask($state)),
                ]),
            InfolistSection::make('Taxas do Asaas')
                ->description('Condições da sua conta. Atualizadas a cada 1 hora.')
                ->icon('heroicon-o-receipt-percent')
                ->visible(fn($record) => $record->provider === PaymentCheckoutProviderEnum::ASAAS)
                ->schema([
                    Grid::make(['default' => 1, 'md' => 2])->schema([
                        TextEntry::make('fee_pix')
                            ->label('Pix')
                            ->icon('heroicon-o-qr-code')
                            ->state(fn() => self::pixLines())
                            ->listWithLineBreaks(),

                        TextEntry::make('fee_bank_slip')
                            ->label('Boleto')
                            ->icon('heroicon-o-document-text')
                            ->state(fn() => self::bankSlipLines())
                            ->listWithLineBreaks(),

                        TextEntry::make('fee_credit_card')
                            ->label('Cartão de crédito')
                            ->icon('heroicon-o-credit-card')
                            ->state(fn() => self::creditCardLines())
                            ->listWithLineBreaks(),

                        TextEntry::make('fee_debit_card')
                            ->label('Cartão de débito')
                            ->icon('heroicon-o-credit-card')
                            ->state(fn() => self::debitCardLines())
                            ->listWithLineBreaks(),
                    ]),

                    TextEntry::make('fees_error')
                        ->hiddenLabel()
                        ->state('Não foi possível carregar as taxas agora. Tente atualizar em instantes.')
                        ->color('danger')
                        ->icon('heroicon-o-exclamation-triangle')
                        ->visible(fn() => self::fees() === null),
                ]),
        ]);
    }

    private static function mask(?string $value): string
    {
        if (blank($value)) {
            return '—';
        }

        return '••••••••' . substr($value, -4);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentGatewaySettings::route('/'),
            'view' => Pages\ViewPaymentGatewaySetting::route('/{record}'),
            // 'edit' => Pages\EditPaymentGatewaySetting::route('/{record}/edit'),
        ];
    }


    // metodos auxiliares

    private static function fees(): ?array
    {
        return app(AsaasFeesService::class)->get();
    }

    private static function money(mixed $value): string
    {
        return filled($value) ? 'R$ ' . number_format((float) $value, 2, ',', '.') : '—';
    }

    private static function percent(mixed $value): string
    {
        return filled($value) ? number_format((float) $value, 2, ',', '.') . '%' : '—';
    }

    private static function days(mixed $days): string
    {
        return filled($days)
            ? 'Recebimento em ' . $days . ' ' . ($days == 1 ? 'dia' : 'dias')
            : '—';
    }

    private static function discountActive(?string $expiration): bool
    {
        return filled($expiration) && Carbon::parse($expiration)->isFuture();
    }

    private static function pixLines(): array
    {
        $pix = data_get(self::fees(), 'payment.pix');

        if (!$pix) {
            return ['—'];
        }

        if (($pix['type'] ?? null) === 'PERCENTAGE') {
            $lines = [self::percent($pix['percentageFee'] ?? null) . ' por transação'];

            if (filled($pix['minimumFeeValue'] ?? null)) {
                $lines[] = 'Mínimo: ' . self::money($pix['minimumFeeValue']);
            }
            if (filled($pix['maximumFeeValue'] ?? null)) {
                $lines[] = 'Máximo: ' . self::money($pix['maximumFeeValue']);
            }
        } else {
            $value = self::discountActive($pix['discountExpiration'] ?? null)
                ? $pix['fixedFeeValueWithDiscount']
                : $pix['fixedFeeValue'];

            $lines = [self::money($value) . ' por transação'];
        }

        if (filled($pix['monthlyCreditsWithoutFee'] ?? null)) {
            $lines[] = 'Isenção mensal: ' . $pix['monthlyCreditsWithoutFee']
                . ' · recebidas no mês: ' . ($pix['creditsReceivedOfCurrentMonth'] ?? 0);
        }

        return $lines;
    }

    private static function bankSlipLines(): array
    {
        $slip = data_get(self::fees(), 'payment.bankSlip');

        if (!$slip) {
            return ['—'];
        }

        $value = self::discountActive($slip['expirationDate'] ?? null)
            ? $slip['discountValue']
            : $slip['defaultValue'];

        return [
            self::money($value) . ' por boleto pago',
            self::days($slip['daysToReceive'] ?? null),
        ];
    }

    private static function creditCardLines(): array
    {
        $card = data_get(self::fees(), 'payment.creditCard');

        if (!$card) {
            return ['—'];
        }

        $d = ($card['hasValidDiscount'] ?? false) ? 'discount' : '';
        $key = fn(string $base) => $d
            ? $d . ucfirst($base)
            : $base;

        $op = ' + ' . self::money($card['operationValue'] ?? null);

        return [
            'À vista: ' . self::percent($card[$key('oneInstallmentPercentage')] ?? null) . $op,
            'Até 6x: ' . self::percent($card[$key('upToSixInstallmentsPercentage')] ?? null) . $op,
            'Até 12x: ' . self::percent($card[$key('upToTwelveInstallmentsPercentage')] ?? null) . $op,
            'Até 21x: ' . self::percent($card[$key('upToTwentyOneInstallmentsPercentage')] ?? null) . $op,
            self::days($card['daysToReceive'] ?? null),
        ];
    }

    private static function debitCardLines(): array
    {
        $card = data_get(self::fees(), 'payment.debitCard');

        if (!$card) {
            return ['—'];
        }

        return [
            self::percent($card['defaultPercentage'] ?? null) . ' + ' . self::money($card['operationValue'] ?? null),
            self::days($card['daysToReceive'] ?? null),
        ];
    }
}
