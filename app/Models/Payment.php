<?php

namespace App\Models;

use App\Enum\{PaymentMethodEnum, PaymentStatusEnum};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Payment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'registration_player_id',
        'transaction_id',
        'value',
        'net_value',
        'billing_type',
        'description',
        'player_id',
        'installments',
        'date_created',
        'due_date',
        'payment_Date',
        'confirmed_date',
        'status',
        'qr_code_64',
        'qr_code',
        'ticket_url',
        'transaction_receipt_url',
    ];

    protected $casts = [
        'billing_type' => PaymentMethodEnum::class,
        'status'       => PaymentStatusEnum::class,
    ];

    public function registrationPlayer(): BelongsTo
    {
        return $this->belongsTo(RegistrationPlayer::class, 'registration_player_id');
    }
}
