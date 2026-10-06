<?php

namespace App\Filament\Resources;

use App\Enum\PaymentCheckoutProviderEnum;
use App\Filament\Resources\PaymentGatewaySettingResource\Pages;
use App\Filament\Resources\PaymentGatewaySettingResource\RelationManagers;
use App\Models\PaymentGatewaySetting;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Components\Section as InfolistSection;

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
}
