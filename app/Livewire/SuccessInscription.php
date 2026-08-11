<?php

namespace App\Livewire;

use App\Models\Championship;
use Livewire\Component;

class SuccessInscription extends Component
{
    public Championship $championship;

    public function render()
    {
        return view('livewire.success-inscription');
    }
}
