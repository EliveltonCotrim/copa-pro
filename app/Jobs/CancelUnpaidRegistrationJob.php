<?php

namespace App\Jobs;

use App\Enum\PaymentStatusEnum;
use App\Enum\RegistrationPlayerStatusEnum;
use App\Models\RegistrationPlayer;
use App\Services\PaymentGateway\PaymentGatewayFactory;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;


class CancelUnpaidRegistrationJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, Dispatchable, SerializesModels;

    protected int $registrationPlayerId;
    public int $tries = 3;
    public array $backoff = [10, 30, 60];
    public int $timeout = 60;
    protected string $checkoutProvider;
    protected PaymentGatewayFactory $factory;

    /**
     * Create a new job instance.
     */
    public function __construct(int $registarionPlayerId, string $checkoutProvider)
    {
        $this->registrationPlayerId = $registarionPlayerId;
        $this->checkoutProvider = $checkoutProvider;
        $this->factory = new PaymentGatewayFactory();
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

            $gateway = $this->factory->make($this->checkoutProvider);

            foreach ($pendingPayments as $payment) {
                try {
                    $gateway->payment()->delete($payment->transaction_id);
                    $registration->update(['status' => RegistrationPlayerStatusEnum::CANCELLED]);
                    $payment->delete();

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
