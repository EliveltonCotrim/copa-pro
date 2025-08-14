<?php

namespace App\Jobs;

use App\Enum\PaymentStatusEnum;
use App\Enum\RegistrationPlayerStatusEnum;
use App\Models\RegistrationPlayer;
use App\Services\PaymentGateway\Gateway;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Services\PaymentGateway\Connectors\AsaasConnector;
use Illuminate\Support\Facades\Log;


class CancelUnpaidRegistrationJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, Dispatchable, SerializesModels;

    protected int $registrationPlayerId;

    protected Gateway $gateway;

    /**
     * Create a new job instance.
     */
    public function __construct(int $registarionPlayerId)
    {
        $this->registrationPlayerId = $registarionPlayerId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $adapter = app(AsaasConnector::class);
        $this->gateway = new Gateway($adapter);

        DB::transaction(function () {
            $registration = RegistrationPlayer::with('payments')
                ->find($this->registrationPlayerId);

            if(!$registration){
                return;
            }

            if($registration->payment_status !== PaymentStatusEnum::RECEIVED && $registration->status === RegistrationPlayerStatusEnum::REGISTERED){
                $payment = $registration->payments()->latest()->first();
                Log::info('paymen: ' . $payment);
                $this->gateway->payment()->delete($payment->transaction_id);
                
                $payment->delete();
                $registration->delete();
            }

        });
    }
}
