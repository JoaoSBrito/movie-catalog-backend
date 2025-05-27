<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TMDBController;

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