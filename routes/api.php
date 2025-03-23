<?php

use App\Http\Controllers\MovieController;
use App\Http\Controllers\SiegeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

// For the welcome route
Route::get('/', function () {
    return view('welcome');
});

    Route::get('/movies', [\App\Http\Controllers\MovieController::class, 'showAllMovies']);

    Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);



Route::post('/addMovie', [\App\Http\Controllers\MovieController::class, 'store']);
Route::put('/movie/{movie}', [\App\Http\Controllers\MovieController::class, 'update']);


Route::prefix('movie')->group(function (){
    Route::get('/', [MovieController::class, 'index']);
    Route::get('/all', [MovieController::class, 'showAllMovies']);
    Route::post('/', [MovieController::class, 'store']);
    Route::get('/{movie}', [MovieController::class, 'show']);
    Route::put('/{movie}', [MovieController::class, 'update']);
    Route::patch('/{movie}', [MovieController::class, 'update']);
    Route::delete('/{movie}', [MovieController::class, 'destroy']);

    // Additional custom routes
    Route::get('/search', [MovieController::class, 'search']);
    Route::get('/genre/{genre}', [MovieController::class, 'byGenre']);
    Route::get('/newest', [MovieController::class, 'newest']);
});

Route::prefix('sieges')->group(function () {
    Route::get('/', [SiegeController::class, 'index']);
    Route::post('/', [SiegeController::class, 'store']);
    Route::get('/{id}', [SiegeController::class, 'show']);
    Route::put('/{id}', [SiegeController::class, 'update']);
    Route::patch('/{id}', [SiegeController::class, 'update']);
    Route::delete('/{id}', [SiegeController::class, 'destroy']);
});
