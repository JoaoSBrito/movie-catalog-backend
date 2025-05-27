<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TMDBController;

// AUTH Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// TMDB API Routes
Route::get('/movie/popular', 
    [TMDBController::class, 'popular']
);

Route::get('/movie/upcoming', 
    [TMDBController::class, 'upcoming']
);

Route::get('/movie/toprated', 
    [TMDBController::class, 'toprated']
);

Route::get('/movie/search', 
    [TMDBController::class, 'search']
);

// Favorite Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/favorite', [\App\Http\Controllers\FavoriteController::class, 'list']);
    Route::post('/favorite', [\App\Http\Controllers\FavoriteController::class, 'store']);
    Route::delete('/favorite/{id}', [\App\Http\Controllers\FavoriteController::class, 'delete']);
});
