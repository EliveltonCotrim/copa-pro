<?php

namespace App\Livewire\Forms;

use App\Enum\{PlayerExperienceLevelEnum, PlayerPlatformGameEnum, PlayerSexEnum};
use App\Models\{Player, User};
use App\Services\PaymentGateway\Connectors\AsaasConnector;
use App\Services\PaymentGateway\Gateway;
use Illuminate\Support\Facades\{DB as DBTransaction, Hash};
use Illuminate\Validation\Rule;
use Livewire\Form;

class RegistrationPlayerForm extends Form
{
    // registration table
    public $championship_team_name = '';

    // player table
    public string $nickname = '';

    public string $heart_team_name = '';

    public string $birth_dt = '';

    public ?int $sex;

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
            'nickname'               => ['nullable', 'string', 'min:3', 'max:50', Rule::unique('players', 'nickname')->ignore($this->player_id)],
            'heart_team_name'        => 'nullable|string|max:255',
            'birth_dt'               => 'nullable|date|before_or_equal:today',
            'sex'                    => ['nullable', Rule::in(PlayerSexEnum::values())],
            'phone'                  => 'required|string|celular_com_ddd|max:20',
            'game_platform'          => ['required', Rule::in(PlayerPlatformGameEnum::values())],
            'level_experience'       => ['required', Rule::in(PlayerExperienceLevelEnum::cases())],
            'name'                   => ['required', 'string', 'regex:/^\S+\s+\S+/', 'max:255'],
            'email'                  => ['required', 'email:rfc,dns', Rule::unique('users', 'email')->ignore($this->user_id)],
            'championship_team_name' => 'required|string|min:3|max:255',
        ];
    }

    // public function messages()
    // {
    //     return [
    //         'nickname.unique' => 'Este apelido já está em uso.',
    //         'phone.celular_com_ddd' => 'O campo telefone não é um número de telefone válido.',
    //         'game_platform.required' => 'O campo plataforma de jogo é obrigatório.',
    //         'level_experience.required' => 'O campo nível de experiência é obrigatório.',
    //         'name.regex' => 'O campo nome deve conter pelo menos um sobrenome.',
    //         'email.unique' => 'Este e-mail já está em uso.',
    //     ];
    // }

    public function setForm(User $user)
    {
        $this->nickname = $user->userable->nickname;
        $this->sex      = $user->userable->sex->value ?? null;
        $this->phone    = '(77) 99151-3661';
        // $this->phone = $user->userable->phone;
        $this->game_platform    = $user->userable->game_platform->value;
        $this->level_experience = $user->userable->level_experience->value;
        $this->heart_team_name  = $user->userable->heart_team_name;
        $this->birth_dt         = $user->userable->birth_dt;

        $this->email = $user->email;
        $this->name  = $user->name;

        $this->user_id     = $user->id;
        $this->player_id   = $user->userable->id;
        $this->customer_id = $user->userable->customer_id;
    }

    public function saveSubscription()
    {
    }

    public function updatePlayer(Player $player): Player
    {
        $this->cpf_cnpj = clear_string($this->cpf_cnpj);

        $updatedPlayer = DBTransaction::transaction(function () use ($player) {
            $player->update(
                $this->except(['name', 'email', 'user_id', 'user_id'])
            );

            $player->user
                ->update([
                    $this->only(['name', 'email']),
                ]);

            return $player;
        });

        return $updatedPlayer;
    }

    public function createPlayer(): Player
    {
        $this->cpf_cnpj = clear_string($this->cpf_cnpj);

        $createdPlayer = DBTransaction::transaction(function () {
            $player = Player::create(
                $this->except(['name', 'email', 'user_id', 'user_id'])
            );

            $player->user()->create([
                'name'          => $this->name,
                'email'         => $this->email,
                'password'      => Hash::make('12345678'),
                'userable_type' => Player::class,
            ]);

            return $player;
        });

        return $createdPlayer;
    }

    public function createCustomerAsaas(): array
    {
        $adapter        = app(AsaasConnector::class);
        $gateway        = new Gateway($adapter);
        $this->cpf_cnpj = clear_string($this->cpf_cnpj);

        return $gateway->customer()->create([
            'name'    => $this->name,
            'email'   => $this->email,
            'phone'   => $this->phone,
            'cpfCnpj' => $this->cpf_cnpj,
        ]);
    }

    public function setArrayForm(array $form)
    {
        $this->nickname               = $form['nickname'] ?? null;
        $this->heart_team_name        = $form['heart_team_name'] ?? null;
        $this->birth_dt               = $form['birth_dt'] ?? null;
        $this->sex                    = $form['sex'] ?? null;
        $this->phone                  = $form['phone'];
        $this->game_platform          = $form['game_platform'];
        $this->level_experience       = $form['level_experience'];
        $this->cpf_cnpj               = $form['cpf_cnpj'];
        $this->name                   = $form['name'];
        $this->email                  = $form['email'];
        $this->user_id                = $form['user_id'];
        $this->player_id              = $form['player_id'];
        $this->customer_id            = $form['customer_id'];
        $this->championship_team_name = $form['championship_team_name'];
    }
}
