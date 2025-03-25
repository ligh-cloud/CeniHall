<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SalleService;
use App\Models\Salle;

class SalleController extends Controller
{
    protected $salleService;

    public function __construct(SalleService $salleService)
    {
        $this->salleService = $salleService;
    }

    public function index()
    {
        $salles = $this->salleService->getAllSalles();
        return response()->json($salles);
    }

    public function show($id)
    {
        $salle = $this->salleService->getSalleById($id);
        if (!$salle) {
            return response()->json(['message' => 'Salle not found.'], 404);
        }
        return response()->json($salle);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',

        ]);

        $salle = $this->salleService->addSalle($validated);
        return response()->json($salle, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer',
        ]);

        try {
            $salle = $this->salleService->updateSalle($id, $validated);
            return response()->json($salle);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $this->salleService->deleteSalle($id);
            return response()->json(['message' => 'Salle deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
