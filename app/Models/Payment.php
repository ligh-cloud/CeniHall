<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'amount',
        'payment_method',
    ];
    public function reservation(){
        $this->hasOne(Reservation::class);
    }
}
