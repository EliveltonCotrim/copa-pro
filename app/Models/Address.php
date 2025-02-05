<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'championship_id',
        'street',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'country',
    ];

    public function championship(): BelongsTo
    {
        return $this->belongsTo(Championship::class);
    }
}
