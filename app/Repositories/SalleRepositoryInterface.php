<?php


namespace App\Repositories;
use App\Models\Salle;

interface SalleRepositoryInterface
{
    public function all();

    public function find($id);
    public function create(array $data);
    public function delete(Salle $salle);
    public function update(Salle $salle , array $data);

}
