<?php

namespace App\Livewire\Championship;

use App\Contracts\PaymentGatewayInterface;
use App\Enum\PaymentMethodEnum;
use App\Enum\PaymentStatusEnum;
use App\Enum\PlayerExperienceLevelEnum;
use App\Enum\PlayerPlatformGameEnum;
use App\Enum\PlayerSexEnum;
use App\Enum\RegistrationPlayerStatusEnum;
use App\Livewire\Forms\PaymentPixForm;
use App\Livewire\Forms\PlayerForm;
use App\Livewire\Forms\RegistrationPlayerForm;
use App\Livewire\Forms\UserForm;
use App\Mail\VerificationCodeMail;
use App\Models\Championship;
use App\Models\Payment;
use App\Models\Player;
use App\Models\RegistrationPlayer;
use App\Notifications\RegistrationVerificationCode;
use App\Services\PaymentGateway\Connectors\Asaas\Customer;
use App\Services\PaymentGateway\Connectors\AsaasConnector;
use App\Services\PaymentGateway\Gateway;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use LaravelLegends\PtBrValidator\Rules\FormatoCpfOuCnpj;
use Livewire\Component;
use Str;
use TallStackUi\Traits\Interactions;

class RegistrationForm extends Component
{
    use Interactions;

    public Championship $championship;
    public RegistrationPlayerForm $registrationForm;
    public PaymentPixForm $paymentPixForm;
    public PaymentMethodEnum $paymentMethodEnum;
    public string $step = "1";
    public array $genders = [];
    public array $gammingPlatforms = [];
    public array $experienceLevels = [];
    public bool $showForm = false;
    public bool $showVerificationForm = false;
    public bool $showInitForm = true;
    public bool $showPaymentForm = true;
    public ?Player $player;
    protected Gateway $gateway;

    public $qr_code = "00020101021226820014br.gov.bcb.pix2560qrpix-h.bradesco.com.br/9d36b84f-c70b-478f-b95c-12729b90ca25520400005303986540516.005802BR5905ASAAS6009JOINVILLE62070503***630459A2";
    public $qr_code_64 = "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";

    public Payment $playerCharge;

    public function boot()
    {
        $adapter = app(AsaasConnector::class);
        $this->gateway = new Gateway($adapter);
    }

    public function mount()
    {
        $this->genders = PlayerSexEnum::optionsArrayWithLabelAndValues();
        $this->gammingPlatforms = PlayerPlatformGameEnum::optionsArrayWithLabelAndValues();
        $this->experienceLevels = PlayerExperienceLevelEnum::optionsArrayWithLabelAndValues();
    }

    public function nextStepControl()
    {
        $this->showForm = true;
        $this->registrationForm->validate();

        $this->step = '2';
    }

    public function searchPlayer()
    {

        $this->validate([
            'registrationForm.nickname' => 'required_without:registrationForm.email|string',
            'registrationForm.email' => 'required_without:registrationForm.nickname|email:rfc,dns',
        ]);

        $this->player = $this->findPlayerByNicknameOrEmail();

        if ($this->player) {

            $existingRegistrationPlayer = $this->player->registrationsChampionships()
                ->where('championship_id', $this->championship->id)
                ->first();

            // if ($existingRegistrationPlayer) {
            //     $this->toast()->warning('Você já está inscrito neste campeonato.')->send();
            //     return;
            // }

            $verificationCode = rand(10000, 99999);

            $this->player->user->notify(new RegistrationVerificationCode($verificationCode));

            Cache::put('verification_code_' . $this->player->id, $verificationCode, now()->addMinutes(10));

            // Mail::to($this->player->user->email)->send(new VerificationCodeMail($verificationCode));

            $this->handleExistingPlayer($this->player);

            $this->toast()->success('Código de verificação enviado para o e-mail cadastrado.')->send();

            $this->showVerificationForm = true;
            $this->showInitForm = false;

            return;
        }

        $this->showInitForm = false;
        $this->showForm = true;
    }

    public function verifyCode()
    {
        $this->validate([
            'registrationForm.verification_code' => 'required|numeric|digits:5',
        ]);

        $code = (string) Cache::get('verification_code_' . $this->registrationForm->player_id);

        if (empty($code)) {
            $this->toast()->error('Código expirou. Por favor, tente novamente.')->send();
            $this->showVerificationForm = false;
            $this->showInitForm = true;
            $this->registrationForm->verification_code = null;
            return;
        }

        if ($code == $this->registrationForm->verification_code) {
            $this->showVerificationForm = false;
            $this->showInitForm = false;
            $this->showForm = true;
        } else {
            $this->toast()->error('Código de verificação inválido.')->send();
            return;
        }
    }

    private function findPlayerByNicknameOrEmail(): ?Player
    {
        return Player::when(!empty($this->registrationForm->nickname), function (Builder $query) {
            $query->where('nickname', $this->registrationForm->nickname);
        })->when(!empty($this->registrationForm->email), function (Builder $query) {
            $query->whereHas('user', function (Builder $subquery) {
                $subquery->where('email', $this->registrationForm->email)->where('userable_type', Player::class);
            });
        })->first();
    }

    private function handleExistingPlayer(Player $player): void
    {
        $this->registrationForm->setForm($player);
    }

    public function createPayment()
    {
        $this->validate([
            'registrationForm.cpf_cnpj' => ['required', 'string', 'max:18', 'cpf_ou_cnpj'],
        ]);

        if (!empty($this->registrationForm->customer_id)) {

            $asaasCustomer = $this->gateway->customer()->show($this->registrationForm->customer_id);

            $this->hasError($asaasCustomer);

            if ($asaasCustomer['deleted'] == true) {
                $asaasCustomer = $this->registrationForm->createCustomerAsaas();
            }

        } else {
            $asaasCustomer = $this->registrationForm->createCustomerAsaas();
        }

        $this->registrationForm->customer_id = $asaasCustomer['id'] ?? null;

        if (!empty($this->player)) {
            $this->player = $this->registrationForm->updatePlayer($this->player);
        } else {
            $this->player = $this->registrationForm->createPlayer();
        }

        $registrationPlayer = RegistrationPlayer::create([
            'championship_id' => $this->championship->id,
            'championship_team_name' => $this->registrationForm->championship_team_name,
            'player_id' => $this->player->id,
        ]);

        $paymentData = [
            'billingType' => PaymentMethodEnum::PIX->value,
            'customer' => $this->registrationForm->customer_id,
            'cpfCnpj' => $this->paymentPixForm->cpf_cnpj,
            'value' => $this->championship->registration_fee,
            'description' => 'Inscrição no campeonato: ' . $this->championship->name,
            'dueDate' => now()->addDays(1)->format('Y-m-d'),
        ];

        $payment = $this->gateway->payment()->create($paymentData);

        $this->hasError($payment);

        $paymentQrcode = $this->gateway->payment()->getPixQrCode($payment['id']);

        $this->playerCharge = $registrationPlayer->payments()->create([
            'transaction_id' => $payment['id'],
            'value' => $this->championship->registration_fee,
            'netValue' => $payment['netValue'],
            'method' => PaymentMethodEnum::PIX->value,
            'status' => PaymentStatusEnum::PENDING->value,
            'qr_code_64' => $paymentQrcode['encodedImage'],
            'qr_code' => $paymentQrcode['payload'],
        ]);

        $this->showPaymentForm = false;
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

    public function checkPayment()
    {
        $this->playerCharge->refresh();

        if ($this->playerCharge->status === PaymentStatusEnum::PAID) {
            $this->toast()->success('Inscrição realizada com sucesso.')
                ->flash()
                ->send();

            // TODO Enviar e-mail de confirmação de inscrição contemplando os detalhes do campeonato e o comprovante de pagamento
            // TODO Redirecionar para a página de detalhes do campeonato

            return $this->redirectRoute('championship.register', $this->championship);
        }
    }

    public function render()
    {
        return view('livewire.championship.registration-form');
    }
}
