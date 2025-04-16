<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    protected $fillable = [
        'name',
        'type'
    ];


    public function seances()
    {
        return $this->hasMany(Seance::class);
    }

    public function sieges()
    {
        return $this->hasMany(Siege::class);
    }
}
