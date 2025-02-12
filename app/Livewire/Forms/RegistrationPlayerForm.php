<?php

namespace App\Livewire\Forms;

use App\Enum\PlayerExperienceLevelEnum;
use App\Enum\PlayerPlatformGameEnum;
use App\Enum\PlayerSexEnum;
use App\Models\Player;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB as DBTransaction;

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
    public string $cpf_cnpj = '';

    // user table
    public $name = '';
    public $email = '';

    public $user_id;
    public $player_id;
    public ?string $customer_id = null;
    public ?string $verification_code = null;



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
            'name' => ['required', 'string', 'regex:/^\S+\s+\S+/', 'max:255'],
            'email' => ['required', 'email:rfc,dns', Rule::unique('users', 'email')->ignore($this->user_id)],
            'championship_team_name' => 'required|string|min:3|max:255',
        ];
    }

    public function messages()
    {
        return [
            'nickname.unique' => 'Este apelido já está em uso.',
            'phone.celular_com_ddd' => 'O campo telefone não é um número de telefone válido.',
            'game_platform.required' => 'O campo plataforma de jogo é obrigatório.',
            'level_experience.required' => 'O campo nível de experiência é obrigatório.',
            'name.regex' => 'O campo nome deve conter pelo menos um sobrenome.',
            'email.unique' => 'Este e-mail já está em uso.',
        ];
    }

    public function setForm(Player $player)
    {
        $this->nickname = $player->nickname;
        $this->sex = $player->sex->value;
        $this->phone = '(77) 99151-3661';
        // $this->phone = $player->phone;
        $this->game_platform = $player->game_platform->value;
        $this->level_experience = $player->level_experience->value;
        $this->heart_team_name = $player->heart_team_name;
        $this->birth_dt = $player->birth_dt;

        $this->email = $player->user->email;
        $this->name = $player->user->name;

        $this->user_id = $player->user->id;
        $this->player_id = $player->id;
        $this->customer_id = $player->customer_id;
    }

    public function updatePlayer(Player $player): Player
    {
        $updatedPlayer = DBTransaction::transaction(function () use ($player) {
            $player->update(
                $this->except(['name', 'email', 'user_id', 'user_id'])
            );

            $player->user
                ->update([
                    $this->only(['name', 'email'])
                ]);

            return $player;
        });

        return $updatedPlayer;
    }

    public function createPlayer(): Player
    {
        $createdPlayer = DBTransaction::transaction(function () {
            $player = Player::create(
                $this->except(['name', 'email', 'user_id', 'user_id'])
            );

            $player->user()->create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make('12345678'),
                'userable_type' => Player::class
            ]);

            return $player;
        });

        return $createdPlayer;
    }

}
