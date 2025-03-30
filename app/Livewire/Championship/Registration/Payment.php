<?php

namespace App\Livewire\Championship\Registration;

use App\Enum\PaymentMethodEnum;
use App\Enum\PaymentStatusEnum;
use App\Enum\RegistrationPlayerStatusEnum;
use App\Livewire\Forms\RegistrationPlayerForm;
use App\Models\Championship;
use App\Models\Player;
use App\Models\RegistrationPlayer;
use App\Services\PaymentGateway\Connectors\AsaasConnector;
use App\Services\PaymentGateway\Gateway;
use Illuminate\Http\RedirectResponse;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class Payment extends Component
{
    use Interactions;

    public bool $isCpfFormVisible = true;

    public RegistrationPlayerForm $form;

    public Championship $championship;

    public ?Player $player;

    protected Gateway $gateway;

    public \App\Models\Payment $playerCharge;

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

        if (! empty($this->form->customer_id)) {

            $asaasCustomer = $this->gateway->customer()->show($this->form->customer_id);

            $this->hasError($asaasCustomer);

            if ($asaasCustomer['deleted'] == true) {
                $asaasCustomer = $this->form->createCustomerAsaas();
            }

        } else {
            $asaasCustomer = $this->form->createCustomerAsaas();
        }

        $this->form->customer_id = $asaasCustomer['id'] ?? null;

        if (! empty($this->player)) {
            $this->player = $this->form->updatePlayer($this->player);
        } else {
            $this->player = $this->form->createPlayer();
        }

        $registrationPlayer = RegistrationPlayer::create([
            'championship_id' => $this->championship->id,
            'championship_team_name' => $this->form->championship_team_name,
            'player_id' => $this->player->id,
        ]);

        $paymentData = [
            'billingType' => PaymentMethodEnum::PIX->value,
            'customer' => $this->form->customer_id,
            'cpfCnpj' => $this->form->cpf_cnpj,
            'value' => $this->championship->registration_fee,
            'description' => 'Inscrição no campeonato: '.$this->championship->name,
            'dueDate' => now()->addDays(1)->format('Y-m-d'),
        ];

        $payment = $this->gateway->payment()->create($paymentData);

        $this->hasError($payment);

        $paymentQrcode = $this->gateway->payment()->getPixQrCode($payment['id']);

        $this->playerCharge = $registrationPlayer->payments()->create([
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
    }

    public function checkPayment()
    {
        $this->playerCharge->refresh();
        if ($this->playerCharge->status === PaymentStatusEnum::RECEIVED) {

            $this->playerCharge->registrationPlayer->status = RegistrationPlayerStatusEnum::APPROVED;
            $this->playerCharge->registrationPlayer->payment_status = PaymentStatusEnum::RECEIVED;
            $this->playerCharge->registrationPlayer->save();

            $this->toast()->success('Inscrição realizada com sucesso.')
                ->flash()
                ->send();

            // TODO Enviar e-mail de confirmação de inscrição contemplando os detalhes do campeonato e o comprovante de pagamento
            // TODO Redirecionar para a página de detalhes do campeonato

            return $this->redirectRoute('championship.register', $this->championship);
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
