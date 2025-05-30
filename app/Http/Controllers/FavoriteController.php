<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FavoriteController extends Controller
{
    public function list(Request $request): array
    {
        $favorites = Favorite::where('user_id', $request->user()->id)->get();

        $movies = [];
        foreach ($favorites as $favorite) {
            $response = Http::get("https://api.themoviedb.org/3/movie/$favorite->tmdb_id", [
                'api_key' => env('TMDB_API_KEY'),
                'language' => 'pt-BR'
            ]);

            if ($response->successful()) {
                $movies[] = $response->json();
            }
        }

        return [
            'favorites' => $movies,
        ];
    }

    public function store(Request $request): array
    {
        $favorite = Favorite::create([
            'user_id' => $request->user()->id,
            'tmdb_id' => $request->input('tmdb_id'),
        ]);

        $exists = Favorite::where('user_id', $request->user()->id)->where('tmdb_id', $request->input('tmdb_id'))->exists();

        if ($exists) {
            return [
                'message' => 'Filme já está nos favoritos',
                'favorite' => null,
            ];
        }

        return [
            'message' => 'Item adicionado aos favoritos com sucesso.',
            'favorite' => $favorite,
        ];
    }

    public function delete(Request $request, int $tmdb_id): array
    {
        $favorite = Favorite::where('user_id', $request->user()->id)
            ->where('tmdb_id', $tmdb_id)
            ->firstOrFail();

        $favorite->delete();

        return [
            'message' => 'Item  excluído dos favoritos com sucesso.',
        ];
    }
}
