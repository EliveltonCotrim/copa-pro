<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UF extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ufs';

    protected $primaryKey = 'id';

    protected $fillable = [
        'state',
        'acronym',
    ];
}
