<?php

namespace App\Livewire\Championship\Registration;

use App\Enum\{PaymentMethodEnum, PaymentStatusEnum, RegistrationPlayerStatusEnum};
use App\Enum\ChampionshipStatusEnum;
use App\Jobs\CancelUnpaidRegistrationJob;
use App\Livewire\Forms\RegistrationPlayerForm;
use App\Models\{Championship, Player, RegistrationPlayer};
use App\Services\PaymentGateway\Connectors\AsaasConnector;
use App\Services\PaymentGateway\Gateway;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class Payment extends Component
{
    use Interactions;

    public bool $isCpfFormVisible = true;

    public RegistrationPlayerForm $form;

    public Championship $championship;

    public ?Player $player = null;

    protected Gateway $gateway;

    public \App\Models\Payment $playerCharge;

    public RegistrationPlayer $registrationPlayer;

    // registrationForm

    public function boot()
    {
        $adapter = app(AsaasConnector::class);
        $this->gateway = new Gateway($adapter);
    }

    public function mount($registrationForm)
    {
        $this->form->setArrayForm($registrationForm);

    }

    public function createPayment()
    {
        $this->validate([
            'form.cpf_cnpj' => ['required', 'string', 'max:18', 'cpf_ou_cnpj'],
        ]);

        DB::beginTransaction();

        try {
            // verificar esse lockfoUpdate
            $this->championship->lockForUpdate();

            // Verifica se o campeonato está aberto para inscrições
            $totalPlayersApproved = $this->championship->registrationPlayers()
                ->where('status', RegistrationPlayerStatusEnum::APPROVED)
                ->whereHas('payments', function (Builder $query) {
                    $query->where('status', PaymentStatusEnum::RECEIVED);
                })->count();

            $registrationPlayersPending = $this->championship->registrationPlayers()
                ->whereHas('payments', function (Builder $query) {
                    $query->where('status', PaymentStatusEnum::PENDING);
                })->get();

            foreach ($registrationPlayersPending as $key => $registrationPlayerPending) {
                if ($registrationPlayerPending->created_at->timezone(config('app.timezone'))->addMinutes(15)->isPast()) {
                    $registrationPlayerPending->delete();
                }
            }

            // $totalOccupiedSlots = $totalPlayersApproved + $totalPlayersPending;

            if ($totalPlayersApproved >= $this->championship->max_players) {
                $this->toast()
                    ->error('Inscrições encerradas, limite de jogadores atingido.')
                    ->flash()
                    ->send();

                return $this->redirectRoute('championship.register', $this->championship);
            }

            if ($totalPlayersApproved === $this->championship->max_players - 1) {
                // processar ultima vaga
                // dd($totalPlayersApproved, $totalPlayersPending, $totalPlayersApproved === $this->championship->max_players - 1);

            }

            if (!empty($this->form->customer_id)) {

                $asaasCustomer = $this->gateway->customer()->show($this->form->customer_id);

                $this->hasError($asaasCustomer);

                if ($asaasCustomer['deleted']) {
                    $asaasCustomer = $this->form->createCustomerAsaas();
                }

            } else {
                $asaasCustomer = $this->form->createCustomerAsaas();
            }

            $this->form->customer_id = $asaasCustomer['id'] ?? null;

            if ($this->player) {
                if ($this->player->trashed()) {
                    $this->player->restore();
                    $this->player->user->restore();
                }

                $this->player = $this->form->updatePlayer($this->player);
            } else {
                $this->player = $this->form->createPlayer();
            }

            $this->registrationPlayer = RegistrationPlayer::create([
                'championship_id' => $this->championship->id,
                'championship_team_name' => $this->form->championship_team_name,
                'player_id' => $this->player->id,
            ]);

            $paymentData = [
                'billingType' => PaymentMethodEnum::PIX->value,
                'customer' => $this->form->customer_id,
                'cpfCnpj' => $this->form->cpf_cnpj,
                'value' => $this->championship->getFeeFormatedAttribute(false),
                'description' => 'Inscrição no campeonato: ' . $this->championship->name,
                'dueDate' => now()->format('Y-m-d'),
            ];

            $payment = $this->gateway->payment()->create($paymentData);

            $this->hasError($payment);

            $paymentQrcode = $this->gateway->payment()->getPixQrCode($payment['id']);

            $this->playerCharge = $this->registrationPlayer->payments()->create([
                'transaction_id' => $payment['id'],
                'value' => $payment['value'],
                'description' => $payment['description'],
                'net_value' => $payment['netValue'],
                'due_date' => $payment['dueDate'],
                'date_created' => $payment['dateCreated'],
                'billing_type' => PaymentMethodEnum::PIX->value,
                'status' => PaymentStatusEnum::parse($payment['status']),
                'qr_code_64' => $paymentQrcode['encodedImage'],
                'qr_code' => $paymentQrcode['payload'],
            ]);

            $this->isCpfFormVisible = false;

            CancelUnpaidRegistrationJob::dispatch($this->registrationPlayer->id)->onQueue('registration-cancel')->delay(now()->addMinutes(1));

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            if (isset($payment['id'])) {
                $this->gateway->payment()->delete($payment['id']);
            }

            $this->toast()
                ->error('Houve um erro inesperado. Por favor, tente novamente em alguns instantes.')
                ->flash()
                ->send();

            return $this->redirectRoute('championship.register', $this->championship);

        }

    }

    public function checkPayment()
    {
        $this->playerCharge->refresh();
        $this->registrationPlayer->refresh();

        if (!empty($this->registrationPlayer->deleted_at)) {
            $this->toast()->info('O QR Code da sua inscrição venceu. Tente gerar uma nova inscrição para garantir sua participação no campeonato.')
                ->timeout(10)
                ->flash()
                ->send();

            return $this->redirectRoute('championship.register', ['championship' => $this->championship->slug]);
        }


        if ($this->playerCharge->status === PaymentStatusEnum::RECEIVED) {

            $this->playerCharge->registrationPlayer->status = RegistrationPlayerStatusEnum::APPROVED;
            $this->playerCharge->registrationPlayer->payment_status = PaymentStatusEnum::RECEIVED;
            $this->playerCharge->registrationPlayer->save();

            $this->toast()->success('Inscrição realizada com sucesso.')
                ->flash()
                ->send();

            // TODO - Enviar e-mail de confirmação de inscrição contemplando os detalhes do campeonato e o comprovante de pagamento

            return $this->redirectRoute('championship.register-success', $this->championship);
        }
    }

    public function hasError(array $response): ?RedirectResponse
    {
        if (isset($response['error']) && $response['error'] === true) {
            $this->toast()
                ->error('Houve um erro inesperado. Por favor, tente novamente em alguns instantes.')
                ->flash()
                ->send();

            return $this->redirectRoute('championship.register', $this->championship);
        }

        return null;
    }

    public function render()
    {
        return view('livewire.championship.registration.payment');
    }
}
