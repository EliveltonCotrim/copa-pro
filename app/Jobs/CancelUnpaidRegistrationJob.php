<?php

namespace App\Jobs;

use App\Enum\PaymentStatusEnum;
use App\Enum\RegistrationPlayerStatusEnum;
use App\Models\RegistrationPlayer;
use App\Services\PaymentGateway\Gateway;
use Exception;
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

    public int $tries = 3;
    public array $backoff = [10, 30, 60];
    public int $timeout = 60;

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
        DB::transaction(function () {
            $registration = RegistrationPlayer::where('id', $this->registrationPlayerId)
                ->lockForUpdate()
                ->with('payments')
                ->first();

            if (!$registration) {
                return;
            }

            $stillUnpaid = $registration->payment_status !== PaymentStatusEnum::RECEIVED
                && $registration->status === RegistrationPlayerStatusEnum::REGISTERED;

            if (!$stillUnpaid) {
                return;
            }

            $pendingPayments = $registration->payments->where('status', PaymentStatusEnum::PENDING);

            if ($pendingPayments->isEmpty()) {
                return;
            }

            $adapter = app(AsaasConnector::class);
            $this->gateway = new Gateway($adapter);

            foreach ($pendingPayments as $payment) {
                try {
                    $this->gateway->payment()->delete($payment->transaction_id);
                } catch (Exception $e) {
                    // Pode já ter sido deletado em uma tentativa anterior (retry),
                    // ou não existir mais no gateway. Loga e segue sem travar o job.
                    report($e);
                }
            }

            $registration->delete();
        });
    }
}
