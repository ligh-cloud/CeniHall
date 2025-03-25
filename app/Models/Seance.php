<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
    protected $fillable = [
        'start_time',
        'type',
        'language',
    ];
    public function salle()
    {
        $this->hasOne(Salle::class);

    }
    public function movie()
    {
        $this->hasOne(Movie::class);

    }
}
