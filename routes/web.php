<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

// For the welcome route
Route::get('/', function () {
    return view('welcome');
});

Route::middleware('api')->group(function (){
    Route::get('/movies' , [\App\Http\Controllers\MovieController::class , 'showAllMovies']);



});

Route::post('/addMovie', [\App\Http\Controllers\MovieController::class, 'store'])
    ->middleware(\Illuminate\Routing\Middleware\SubstituteBindings::class)
    ->withoutMiddleware(VerifyCsrfToken::class);
Route::put('/movie/{movie}' , [\App\Http\Controllers\MovieController::class , 'update'])
    ->middleware(\Illuminate\Routing\Middleware\SubstituteBindings::class)
    ->withoutMiddleware(VerifyCsrfToken::class);
