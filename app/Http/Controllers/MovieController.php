<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Services\MovieService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MovieController extends Controller
{
    protected $movieService;

    /**
     * MovieController constructor.
     *
     * @param MovieService $movieService
     */
    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movies = $this->movieService->getAllMovies();

        return response()->json([
            'status' => 'success',
            'data' => $movies
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // This method typically returns a view for web applications
        // For an API, this might not be needed
        return response()->json([
            'status' => 'success',
            'message' => 'Use POST to /movies to create a new movie'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $movie = $this->movieService->addMovie($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Movie created successfully',
                'data' => $movie
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Movie creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        return response()->json([
            'status' => 'success',
            'data' => $movie
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movie $movie)
    {
        // This method typically returns a view for web applications
        // For an API, this might not be needed
        return response()->json([
            'status' => 'success',
            'message' => 'Use PUT/PATCH to /movies/{id} to update a movie',
            'data' => $movie
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movie $movie)
    {
        try {
            $updated = $this->movieService->updateMovie($movie, $request->all());

            if (!$updated) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Movie update failed'
                ], 500);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Movie updated successfully',
                'data' => $movie->fresh()
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Movie update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function destroy(Movie $movie)
    {
        try {
            $deleted = $this->movieService->deleteMovie($movie);

            if (!$deleted) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Movie deletion failed'
                ], 500);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Movie deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Movie deletion failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function search(Request $request)
    {
        $query = $request->query('q');

        if (!$query) {
            return response()->json([
                'status' => 'error',
                'message' => 'Search query parameter (q) is required'
            ], 400);
        }

        $movies = $this->movieService->searchMovies($query);

        return response()->json([
            'status' => 'success',
            'data' => $movies
        ]);
    }


    public function byGenre(Request $request, $genre)
    {
        $movies = $this->movieService->getMoviesByGenre($genre);

        return response()->json([
            'status' => 'success',
            'data' => $movies
        ]);
    }


    public function newest(Request $request)
    {
        $limit = $request->query('limit', 10);
        $movies = $this->movieService->getNewestMovies($limit);

        return response()->json([
            'status' => 'success',
            'data' => $movies
        ]);
    }
    public function showAllMovies(){
        return $this->movieService->getAllMovies();
    }
}
