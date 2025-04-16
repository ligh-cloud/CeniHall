<?php

namespace App\Http\Controllers;

use App\Jobs\DeleteUnpaidReservation;
use App\Services\ReservationService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{

    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    /**
     * Display a listing of all reservations.
     */
    public function index()
    {
        return response()->json($this->reservationService->getAllReservations());
    }

    /**
     * Store a newly created reservation.
     */
    public function store(Request $request)
    {
        try {

            $reservation = $this->reservationService->createReservation($request);

            // Dispatch job to delete unpaid reservation
            DeleteUnpaidReservation::dispatch($reservation['reservation']->id)
                ->onQueue('delete_reservation')
                ->delay(now()->addMinute(1));


            return response()->json([
                'message' => 'Reservation created successfully',
                'reservation' => $reservation['reservation'],
                'movie_name' => $reservation['movie_name'],
                'salle_name' => $reservation['salle_name'],
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create reservation', 'error' => $e->getMessage()], 400);
        }
    }


    /**
     * Display the specified reservation.
     */
    public function show($id)
    {
        // Get the reservation by its ID
        $reservation = $this->reservationService->getReservationById($id);
        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }


        $seance = $reservation->seance;
        $movie = $seance->movie;
        $salle = $seance->salle;


        return response()->json([
            'reservation' => $reservation,
            'movie_name' => $movie ? $movie->name : null,
            'salle_name' => $salle ? $salle->name : null,
        ]);
    }

    /**
     * Update the specified reservation.
     */
    public function update(Request $request, $id)
    {
        $reservation = $this->reservationService->updateReservation($id, $request->all());
        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }
        return response()->json(['message' => 'Reservation updated successfully', 'reservation' => $reservation]);
    }

    /**
     * Remove the specified reservation.
     */
    public function destroy($id)
    {
        $deleted = $this->reservationService->deleteReservation($id);
        if (!$deleted) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }
        return response()->json(['message' => 'Reservation deleted successfully']);
    }


}
