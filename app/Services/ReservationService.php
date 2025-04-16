<?php


namespace App\Services;

use App\Jobs\DeleteUnpaidReservation;
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
        // Validate the input data
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'siege_id' => 'required|exists:sieges,id', // changed from 'salles' to 'sieges'
            'seance_id' => 'required|exists:seances,id',
        ]);

        // Create the reservation
        $reservation = $this->reservationRepository->create($data);

        // Get related seance, movie, and salle information
        $seance = $reservation->seance;
        $movie = $seance->movie; // Retrieve the movie related to the seance
        $salle = $seance->salle; // Retrieve the salle related to the seance

        // Return reservation with movie and salle names
        return [
            'reservation' => $reservation,
            'movie_name' => $movie ? $movie->name : null,
            'salle_name' => $salle ? $salle->name : null,
        ];
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
