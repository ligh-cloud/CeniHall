<?php


namespace App\Repositories;

use App\Models\Seance;

class SeanceRepository implements SeanceRepositoryInterface
{
    /**
     * Retrieve all seances.
     */
    public function all()
    {
        return Seance::with(['movie', 'salle'])->get();
    }

    /**
     * Find a specific seance by ID.
     */
    public function find($id)
    {
        return Seance::with(['movie', 'salle'])->find($id);
    }

    /**
     * Create a new seance.
     */
    public function create(array $data)
    {
        return Seance::create($data);
    }

    /**
     * Update an existing seance.
     */
    public function update(Seance $seance, array $data)
    {
        return $seance->update($data);
    }

    /**
     * Delete a seance.
     */
    public function delete(Seance $seance)
    {
        return $seance->delete();
    }
}
