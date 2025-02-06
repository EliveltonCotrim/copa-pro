<?php

namespace App\Livewire\Championship;

use App\Livewire\Forms\PlayerForm;
use App\Livewire\Forms\RegistrationPlayerForm;
use App\Livewire\Forms\UserForm;
use App\Models\Championship;
use Livewire\Component;

class RegistrationForm extends Component
{

    public RegistrationPlayerForm $registrationPlayerForm;
    public PlayerForm $playerForm;
    public UserForm $userForm;
    public $step = "1";
    public $nickname;
    public int $nextStep;
    public Championship $championship;

    public function nextStepControl(int $step)
    {
        dd($this->playerForm->all(), $this->nickname);
        $this->playerForm->validate();
        $this->step = "2";

    }

    public function backStepControl($step)
    {
        $this->step = "1";
    }

    public function save()
    {
        $this->step = '3';
    }

    public function render()
    {
        return view('livewire.championship.registration-form');
    }
}
