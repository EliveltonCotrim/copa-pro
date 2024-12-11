<?php

namespace App\Models;

use App\Enum\ChampionshipFormatEnum;
use App\Enum\ChampionshipStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Championship extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'registration_fee',
        'banner_path',
        'regulation_path',
        'game_platform',
        'max_playes',
        'championship_format',
        'wpp_group_link',
        'registration_link',
        'information',
        'status',
    ];

    protected $casts = [
        'championship_format' => ChampionshipFormatEnum::class,
        'status' => ChampionshipStatusEnum::class,
    ];

    public function addres()
    {
        return $this->hasOne(Addres::class);
    }

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    public function organizers(): BelongsToMany
    {
        return $this->belongsToMany(Organizer::class)->using(ChampionshipOrganization::class);
    }
}
