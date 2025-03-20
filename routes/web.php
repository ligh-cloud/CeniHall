<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

// For the welcome route
Route::get('/', function () {
    return view('welcome');
});

// For the register route without CSRF protection
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])
    ->middleware(\Illuminate\Routing\Middleware\SubstituteBindings::class)
    ->withoutMiddleware(VerifyCsrfToken::class);

// For API routes group
Route::middleware(['throttle:api'])
    ->prefix('api')
    ->group(function () {
        // Your API routes here
        // These routes will automatically bypass CSRF verification
    });
