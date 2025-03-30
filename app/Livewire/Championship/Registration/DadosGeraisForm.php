<?php

namespace App\Livewire\Championship\Registration;

use App\Enum\PlayerExperienceLevelEnum;
use App\Enum\PlayerPlatformGameEnum;
use App\Enum\PlayerSexEnum;
use App\Livewire\Championship\RegistrationForm;
use App\Livewire\Forms\RegistrationPlayerForm;
use App\Models\Championship;
use App\Models\Player;
use App\Models\User;
use App\Notifications\RegistrationVerificationCode;
use Cache;
use Illuminate\Database\Eloquent\Builder;
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

    public Championship $championship;

    public function mount(Championship $championship)
    {
        // token: uVAYSf8MKiKDWKUYn1w4LGoHcj5gj2b8ZyqssJ4HBdn1RpUn5UP5s3BeJNj5
        $this->genders = PlayerSexEnum::optionsArrayWithLabelAndValues();
        $this->gammingPlatforms = PlayerPlatformGameEnum::optionsArrayWithLabelAndValues();
        $this->experienceLevels = PlayerExperienceLevelEnum::optionsArrayWithLabelAndValues();
    }

    public function nextStep(int $step)
    {
        $this->registrationForm->validate();

        $params = ['step' => $step, 'registrationForm' => $this->registrationForm];

        if (!is_null($this->user->userable)) {
            $params['player_id'] = $this->user->userable->id;
        }

        $this->dispatch('nextStep', ...$params)->to(RegistrationForm::class);
    }

    public function searchPlayer()
    {
        $this->validate([
            'registrationForm.email' => 'required|email:rfc,dns',
        ]);

        $this->user = $this->findUserByEmail();

        if ($this->user?->userable->exists()) {
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

            // Mail::to($this->user->email)->send(new VerificationCodeMail($verificationCode));

            $this->handleExistingPlayer($this->user->userable);

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

    private function findUserByEmail(): ?User
    {
        return User::where('email', $this->registrationForm->email)
            ->where('userable_type', Player::class)
            ->first();
    }

    private function handleExistingPlayer(Player $player): void
    {
        $this->registrationForm->setForm($player);
    }

    public function render()
    {
        return view('livewire.championship.registration.dados-gerais-form');
    }
}
