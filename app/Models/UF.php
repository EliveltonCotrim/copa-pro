<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class UF extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ufs';

    protected $primaryKey = 'id';

    protected $fillable = [
        'state',
        'acronym',
    ];
}
