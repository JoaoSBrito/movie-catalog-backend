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
            'favorite' => $favorites,
        ];
    }

    public function store(Request $request): array
    {
        $favorite = Favorite::create([
            'user_id' => $request->user()->id,
            'tmdb_id' => $request->input('tmdb_id'),
        ]);

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
