<?php

namespace App\Filament\Resources\PaymentGatewaySettingResource\Pages;

use App\Filament\Resources\PaymentGatewaySettingResource;
use App\Models\PaymentGatewaySetting;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPaymentGatewaySettings extends ListRecords
{
    protected static string $resource = PaymentGatewaySettingResource::class;

    public function mount(): void
    {
        $record = PaymentGatewaySetting::query()->firstOrFail();

        $this->redirect(
            PaymentGatewaySettingResource::getUrl('view', ['record' => $record]),
            navigate: true,
        );
    }
}
