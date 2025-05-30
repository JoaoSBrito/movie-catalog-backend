<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        try {
                $request->validate([
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|unique:users,email',  
                    'password' => 'required|string|min:8|confirmed', 
                ], [
                    'email.unique' => 'O email já está em uso.',
                    'password.confirmed' => 'As senhas não coincidem.',
                    'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
                ]);

                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                $token = $user->createToken('PersonalAccessToken')->plainTextToken;

                return response()->json([
                    'message' => 'Usuário cadastrado com sucesso!', 
                    'token' => $token
                ], 201);

            } catch (\Illuminate\Validation\ValidationException $e) {
                $err = collect($e->errors())->flatten()->first();

                return response()->json([
                    'message' =>  $err ?? 'Erro no cadastro',
                ], 422);
            }
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Credenciais inválidas.'
            ], 401);
        }

        // Gerar um token de acesso
        $token = $user->createToken('PersonalAccessToken')->plainTextToken;

        return response()->json(['token' => $token]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout realizado com sucesso.'
        ]);
    }

    public function user(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}
