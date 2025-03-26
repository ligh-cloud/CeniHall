<?php

namespace App\Jobs;

use App\Models\Reservation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteUnpaidReservation implements ShouldQueue
{
    use Queueable, InteractsWithQueue, Queueable, SerializesModels;

    protected $reservationId;

    /**
     * Create a new job instance.
     */
    public function __construct($reservationId)
    {
        $this->reservationId = $reservationId;
    }


    public function handle(): void
    {
        // Use find() correctly
        $reservation = Reservation::find($this->reservationId);

        // More explicit status check
        if ($reservation && $reservation->status === false) {
            $reservation->delete();
        }
    }
}
