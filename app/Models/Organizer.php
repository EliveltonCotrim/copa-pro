<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, MorphOne};
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Organizer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'descrition',
    ];

    public function championships(): BelongsToMany
    {
        return $this->belongsToMany(Championship::class)->using(ChampionshipOrganization::class);
    }

    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }
}
