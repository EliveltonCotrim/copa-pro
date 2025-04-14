<?php

namespace App\Livewire\Championship;

use App\Models\{Championship, Player};
use Livewire\Attributes\On;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class RegistrationForm extends Component
{
    use Interactions;

    public Championship $championship;

    public ?array $registrationForm;

    public int $step = 1;

    public bool $showFormGeral = true;

    public ?Player $player;

    #[On('nextStep')]
    public function stepControl(int $step, array $registrationForm, ?int $player_id = null)
    {
        $this->step = $step;

        if ($step === 2) {
            $this->registrationForm = $registrationForm;
            $this->player           = $player_id ? Player::withTrashed()->where('id', $player_id)->first() : null;
            $this->showFormGeral    = false;
        }
    }

    public function render()
    {
        return view('livewire.championship.registration-form');
    }
}
