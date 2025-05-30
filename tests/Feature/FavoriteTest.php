<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Favorite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_must_be_authenticated()
    {
        $response = $this->getJson('/api/favorite');

        $response->assertUnauthorized();
        $response->assertJson(['message' => 'Não autorizado. Um token de autenticação válido é necessário.']);
    }

    
    public function test_user_can_list_favorites()
    {
        Http::fake([
            'https://api.themoviedb.org/*' => Http::response([
                'id' => 1234,
                'title' => 'Filme Fake'
            ], 200),
        ]);

        $user = User::factory()->create();
        Favorite::factory()->count(2)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->getJson('/api/favorite');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'favorites');
    }

    public function test_user_cant_add_same_favorite()
    {
        $user = User::factory()->create();
        $favorite = Favorite::factory()->create(['user_id' => $user->id]);


        $response = $this->actingAs($user)->postJson('/api/favorite', ['tmdb_id' => $favorite->tmdb_id]);
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Filme já está nos favoritos',
            'favorite' => null
        ]);
    }

    public function test_user_can_add_favorites()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/favorite', ['tmdb_id' => 1234]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Item adicionado aos favoritos com sucesso.',
            'favorite' => [
                'user_id' => $user->id,
                'tmdb_id' => 1234,
            ],
        ]);
    }

    public function test_user_can_delete_favorites()
    {
        $user = User::factory()->create();
        $favorite = Favorite::factory()->create(['user_id' => $user->id, 'tmdb_id' => 12345]);

        $response = $this->actingAs($user)->deleteJson('/api/favorite/' . $favorite->tmdb_id);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Item  excluído dos favoritos com sucesso.']);

        $this->assertDatabaseMissing('favorite', ['id' => $favorite->id]);
    }
}