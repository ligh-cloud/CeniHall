<?php


namespace App\Services;

use App\Repositories\SeanceRepositoryInterface;
use App\Models\Seance;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class SeanceService
{
    protected $seanceRepository;

    /**
     * Inject the SeanceRepositoryInterface.
     */
    public function __construct(SeanceRepositoryInterface $seanceRepository)
    {
        $this->seanceRepository = $seanceRepository;
    }

    /**
     * Retrieve all seances.
     */
    public function getAllSeances()
    {
        return $this->seanceRepository->all();
    }

    /**
     * Find a specific seance by ID.
     */
    public function getSeanceById($id)
    {
        return $this->seanceRepository->find($id);
    }

    /**
     * Create a new seance.
     */
    public function addSeance(array $data)
    {
        $validator = Validator::make($data, [
            'movie_id' => 'required|exists:movies,id',
            'salle_id' => 'required|exists:salles,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->seanceRepository->create($data);
    }

    /**
     * Update an existing seance.
     */
    public function updateSeance(Seance $seance, array $data)
    {
        $validator = Validator::make($data, [
            'movie_id' => 'sometimes|exists:movies,id',
            'salle_id' => 'sometimes|exists:salles,id',
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|date|after:start_time',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->seanceRepository->update($seance, $data);
    }

    /**
     * Delete a seance.
     */
    public function deleteSeance(Seance $seance)
    {
        return $this->seanceRepository->delete($seance);
    }
}
