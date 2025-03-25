<?php

namespace App\Http\Controllers;

use App\Models\seance;
use Illuminate\Http\Request;
use App\Services\SeanceService;
use Illuminate\Validation\ValidationException;

class SeanceController extends Controller
{
    protected $seanceService;

    /**
     * Inject the SeanceService.
     */
    public function __construct(SeanceService $seanceService)
    {
        $this->seanceService = $seanceService;
    }

    /**
     * Display a listing of all seances.
     */
    public function index()
    {
        $seances = $this->seanceService->getAllSeances();
        return response()->json(['status' => 'success', 'data' => $seances]);
    }

    /**
     * Store a newly created seance.
     */
    public function store(Request $request)
    {
        try {
            $seance = $this->seanceService->addSeance($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Seance created successfully',
                'data' => $seance
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Seance creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified seance.
     */
    public function show($id)
    {
        $seance = $this->seanceService->getSeanceById($id);

        if (!$seance) {
            return response()->json(['status' => 'error', 'message' => 'Seance not found'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $seance]);
    }

    /**
     * Update the specified seance.
     */
    public function update(Request $request, Seance $seance)
    {
        try {
            $updated = $this->seanceService->updateSeance($seance, $request->all());

            if (!$updated) {
                return response()->json(['status' => 'error', 'message' => 'Seance update failed'], 500);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Seance updated successfully',
                'data' => $seance->fresh()
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Seance update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified seance.
     */
    public function destroy(Seance $seance)
    {
        try {
            $deleted = $this->seanceService->deleteSeance($seance);

            if (!$deleted) {
                return response()->json(['status' => 'error', 'message' => 'Seance deletion failed'], 500);
            }

            return response()->json(['status' => 'success', 'message' => 'Seance deleted successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Seance deletion failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
