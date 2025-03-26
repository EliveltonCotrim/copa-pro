<?php

namespace App\Livewire\Forms;

use App\Enum\PlayerExperienceLevelEnum;
use App\Enum\PlayerPlatformGameEnum;
use App\Enum\PlayerSexEnum;
use Illuminate\Validation\Rule;
use Livewire\Form;

class PlayerForm extends Form
{
    public $nickname = '';

    public $heart_team_name = '';

    public $birth_dt = '';

    public $sex = '';

    public $phone = '';

    public $game_platform = '';

    public $level_experience = '';

    public function rules()
    {
        return [
            'nickname' => 'nullable|string|min:3|max:50',
            'heart_team_name' => 'nullable|string|max:255',
            'birth_dt' => 'nullable|date|before_or_equal:today',
            'sex' => ['nullable', Rule::enum(PlayerSexEnum::class)],
            'phone' => 'required|string|celular_com_ddd|max:20',
            'game_platform' => ['required', Rule::enum(PlayerPlatformGameEnum::class)],
            'level_experience' => ['required', Rule::enum(PlayerExperienceLevelEnum::class)],
        ];

    }
}
