<?php

namespace App\Livewire\Forms;

use App\Enum\PlayerExperienceLevelEnum;
use App\Enum\PlayerPlatformGameEnum;
use App\Enum\PlayerSexEnum;
use App\Models\Player;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Validation\Rule;

class RegistrationPlayerForm extends Form
{
    // registration table
    public $championship_team_name = '';

    // player table
    public string $nickname = '';
    public string $heart_team_name = '';
    public string $birth_dt = '';
    public int $sex;
    public string $phone = '';
    public int $game_platform;
    public int $level_experience;

    // user table
    public $name = '';
    public $email = '';

    public $user_id;
    public $player_id;


    public function rules()
    {

        return [
            'nickname' => ['nullable', 'string', 'min:3', 'max:50', Rule::unique('players', 'nickname')->ignore($this->player_id)],
            'heart_team_name' => 'nullable|string|max:255',
            'birth_dt' => 'nullable|date|before_or_equal:today',
            'sex' => ['nullable', Rule::in(PlayerSexEnum::values())],
            'phone' => 'required|string|celular_com_ddd|max:20',
            'game_platform' => ['required', Rule::in(PlayerPlatformGameEnum::values())],
            'level_experience' => ['required', Rule::in(PlayerExperienceLevelEnum::cases())],
            'name' => 'required|string|min:3|max:255',
            'email' => ['required', 'email:rfc,dns', Rule::unique('users', 'email')->ignore($this->user_id)],
            'championship_team_name' => 'required|string|min:3|max:255',
        ];
    }

    public function setForm(Player $player)
    {
        $this->nickname = $player->nickname;
        $this->sex = $player->sex->value;
        $this->phone = $player->phone;
        $this->game_platform = $player->game_platform->value;
        $this->level_experience = $player->level_experience->value;
        $this->heart_team_name = $player->heart_team_name;
        $this->birth_dt = $player->birth_dt;

        $this->email = $player->user->email;
        $this->name = $player->user->name;

        $this->user_id = $player->user->id;
        $this->player_id = $player->id;

    }

}
