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
        return $this->belongsTo(User::class);
    }

    public function siege()
    {
        return $this->belongsTo(Siege::class);
    }

    public function seance()
    {
        return $this->belongsTo(Seance::class);
    }


}
