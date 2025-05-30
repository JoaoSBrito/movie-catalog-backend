<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_register_successfully()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);


        $response->assertStatus(201);
        $response->assertJsonStructure(['message','token']);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_user_register_fail_existing_email()
    {
        User::factory()->create(['email' => 'test@example.com']);

        $response = $this->postJson('/api/register', [
            'name' => 'Another User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422);
        $response->assertJson(['message' => 'O email já está em uso.']);
    }

    public function test_user_login_successfully()
    {
        $user = User::factory()->create(['password' => bcrypt('password123')]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
    }

    public function test_user_login_fail_wrong_password()
    {
        $user = User::factory()->create(['password' => bcrypt('password123')]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Credenciais inválidas.']);
    }

    public function test_user_logout_successfully()
    {
        $user = User::factory()->create();
        $token = $user->createToken('PersonalAccessToken')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->postJson('/api/logout');

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Logout realizado com sucesso.']);
    }

    public function test_get_authenticated_user()
    {
        $user = User::factory()->create();
        $token = $user->createToken('PersonalAccessToken')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->getJson('/api/user');

        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'name', 'email']);
        $response->assertJson(['id' => $user->id, 'name' => $user->name, 'email' => $user->email]);
    }
}
