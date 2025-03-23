<?php
namespace App\Services;
use App\Models\movie;
use App\Models\Siege;
use App\Models\User;
use App\Repositories\SiegeRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Repositories\MovieRepositoryInterface;
use Illuminate\Http\Request;


class SiegeService
{
    protected $siegeRepository;

    public function __construct(SiegeRepositoryInterface $siegeRepository)
    {
        $this->siegeRepository = $siegeRepository;
    }

    /**
     * Get all Sieges
     */
    public function getAllSieges()
    {
        return $this->siegeRepository->all();
    }

    /**
     * Get a single Siege by ID
     */
    public function getSiegeById($id)
    {
        return $this->siegeRepository->find($id);
    }

    /**
     * Add a new Siege
     */
    public function addSiege(Request $request)
    {
        $validatedData = $request->validate([
            'siege_number' => 'required|integer|max:100',
            'status' => 'required|string|in:available,reserved',
            'salle_id' => 'required|exists:salles,id'
        ]);

        return $this->siegeRepository->create($validatedData);
    }

    /**
     * Update an existing Siege
     */
    public function updateSiege($id, array $data)
    {
        $siege = $this->siegeRepository->find($id);
        if (!$siege) {
            return null;
        }

        $validatedData = validator($data, [
            'siege_number' => 'sometimes|integer|max:100',
            'status' => 'sometimes|string|in:available,reserved',
            'salle_id' => 'sometimes|exists:salles,id'
        ])->validate();

        return $this->siegeRepository->update($siege, $validatedData);
    }

    /**
     * Delete a Siege
     */
    public function deleteSiege($id)
    {
        $siege = $this->siegeRepository->find($id);
        if (!$siege) {
            return false;
        }

        return $this->siegeRepository->delete($siege);
    }
}
