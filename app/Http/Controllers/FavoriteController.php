<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function list(Request $request): array
    {
        $favorites = Favorite::where('user_id', $request->user()->id)->get();

        return [
            'favorites' => $favorites,
        ];
    }

    public function store(Request $request): array
    {
        $favorite = Favorite::create([
            'user_id' => $request->user()->id,
            'movie_id' => $request->input('movie_id'),
        ]);

        return [
            'message' => 'Item adicionado aos favoritos com sucesso.',
            'favorites' => $favorite,
        ];
    }

    public function delete(Request $request, int $movie_id): array
    {
        $favorite = Favorite::where('user_id', $request->user()->id)
            ->where('movie_id', $movie_id)
            ->firstOrFail();

        $favorite->delete();

        return [
            'message' => 'Item  excluído dos favoritos com sucesso.',
        ];
    }
}
