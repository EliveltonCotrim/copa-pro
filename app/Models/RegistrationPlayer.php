<?php

namespace App\Models;

use App\Enum\{PaymentStatusEnum, RegistrationPlayerStatusEnum};
use App\Observers\RegistrationPlayerObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

#[ObservedBy([RegistrationPlayerObserver::class])]
class RegistrationPlayer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'championship_id',
        'championship_team_name',
        'player_id',
        'status',
        'payment_status',
    ];

    protected $casts = [
        'status'         => RegistrationPlayerStatusEnum::class,
        'payment_status' => PaymentStatusEnum::class,
    ];

    public function championship(): BelongsTo
    {
        return $this->belongsTo(Championship::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
