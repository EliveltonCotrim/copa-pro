<?php

namespace App\Models;

use App\Enum\PaymentCheckoutProviderEnum;
use Cache;
use Illuminate\Database\Eloquent\Model;

class PaymentGatewaySetting extends Model
{
    protected $fillable = [
        'provider',
        'public_key',
        'access_token',
        'webhook_token'
    ];

    protected $casts = [
        'provider' => PaymentCheckoutProviderEnum::class,
        'access_token' => 'encrypted',
        'webhook_token' => 'encrypted',
        'public_key' => 'encrypted',
    ];

    private const CACHE_KEY = 'payment_gateway.provider';

    protected static function booted(): void
    {
        static::saved(fn() => cache()->forget(self::CACHE_KEY));
        static::deleted(fn() => cache()->forget(self::CACHE_KEY));
    }

    protected static function currentProvider(): ?string
    {
        $value = Cache::rememberForever(
            self::CACHE_KEY,
            fn() => static::query()->first()?->provider->value
        );

        return $value ? PaymentCheckoutProviderEnum::tryFrom($value)->value : null;
    }
}
