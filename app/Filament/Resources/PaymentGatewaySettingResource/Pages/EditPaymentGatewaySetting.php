<?php

namespace App\Filament\Resources\PaymentGatewaySettingResource\Pages;

use App\Filament\Resources\PaymentGatewaySettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaymentGatewaySetting extends EditRecord
{
    protected static string $resource = PaymentGatewaySettingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
