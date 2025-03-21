<?php

namespace App\Repositories;
use App\Models\movie;

class MovieReposetory implements MovieRepositoryInterface {
    public function all(){
        return response()->json(\App\Models\movie::all());
    }
    public function find($id)
    {
        return Movie::find($id);
    }

    public function create(array $data)
    {
        return Movie::create($data);
    }

    public function update(Movie $movie, array $data)
    {
        return $movie->update($data);
    }

    public function delete(Movie $movie)
    {
        return $movie->delete();
    }
    public function getAll()
    {
        return Movie::all();
    }

}
