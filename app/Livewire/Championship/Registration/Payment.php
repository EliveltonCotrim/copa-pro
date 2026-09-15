<?php

namespace App\Livewire\Championship\Registration;

use App\Enum\{PaymentCheckoutProviderEnum, PaymentMethodEnum, PaymentStatusEnum, RegistrationPlayerStatusEnum};
use App\Exceptions\PaymentGatewayException;
use App\Jobs\CancelUnpaidRegistrationJob;
use App\Livewire\Forms\RegistrationPlayerForm;
use App\Models\{Championship, Player, RegistrationPlayer};
use App\Notifications\SuccessfullyRegistered;
use App\Services\Internal\Payment\PaymentService;
use App\Services\PaymentGateway\Connectors\AsaasConnector;
use App\Services\PaymentGateway\Connectors\MercadoPagoConnector;
use App\Services\PaymentGateway\Gateway;
use App\Services\PaymentGateway\PaymentGatewayFactory;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class Payment extends Component
{
    use Interactions;

    public bool $isCpfFormVisible = true;

    public RegistrationPlayerForm $form;

    public Championship $championship;

    public ?Player $player = null;

    protected ?Gateway $gateway = null;
    protected PaymentService $paymentService;

    public \App\Models\Payment $playerCharge;

    public RegistrationPlayer $registrationPlayer;

    public function mount($registrationForm, ?int $championshipId = null, ?int $paymentId = null)
    {
        if (!blank($registrationForm)) {
            $this->form->setArrayForm($registrationForm);
        } else {
            $this->isCpfFormVisible = false;
        }

        if ($paymentId) {
            $this->championship = Championship::findOrFail($championshipId);
            $this->playerCharge = \App\Models\Payment::findOrFail($paymentId);
            $this->registrationPlayer = $this->playerCharge->registrationPlayer;
        }

    }

    // protected function gateway(string $provider = 'asaas'): Gateway
    // {
    //     $provider = 'mercadopago';

    //     if (!$this->gateway) {
    //         $connector = match ($provider) {
    //             'mercadopago' => app(MercadoPagoConnector::class),
    //             default => app(AsaasConnector::class),
    //         };

    //         $this->gateway = new Gateway($connector);
    //     }

    //     return $this->gateway;
    // }

    public function createPayment(PaymentService $paymentService)
    {
        $this->validate([
            'form.cpf_cnpj' => ['required', 'string', 'max:18', 'cpf_ou_cnpj'],
        ]);

        DB::beginTransaction();

        try {
            // $this->championship->lockForUpdate();
            $this->championship = Championship::where('id', $this->championship->id)
                ->lockForUpdate()
                ->firstOrFail();

            $registrationPlayersPending = $this->championship->registrationPlayers()
                ->whereHas('payments', function (Builder $query) {
                    $query->where('status', PaymentStatusEnum::PENDING);
                })->get();

            // Verifica o total de inscrições aprovadas
            $totalPlayersApproved = $this->championship
                // ->where('status', ChampionshipStatusEnum::REGISTRATION_OPEN)
                ->registrationPlayers()
                ->where('status', RegistrationPlayerStatusEnum::APPROVED)
                ->whereHas('payments', function (Builder $query) {
                    $query->where('status', PaymentStatusEnum::RECEIVED);
                })->count();

            $totalOccupiedSlots = $totalPlayersApproved + $registrationPlayersPending->count();

            if ($totalOccupiedSlots >= $this->championship->max_players) {
                DB::rollBack();

                $this->toast()
                    ->info('Todas as vagas estão temporariamente ocupadas, incluindo inscrições aguardando pagamento. Tente novamente em alguns minutos — uma vaga pode abrir caso alguma inscrição expire.')
                    ->timeout(20)
                    ->flash()
                    ->send();

                return $this->redirectRoute('championship.register', ['championship' => $this->championship->slug]);
            }

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
                'customerName' => $this->form->name,
                'customerEmail' => $this->form->email,
                'customerPhone' => $this->form->phone,
            ];

            $paymentResult = $paymentService->processPixPayment($paymentData, PaymentCheckoutProviderEnum::ASAAS->value);
            if ($redirect = $this->hasError($paymentResult)) {
                DB::rollBack();
                return $redirect;
            }

            $this->playerCharge = $this->registrationPlayer->payments()->create($paymentResult);

            $this->isCpfFormVisible = false;

            // passar o adaptor para o job
            CancelUnpaidRegistrationJob::dispatch($this->registrationPlayer->id)->onQueue('registration-cancel')->delay(now()->addMinutes(20))->afterCommit();

            DB::commit();

        } catch (PaymentGatewayException $e) {
            // API do Asaas/MP retornou erro (ex: CPF inválido).
            DB::rollBack();

            // Infomar o gateway
            Log::error('Error creating payment: ', [
                $e->getMessage(),
                $e->getTraceAsString(),
            ]);

            $this->toast()
                ->warning('Erro ao processar pagamento. Por favor, tente novamente em alguns instantes.')
                ->flash()
                ->send();

            return $this->redirectRoute('championship.register', $this->championship);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Error creating payment: ', [
                $e->getMessage(),
                $e->getTraceAsString(),
            ]);

            // Implementar isso
            // if (isset($payment['id'])) {
            //     $this->gateway()->payment()->delete($payment['id']);
            // }

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
        $notify = false;

        $redirect = DB::transaction(function () use (&$notify) {

            $this->registrationPlayer = RegistrationPlayer::withTrashed()
                ->where('id', $this->registrationPlayer->id)
                ->lockForUpdate()
                ->first();

            if (!empty($this->registrationPlayer->deleted_at)) {
                $this->toast()->info('O QR Code da sua inscrição venceu. Tente gerar uma nova inscrição para garantir sua participação no campeonato.')
                    ->timeout(10)
                    ->flash()
                    ->send();

                return $this->redirectRoute('championship.register', ['championship' => $this->championship->slug]);
            }

            if ($this->playerCharge->status === PaymentStatusEnum::RECEIVED) {

                $this->registrationPlayer->status = RegistrationPlayerStatusEnum::APPROVED;
                $this->registrationPlayer->payment_status = PaymentStatusEnum::RECEIVED;
                $this->registrationPlayer->save();

                $notify = true;

                $this->toast()->success('Inscrição realizada com sucesso.')
                    ->flash()
                    ->send();

                return $this->redirectRoute('championship.register-success', $this->championship);
            }

            return null;
        });

        if ($notify) {
            $this->registrationPlayer->player->user->notify(new SuccessfullyRegistered($this->championship));
        }

        return $redirect;
    }

    public function hasError(array $response): ?RedirectResponse
    {
        if (isset($response['error']) && $response['error'] === true) {

            Log::error('Erro na resposta do gateway de pagamento - creating payment: ', [
                'response' => $response,
                'registration_player_id' => $this->registrationPlayer?->id ?? null,
                'championship_id' => $this->championship->id,
            ]);

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
