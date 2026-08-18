<?php

namespace App\Livewire\Championship;

use App\Livewire\Forms\RegistrationPlayerForm;
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
    public ?string $action = null;

    public ?Player $player;
    public ?int $championshipId = null;
    public ?int $paymentId = null;

    #[On('nextStep')]
    public function stepControl(int $step, array $registrationForm, ?int $player_id = null, ?string $action = null, ?int $championship_id = null, ?int $payment_id = null)
    {
        $this->step = $step;
        $this->action = $action;

        if ($action === 'show-pix-again') {
            $this->showFormGeral = false;
            $this->championshipId = $championship_id;
            $this->paymentId = $payment_id;
        }
        
        if ($step === 2) {
            if ($action !== 'show-pix-again') {
                $this->registrationForm = $registrationForm;

            }
            $this->player = $player_id ? Player::withTrashed()->where('id', $player_id)->first() : null;
            $this->showFormGeral = false;

        }
    }

    public function render()
    {
        return view('livewire.championship.registration-form');
    }
}
