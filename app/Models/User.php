<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Observers\UserObserver;
use App\RoleEnum;
use Filament\Models\Contracts\{FilamentUser, HasAvatar};
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\{HasMedia, InteractsWithMedia};
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasAvatar, HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, SoftDeletes, HasRoles, InteractsWithMedia, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_url',
    ];

    protected static function booted()
    {
        parent::booted();

        static::created(function (User $user) {
            if ($user->userable_type == Player::class) {
                $user->assignRole(RoleEnum::PLAYER->value);
            }
        });

        static::observe(UserObserver::class);
    }

    public function getFilamentAvatarUrl(): ?string
    {
        if (empty($this->avatar_url)) {
            return null;
        }

        $path = $this->avatar_url;

        if (!str_starts_with($path, 'avatars/')) {
            $path = 'avatars/' . ltrim($path, '/');
        }

        return Storage::disk('public')->url($path);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole(RoleEnum::cases());
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function userable(): MorphTo
    {
        return $this->morphTo();
    }
}
