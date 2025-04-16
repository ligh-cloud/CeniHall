<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

Route::get('/login' , function (){
    return view('auth');
});
Route::get('/' , function (){
    return view('auth');
});
Route::get('/' , function (){
    return view('auth');
});
Route::get('/post' , function (){
    return view('posts');
});
Route::get('/salle' , function (){
    return view('salle');
});

Route::get("/test" , function (){
    return view('test');
});
Route::get("/seances" , function (){
    return view('sceance');
});
