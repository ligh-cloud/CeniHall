<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'reservations';
    protected $fillable = [
        'user_id',
        'seance_id',
        'siege_id'
    ];

    public function user()
    {
        $this->belongsTo(User::class);
    }
}
