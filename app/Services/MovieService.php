<?php

use App\Models\movie;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Repositories\MovieRepositoryInterface;

class MovieService
{
    protected $movierepository;
    public function __construct(MovieRepositoryInterface $movieRepository)
    {
        $this->movierepository = $movieRepository;
    }
    public function addMovie(Movie $movie , $data)
    {
        $validate = Validator::make($data , [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer|min:1',
            'release_date' => 'required|date',
            'genre' => 'required|string|max:100',
            'director' => 'required|string|max:255',
            'poster_url' => 'nullable|string|url',
            'trailer_url' => 'nullable|string|url',
        ]);
        if($validate->fails()){
            throw new \Nette\Schema\ValidationException($validate);
        }
        return $this->movierepository->create($data);
    }
    public function getAllMovies()
    {
        return $this->movierepository->getAll();
    }

    public function getMovieById(int $id)
    {
        return $this->movierepository->findById($id);
    }


    public function updateMovie(Movie $movie, array $data)
    {
        $validator = Validator::make($data, [
            'title' => 'string|max:255',
            'description' => 'string',
            'duration' => 'integer|min:1',
            'release_date' => 'date',
            'genre' => 'string|max:100',
            'director' => 'string|max:255',
            'poster_url' => 'nullable|string|url',
            'trailer_url' => 'nullable|string|url',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->movierepository->update($movie, $data);
    }


    public function deleteMovie(Movie $movie)
    {
        return $this->movierepository->delete($movie);
    }












}
