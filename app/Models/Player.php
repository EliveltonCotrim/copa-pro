<?php

namespace App\Models;

use App\Enum\{ChampionshipStatusEnum, PlayerExperienceLevelEnum, PlayerPlatformGameEnum, PlayerSexEnum, PlayerStatusEnum};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{HasMany, MorphOne};
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};

class Player extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nickname',
        'customer_id',
        'heart_team_name',
        'birth_dt',
        'sex',
        'phone',
        'bio',
        'status',
        'game_platform',
        'level_experience',
        'cpf_cnpj',
    ];

    protected $casts = [
        'sex'              => PlayerSexEnum::class,
        'status'           => PlayerStatusEnum::class,
        'game_platform'    => PlayerPlatformGameEnum::class,
        'level_experience' => PlayerExperienceLevelEnum::class,
    ];

    protected static function booted()
    {
        parent::booted();

        static::deleting(function (Player $player) {
            $player->user()->delete();
            $player->registrationsChampionships()->delete();
        });

        static::restored(function (Player $player) {
            $player->user()->restore();
            $player->registrationsChampionships()->withTrashed()->restore();

        });

        static::forceDeleted(function (Player $player) {
            $player->registrationsChampionships()->forceDelete();
            $player->user()->forceDelete();
        });
    }

    public function hasActiveChampionships(): bool
    {
        $exists = $this->registrationsChampionships()->whereHas('championship', function (Builder $query) {
            $query->where('status', ChampionshipStatusEnum::REGISTRATION_OPEN)
                ->orWhere('status', ChampionshipStatusEnum::IN_PROGRESS)
                ->orWhere('status', ChampionshipStatusEnum::ON_HOLD);
        })->exists();

        return $exists;
    }

    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function registrationsChampionships(): HasMany
    {
        return $this->hasMany(RegistrationPlayer::class, 'player_id', 'id');
    }
}
