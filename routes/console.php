<?php

use App\Services\AsaasPhp\Customer\CustomerCreate;
use App\Services\PaymentGateway\Connectors\AsaasConnector;
use App\Services\PaymentGateway\Gateway;
use Illuminate\Support\Facades\Artisan;

Artisan::command('play', function () {

    $adapter = new AsaasConnector;

    $gateway = new Gateway($adapter);

    ds($gateway->customer()->show('cus_000006504209'));
    // $clients = (new CustomerCreate(data: [
    //     'name' => 'Elivelton',
    //     'cpfCnpj' => '05493282542',
    // ])->handle());

    // dd($clients);
})->purpose('Display an inspiring quote')->hourly();
