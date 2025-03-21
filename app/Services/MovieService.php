<?php

namespace App\Services;
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
    public function addMovie( $data)
    {
        $validate = Validator::make($data , [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|date_format:H:i:s',

            'movie_type' => 'required|string|max:100',
            'director' => 'required|string|max:255',
            'image' => 'nullable|string|url',
            'trailer' => 'nullable|string|url',
            'min_age' => 'integer|min:12|max:100'
        ]);
        if($validate->fails()){
            throw ValidationException::withMessages($validate->errors()->toArray());
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
