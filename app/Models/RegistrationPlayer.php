<?php

namespace App\Models;

use App\Enum\PlayerPlatformGameEnum;
use App\Enum\PlayerSexEnum;
use App\Enum\PlayerStatusEnum;
use App\Enum\RegistrationPlayerStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegistrationPlayer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'championship_id',
        'name',
        'email',
        'heart_team_name',
        'championship_team_name',
        'wpp_number',
        'birth_dt',
        'sex',
        'game_platform',
        'status',
    ];

    protected $casts = [
        'sex' => PlayerSexEnum::class,
        'game_platform' => PlayerPlatformGameEnum::class,
        'status' => RegistrationPlayerStatusEnum::class,
    ];

    public function championship(): BelongsTo
    {
        return $this->belongsTo(Championship::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
