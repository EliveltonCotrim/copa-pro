<?php

namespace App\Livewire\Championship\Registration;

use App\Enum\{PlayerExperienceLevelEnum, PlayerPlatformGameEnum, PlayerSexEnum};
use App\Livewire\Championship\RegistrationForm;
use App\Livewire\Forms\RegistrationPlayerForm;
use App\Mail\VerificationCodeMail;
use App\Models\{Championship, Player, User};
use App\Notifications\RegistrationVerificationCode;
use Cache;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class DadosGeraisForm extends Component
{
    use Interactions;

    public RegistrationPlayerForm $registrationForm;

    public bool $showSearchPlayerForm = true;

    public bool $showVerificationForm = false;

    public array $genders = [];

    public array $gammingPlatforms = [];

    public array $experienceLevels = [];

    public ?User $user = null;

    public ?Player $player = null;

    public Championship $championship;

    public function mount(Championship $championship)
    {
        // token: uVAYSf8MKiKDWKUYn1w4LGoHcj5gj2b8ZyqssJ4HBdn1RpUn5UP5s3BeJNj5
        $this->genders          = PlayerSexEnum::optionsArrayWithLabelAndValues();
        $this->gammingPlatforms = PlayerPlatformGameEnum::optionsArrayWithLabelAndValues();
        $this->experienceLevels = PlayerExperienceLevelEnum::optionsArrayWithLabelAndValues();
    }

    public function nextStep(int $step)
    {
        $this->registrationForm->validate();

        $params = ['step' => $step, 'registrationForm' => $this->registrationForm];

        if ($this->player) {
            $params['player_id'] = $this->player->id;
        }

        $this->dispatch('nextStep', ...$params)->to(RegistrationForm::class);
    }

    public function searchPlayer()
    {
        $this->validate([
            'registrationForm.email' => 'required|email:rfc,dns',
        ]);

        $this->user   = $this->findUserByEmail();
        $this->player = $this->user?->userable;

        if ($this->player) {
            $existingRegistrationPlayer = $this->user->userable->registrationsChampionships()
                ->where('championship_id', $this->championship->id)
                ->first();

            if ($existingRegistrationPlayer) {
                $this->toast()->warning('Você já está inscrito neste campeonato.')->send();

                return;
            }

            $verificationCode = rand(10000, 99999);

            $this->user->notify(new RegistrationVerificationCode($verificationCode));

            Cache::put('verification_code_' . $this->user->userable->id, $verificationCode, now()->addMinutes(10));

            Mail::to($this->user->email)->send(new VerificationCodeMail($verificationCode, $this->user->name));

            $this->registrationForm->setForm($this->user);

            $this->toast()->success('Código de verificação enviado para o e-mail cadastrado.')->send();

            $this->showVerificationForm = true;
            $this->showSearchPlayerForm = false;

            return;
        }

        $this->showVerificationForm = false;
        $this->showSearchPlayerForm = false;

    }

    public function verifyCode()
    {
        $this->validate([
            'registrationForm.verification_code' => 'required|numeric|digits:5',
        ]);

        $code = (string) Cache::get('verification_code_' . $this->registrationForm->player_id);

        if (empty($code)) {
            $this->toast()->error('Código expirou. Por favor, tente novamente.')->send();
            $this->showVerificationForm                = false;
            $this->showInitForm                        = true;
            $this->registrationForm->verification_code = null;

            return;
        }

        if ($code == $this->registrationForm->verification_code) {
            $this->showVerificationForm = false;
            $this->showInitForm         = false;
            $this->showForm             = true;

            return;
        }

        $this->toast()->error('Código de verificação inválido.')->send();

    }

    private function findUserByEmail(): ?User
    {
        return User::where('email', $this->registrationForm->email)
            ->with([
                'userable' => function ($userable) {
                    $userable->withTrashed();
                },
            ])
            ->where('userable_type', Player::class)
            ->withTrashed()
            ->first();
    }

    public function render()
    {
        return view('livewire.championship.registration.dados-gerais-form');
    }
}
