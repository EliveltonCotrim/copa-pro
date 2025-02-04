<?php

namespace App\Models;

use App\Enum\ChampionshipStatusEnum;
use App\Enum\PlayerExperienceLevelEnum;
use App\Enum\PlayerPlatformGameEnum;
use App\Enum\PlayerSexEnum;
use App\RoleEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Player extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nickname',
        'heart_team_name',
        'birth_dt',
        'sex',
        'phone',
        'bio',
        'status',
        'game_platform',
        'level_experience',
    ];

    protected $casts = [
        'sex' => PlayerSexEnum::class,
        'status' => PlayerSexEnum::class,
        'game_platform' => PlayerPlatformGameEnum::class,
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
            $query->where('status', ChampionshipStatusEnum::ACTIVE)->orWhere('status', ChampionshipStatusEnum::IN_PROGRESS);
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
