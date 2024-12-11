<?php

namespace App\Models;

use App\Enum\PaymentMethodEnum;
use App\Enum\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaction_id',
        'player_id',
        'method',
        'status',
        'installments',
        'approved_at',
        'qr_code_64',
        'qr_code',
        'ticket_url',
    ];

    protected $casts = [
        "method" => PaymentMethodEnum::class,
        "status" => PaymentStatusEnum::class
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
