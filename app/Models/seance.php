<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class seance extends Model
{
    protected $fillable = [
        'start_time',
        'type',
        'language',
    ];
}
