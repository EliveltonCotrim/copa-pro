<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organizer extends Model
{
    use HasFactory, SoftDeletes;

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
