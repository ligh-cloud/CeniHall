<?php

namespace App\Http\Controllers;

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
        $reservation = $this->reservationService->createReservation($request);
        return response()->json(['message' => 'Reservation created successfully', 'reservation' => $reservation], 201);
    }

    /**
     * Display the specified reservation.
     */
    public function show($id)
    {
        $reservation = $this->reservationService->getReservationById($id);
        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }
        return response()->json($reservation);
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
