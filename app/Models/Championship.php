<?php

namespace App\Models;

use App\Enum\{ChampionshipFormatEnum, ChampionshipGamesEnum, ChampionshipStatusEnum, PlayerPlatformGameEnum};
use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasMany};
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\{HasMedia, InteractsWithMedia};

class Championship extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use SoftDeletes;

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

            $bannerPath     = $championship->getOriginal('banner_path');
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
        'status'              => ChampionshipStatusEnum::class,
        'game'                => ChampionshipGamesEnum::class,
        'game_platform'       => PlayerPlatformGameEnum::class,
    ];

    protected $appends = [
        'link_inscription',
        'start_date_formated',
        'end_date_formated',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($championship) {
            if (!$championship->uuid) {
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

    public function getFeeFormatedAttribute()
    {
        return Money::BRL($this->registration_fee);
    }

    public function startDateFormated(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => now()->parse($value)->format('d/m/Y H:i'),
        );
    }

    public function endDateFormated(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => now()->parse($value)->format('d/m/Y H:i'),
        );
    }

    public function linkInscription(): Attribute
    {
        return Attribute::make(
            get: fn ($value): string => route('championship.register', $this->slug),
        );
    }

    public function regulationPath(): Attribute
    {
        return Attribute::make(
            get: fn (?string $path): ?string => $path ? (str_contains($path, 'http')) ? $path : Storage::url($path) : '',
        );
    }
}
