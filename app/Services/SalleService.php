<?php

namespace App\Services;

use App\Repositories\SalleRepositoryInterface;
use App\Models\Salle;

class SalleService
{
    protected $salleRepository;

    public function __construct(SalleRepositoryInterface $salleRepository)
    {
        $this->salleRepository = $salleRepository;
    }

    /**
     * Add a new salle.
     */
    public function addSalle(array $data)
    {
        return $this->salleRepository->create($data);
    }

    /**
     * Retrieve all salles.
     */
    public function getAllSalles()
    {
        return $this->salleRepository->all();
    }

    /**
     * Find a salle by its ID.
     */
    public function getSalleById($id)
    {
        return $this->salleRepository->find($id);
    }

    /**
     * Update an existing salle.
     */
    public function updateSalle($id, array $data)
    {
        $salle = $this->salleRepository->find($id);
        if (!$salle) {
            throw new \Exception("Salle not found.");
        }

        return $this->salleRepository->update($salle, $data);
    }

    /**
     * Delete a salle.
     */
    public function deleteSalle($id)
    {
        $salle = $this->salleRepository->find($id);
        if (!$salle) {
            throw new \Exception("Salle not found.");
        }

        return $this->salleRepository->delete($salle);
    }
}
