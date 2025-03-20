<?php
namespace App\Repositories;

use App\Models\Movie;

interface MovieRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update(Movie $movie, array $data);
    public function delete(Movie $movie);

}
