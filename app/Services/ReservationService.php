<?php


namespace App\Services;

use App\Repositories\ReservationRepositoryInterface;
use Illuminate\Http\Request;

class ReservationService
{
    protected $reservationRepository;

    public function __construct(ReservationRepositoryInterface $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    /**
     * Get all reservations.
     */
    public function getAllReservations()
    {
        return $this->reservationRepository->all();
    }

    /**
     * Get a reservation by ID.
     */
    public function getReservationById($id)
    {
        return $this->reservationRepository->find($id);
    }

    /**
     * Create a new reservation.
     */
    public function createReservation(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'siege_id' => 'required|exists:salles,id',
            'seance_id' => 'required|exists:seances,id',
            'status' => 'required|boolean',
        ]);

        return $this->reservationRepository->create($data);
    }

    /**
     * Update an existing reservation.
     */
    public function updateReservation($id, array $data)
    {
        $reservation = $this->reservationRepository->find($id);
        if (!$reservation) {
            return null;
        }

        return $this->reservationRepository->update($reservation, $data);
    }

    /**
     * Delete a reservation.
     */
    public function deleteReservation($id)
    {
        $reservation = $this->reservationRepository->find($id);
        if (!$reservation) {
            return false;
        }

        return $this->reservationRepository->delete($reservation);
    }
}
