<?php

namespace App\Livewire\Championship;

use App\Contracts\PaymentGatewayInterface;
use App\Enum\PaymentMethodEnum;
use App\Enum\PlayerExperienceLevelEnum;
use App\Enum\PlayerPlatformGameEnum;
use App\Enum\PlayerSexEnum;
use App\Livewire\Forms\PaymentPixForm;
use App\Livewire\Forms\PlayerForm;
use App\Livewire\Forms\RegistrationPlayerForm;
use App\Livewire\Forms\UserForm;
use App\Mail\VerificationCodeMail;
use App\Models\Championship;
use App\Models\Player;
use App\Notifications\RegistrationVerificationCode;
use App\Services\PaymentGateway\Connectors\Asaas\Customer;
use App\Services\PaymentGateway\Connectors\AsaasConnector;
use App\Services\PaymentGateway\Gateway;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
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
    public ?Player $player;
    protected Gateway $gateway;

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

            $verificationCode = rand(10000, 99999);

            $this->player->user->notify(new RegistrationVerificationCode($verificationCode));

            Cache::put('verification_code_' . $this->player->id, $verificationCode, now()->addMinutes(1));

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
            'registrationForm.cpf_cnpj' => 'required|string|max:18|formato_cpf_ou_cnpj',
        ]);

        // Verificar essa parte, testei com um player que possui um customer_id e não funcionou
        // recebi um retorno informando que o cliente existe, mas foi excluido
        // implementar um método para verificar se o cliente existe e está ativo
        // restore
        if (!empty($this->registrationForm->customer_id)) {
            $asaasCustomer = $this->gateway->customer()->show($this->registrationForm->customer_id);
        } else {
            $asaasCustomer = $this->gateway->customer()->create([
                'name' => $this->registrationForm->name,
                'email' => $this->registrationForm->email,
                'phone' => $this->registrationForm->phone,
                'cpfCnpj' => $this->registrationForm->cpf_cnpj,
            ]);
        }
        
        dd($asaasCustomer);

        $this->registrationForm->customer_id = $asaasCustomer['id'] ?? null;

        if (!empty($this->player)) {
            $this->player = $this->registrationForm->updatePlayer($this->player);
        } else {
            $this->player = $this->registrationForm->createPlayer();
        }

        dd($this->player);

        $this->registrationForm->customer_id = $asaasCustomer['id'] ?? null;
        $player = Player::create($this->registrationForm->all());

        $paymentData = [
            'billingType' => PaymentMethodEnum::PIX->value,
            'customer' => $this->registrationForm->customer ?? null,
            'cpfCnpj' => $this->paymentPixForm->cpf_cnpj,
            'gateway' => 'Asaas',
            'value' => $this->championship->registration_fee,
            'dueDate' => now()->addDays(1)->format('Y-m-d'),
        ];


    }

    public function savePayment()
    {
        dd('savePayment');
    }

    public function render()
    {
        return view('livewire.championship.registration-form');
    }
}
