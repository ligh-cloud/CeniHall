<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class movie extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'duration',
        'min_age',
        'trailer',
        'type'
    ];
}
