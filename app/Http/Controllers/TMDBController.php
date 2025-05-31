<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class TMDBController extends Controller
{
    public function popular(): array
    {
        $response = Http::get("https://api.themoviedb.org/3/movie/popular", [
            'api_key' => env('TMDB_API_KEY'),
            'language' => 'pt-BR'
        ]);

        return $response->json();
    }

    public function upcoming(): array
    {
        $response = Http::get("https://api.themoviedb.org/3/movie/upcoming", [
            'api_key' => env('TMDB_API_KEY'),
            'language' => 'pt-BR'
        ]);

        return $response->json();
    }

    public function toprated(): array
    {
        $response = Http::get("https://api.themoviedb.org/3/movie/top_rated", [
            'api_key' => env('TMDB_API_KEY'),
            'language' => 'pt-BR'
        ]);

        return $response->json();
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $response = Http::get("https://api.themoviedb.org/3/search/movie", [
            'api_key' => env('TMDB_API_KEY'),
            'query' => $query,
            'language' => 'pt-BR'
        ]);

        return $response->json();
    }

    public function genres()
    {
        $response = Http::get("https://api.themoviedb.org/3/genre/movie/list", [
            'api_key' => env('TMDB_API_KEY'),
            'language' => 'pt-BR'
        ]);

        return $response->json();
    }

}
