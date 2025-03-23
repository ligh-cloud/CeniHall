<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siege extends Model
{
    protected $fillable = [
        'status',
        'siege_number'
    ];


    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
    public function salle(){
        return $this->belongsTo(Salle::class);
    }
}
