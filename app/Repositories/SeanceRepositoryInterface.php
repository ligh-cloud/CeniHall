<?php
namespace App\Repositories;
use App\Models\Seance;

interface SeanceRepositoryInterface{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update(Seance $seance, array $data);
    public function delete(Seance $seance);

}
