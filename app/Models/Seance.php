<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
    protected $fillable = [
        'salle_id',
        'movie_id',
        'start_time',
        'type',
        'language',
    ];
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }
}
