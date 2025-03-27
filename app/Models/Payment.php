<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'amount',
        'reservation_id',
        'user_id'
    ];
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
