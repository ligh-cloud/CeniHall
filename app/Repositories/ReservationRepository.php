<?php


namespace App\Repositories;

use App\Models\Reservation;
use App\Repositories\ReservationRepositoryInterface;

class ReservationRepository implements ReservationRepositoryInterface
{
    /**
     * Get all reservations.
     */
    public function all()
    {
        return Reservation::with(['user', 'siege', 'seance'])->get();
    }

    /**
     * Find a reservation by its ID.
     */
    public function find($id)
    {
        return Reservation::with(['user', 'siege', 'seance'])->findOrFail($id);
    }

    /**
     * Create a new reservation.
     */
    public function create(array $data)
    {
        return Reservation::create($data);
    }

    /**
     * Delete a reservation.
     */
    public function delete(Reservation $reservation)
    {
        return $reservation->delete();
    }

    /**
     * Update an existing reservation.
     */
    public function update(Reservation $reservation, array $data)
    {
        return $reservation::update($data);
    }
}
