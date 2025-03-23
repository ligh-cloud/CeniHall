<?php
namespace App\Repositories;

use App\Models\Siege;

interface SiegeRepositoryInterface
{
    public function all();

    public function find($id);
    public function create(array $data);
    public function update(Siege $siege, array $data);
    public function delete(Siege $siege);

}
