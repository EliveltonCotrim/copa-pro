<?php

namespace App\Livewire\Championship\Registration;

use App\Enum\{PlayerExperienceLevelEnum, PlayerPlatformGameEnum, PlayerSexEnum, RegistrationPlayerStatusEnum};
use App\Livewire\Championship\RegistrationForm;
use App\Livewire\Forms\RegistrationPlayerForm;
use App\Models\{Championship, Player, User};
use App\Notifications\RegistrationVerificationCode;
use Cache;
use Illuminate\Support\Facades\RateLimiter;
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
    public int $timeThrottle = 30;

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

        $params = ['step' => $step, 'registrationForm' => $this->registrationForm->all()];

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

        $this->user = $this->findUserByEmail();
        $this->player = $this->user?->userable;

        if (!$this->player) {
            $this->showVerificationForm = false;
            $this->showSearchPlayerForm = false;
            return;
        }

        $existingRegistrationPlayer = $this->user->userable->registrationsChampionships()
            ->where('championship_id', $this->championship->id)
            ->where('status', RegistrationPlayerStatusEnum::APPROVED)
            ->first();

        if ($existingRegistrationPlayer) {
            $this->toast()->warning('Você já está inscrito neste campeonato.')->send();

            return;
        }

        $this->sendVerificationCode();

        $this->showVerificationForm = true;
        $this->showSearchPlayerForm = false;
    }

    private function checkRateLimit(bool $isSearch = true)
    {
        $throttlekeyCode = 'verify-code-play-id:' . $this->registrationForm->player_id . '|' . request()->ip();

        if ($isSearch) {
            $throttlekey = 'search-play:' . $this->registrationForm->email . '|' . request()->ip();
        } else {
            $throttlekey = $throttlekeyCode;
        }

        if (RateLimiter::tooManyAttempts($throttlekey, 5)) {
            $seconds = RateLimiter::availableIn($throttlekey);
            $this->toast()->error("Muitas tentativas. Tente novamente em {$seconds} segundos.")->send();

            return false;
        }

        RateLimiter::hit($throttlekey, $this->timeThrottle);
        if ($isSearch) {
            RateLimiter::clear($throttlekeyCode);
        }

        return true;
    }

    public function sendVerificationCode()
    {
        if (!$this->checkRateLimit()) {
            return;
        }

        $verificationCode = random_int(10000, 99999);

        $this->user->notify(new RegistrationVerificationCode($verificationCode));

        Cache::put('verification_code_' . $this->user->userable->id, $verificationCode, now()->addMinutes(10));

        $this->registrationForm->setForm($this->user);

        $this->toast()->success('Código de verificação enviado para o e-mail cadastrado.')->timeout(10)->send();

        $this->dispatch('cooldown-started', seconds: $this->timeThrottle);
    }

    public function verifyCode()
    {
        if (!$this->checkRateLimit(false)) {
            return;
        }

        $this->validate([
            'registrationForm.verification_code' => 'required|numeric|digits:5',
        ]);

        $code = (string) Cache::get('verification_code_' . $this->registrationForm->player_id);

        if (empty($code)) {
            $this->toast()->error('Código expirou. Por favor, tente novamente.')->send();
            // $this->showVerificationForm = false;
            // $this->showInitForm = true;
            $this->registrationForm->verification_code = null;

            return;
        }

        if (hash_equals($code, (string) $this->registrationForm->verification_code)) {
            $this->showVerificationForm = false;
            // $this->showInitForm = false;
            // $this->showForm = true;

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
