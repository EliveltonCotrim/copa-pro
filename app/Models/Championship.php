<?php

namespace App\Models;

use App\Enum\{ChampionshipFormatEnum, ChampionshipGamesEnum, ChampionshipStatusEnum, PlayerPlatformGameEnum};
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasMany};
use Spatie\MediaLibrary\{HasMedia, InteractsWithMedia};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Championship extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'description',
        'start_date',
        'end_date',
        'registration_fee',
        'banner_path',
        'regulation_path',
        'game_platform',
        'game',
        'max_players',
        'championship_format',
        'wpp_group_link',
        'registration_link',
        'information',
        'status',
    ];

    protected static function booted()
    {
        static::deleted(function (Championship $championship) {
            $championship->players()->delete();
        });

        static::restored(function (Championship $championship) {
            $championship->players()->withTrashed()->restore();
        });

        static::forceDeleted(function (Championship $championship) {
            $championship->players()->forceDelete();

            Storage::disk('public')->delete($championship->banner_path);
            Storage::disk('public')->delete($championship->regulation_path);
        });

        static::updating(function (Championship $championship) {

            $bannerPath = $championship->getOriginal('banner_path');
            $regulationPath = $championship->getOriginal('regulation_path');

            if ($championship->banner_path !== $bannerPath && $bannerPath) {
                Storage::disk('public')->delete($bannerPath);
            }

            if ($championship->regulation_path !== $regulationPath && $regulationPath) {
                Storage::disk('public')->delete($regulationPath);
            }

            $championship->slug = Str::slug($championship->name);

        });

        static::creating(function (Championship $championship) {
            $championship->slug = Str::slug($championship->name);
        });
    }

    protected $casts = [
        'championship_format' => ChampionshipFormatEnum::class,
        'status' => ChampionshipStatusEnum::class,
        'game' => ChampionshipGamesEnum::class,
        'game_platform' => PlayerPlatformGameEnum::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($championship) {
            if (! $championship->uuid) {
                $championship->uuid = str::uuid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function address()
    {
        return $this->hasOne(Address::class);
    }

    public function registrationPlayers(): HasMany
    {
        return $this->hasMany(RegistrationPlayer::class, 'championship_id', 'id');
    }

    public function organizers(): BelongsToMany
    {
        return $this->belongsToMany(Organizer::class)->using(ChampionshipOrganization::class);
    }
}
