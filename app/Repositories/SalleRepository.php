<?php

namespace App\Repositories;

use App\Models\Salle;
use App\Repositories\SalleRepositoryInterface;

class SalleRepository implements SalleRepositoryInterface
{
    /**
     * Retrieve all salles.
     */
    public function all()
    {
        return Salle::all();
    }

    /**
     * Find a salle by its ID.
     */
    public function find($id)
    {
        return Salle::find($id);
    }

    /**
     * Create a new salle.
     */
    public function create(array $data)
    {
        return Salle::create($data);
    }

    /**
     * Delete a salle.
     */
    public function delete(Salle $salle)
    {
        return $salle->delete();
    }

    /**
     * Update an existing salle.
     */
    public function update(Salle $salle, array $data)
    {
        return $salle->update($data);
    }
}
