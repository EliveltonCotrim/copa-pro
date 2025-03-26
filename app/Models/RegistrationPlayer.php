<?php

namespace App\Models;

use App\Enum\PaymentStatusEnum;
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
        'championship_team_name',
        'player_id',
        'status',
        'payment_status',
    ];

    protected $casts = [
        'status' => RegistrationPlayerStatusEnum::class,
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
