<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Address extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'championship_id',
        'postal_code',
        'state',
        'city',
        'neighborhood',
        'street',
        'number',
        'complement',
    ];

    public function championship(): BelongsTo
    {
        return $this->belongsTo(Championship::class);
    }
}
