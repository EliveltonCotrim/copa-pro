<?php

namespace Database\Seeders;

use App\Enum\PaymentCheckoutProviderEnum;
use App\Models\PaymentGatewaySetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentGatewaySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentGatewaySetting::firstOrCreate(
            [ ],
            [
                'provider' => PaymentCheckoutProviderEnum::ASAAS
            ]
        );
    }
}
