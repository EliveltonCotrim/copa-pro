<?php

namespace App\Filament\Resources\PaymentGatewaySettingResource\Pages;

use App\Enum\PaymentCheckoutProviderEnum;
use App\Filament\Resources\PaymentGatewaySettingResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewPaymentGatewaySetting extends ViewRecord
{
    protected static string $resource = PaymentGatewaySettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('changeProvider')
                ->label('Alterar provedor')
                ->icon('heroicon-o-pencil-square')
                ->modalHeading('Alterar gateway de pagamento')
                ->modalWidth('md')
                ->modalSubmitActionLabel('Salvar')
                ->fillForm(fn() => ['provider' => $this->record->provider])
                ->form([
                    Select::make('provider')
                        ->label('Provedor')
                        ->options(PaymentCheckoutProviderEnum::class)
                        ->native(false)
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $this->record->update($data);

                    Notification::make()
                        ->success()
                        ->title('Gateway atualizado')
                        ->send();
                }),
        ];
    }
}
