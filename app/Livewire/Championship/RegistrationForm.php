<?php

namespace App\Livewire\Championship;

use App\Enum\PlayerExperienceLevelEnum;
use App\Enum\PlayerPlatformGameEnum;
use App\Enum\PlayerSexEnum;
use App\Livewire\Forms\PlayerForm;
use App\Livewire\Forms\RegistrationPlayerForm;
use App\Livewire\Forms\UserForm;
use App\Models\Championship;
use App\Models\Player;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class RegistrationForm extends Component
{
    use Interactions;

    public Championship $championship;
    public RegistrationPlayerForm $registrationForm;
    public string $step = "1";
    public array $genders = [];
    public array $gammingPlatforms = [];
    public array $experienceLevels = [];
    public bool $showForm = false;

    public ?string $nickname = '';
    public ?string $email = '';
    protected $player;


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
        $this->validateSearch();
        
        $this->player = $this->findPlayerByNicknameOrEmail();

        if ($this->player) {
            $this->handleExistingPlayer($this->player);
        } else {
            $this->handleNewPlayer();
        }

        $this->showForm = true;
    }

    private function validateSearch()
    {
        $this->validate([
            'nickname' => 'required_without:email|string',
            'email' => 'required_without:nickname|email:rfc,dns',
        ]);
    }

    /**
     * Search player by nickname or email
     * @return Player|null
     */
    private function findPlayerByNicknameOrEmail(): ?Player
    {
        return Player::when(!empty($this->nickname), function (Builder $query) {
            $query->where('nickname', $this->nickname);
        })->when(!empty($this->email), function (Builder $query) {
            $query->whereHas('user', function (Builder $subquery) {
                $subquery->where('email', $this->email)->where('userable_type', Player::class);
            });
        })->first();
    }

    /**
     * Prepare the form to create a new player whith the nickname and email
     * @return void
     */
    private function handleNewPlayer(): void
    {
        $this->registrationForm->email = $this->email;
        $this->registrationForm->nickname = $this->nickname;
    }

    /**
     * Fill out the form with the player's data
     * @param \App\Models\Player $player
     * @return void
     */
    private function handleExistingPlayer(Player $player): void
    {
        $this->registrationForm->setForm($player);
    }


    public function render()
    {
        return view('livewire.championship.registration-form');
    }
}
