<?php

namespace App\Http\Controllers;

use App\Models\Siege;
use App\Services\SiegeService;
use Illuminate\Http\Request;

class SiegeController extends Controller
{
    protected $siegeService;

    public function __construct(SiegeService $siegeService)
    {
        $this->siegeService = $siegeService;
    }

    /**
     * Display a listing of all Sieges.
     */
    public function index()
    {
        return response()->json($this->siegeService->getAllSieges());
    }

    /**
     * Store a newly created Siege in storage.
     */
    public function store(Request $request)
    {
        $siege = $this->siegeService->addSiege($request);
        return response()->json(['message' => 'Siege created successfully', 'siege' => $siege], 201);
    }

    /**
     * Display the specified Siege.
     */
    public function show($id)
    {
        $siege = $this->siegeService->getSiegeById($id);
        if (!$siege) {
            return response()->json(['message' => 'Siege not found'], 404);
        }
        return response()->json($siege);
    }

    /**
     * Update the specified Siege.
     */
    public function update(Request $request, $id)
    {
        $siege = $this->siegeService->updateSiege($id, $request->all());
        if (!$siege) {
            return response()->json(['message' => 'Siege not found'], 404);
        }
        return response()->json(['message' => 'Siege updated successfully', 'siege' => $siege]);
    }

    /**
     * Remove the specified Siege.
     */
    public function destroy($id)
    {
        $deleted = $this->siegeService->deleteSiege($id);
        if (!$deleted) {
            return response()->json(['message' => 'Siege not found'], 404);
        }
        return response()->json(['message' => 'Siege deleted successfully']);
    }
}
