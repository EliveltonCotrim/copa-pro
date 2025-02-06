<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class PlayerForm extends Form
{
    #[Validate('required|min:3')]
    public $nickname = '';
    public $birth_dt = '';
    public $phone = '';
    public $game_platform = '';
    public $sex = '';
    public $level_experience = '';
}
