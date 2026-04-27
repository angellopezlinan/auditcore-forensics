<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Iniciar Sesion (Obtener Token)
     *
     * Autentica a un usuario y devuelve un token Bearer (Sanctum) para interactuar con la API.
     *
     * @unauthenticated
     * @bodyParam email string required Correo electronico del usuario. Example: jefe@cordoba.es
     * @bodyParam password string required Contrasena del usuario. Example: 123456
     *
     * @response 200 {"access_token": "1|token...", "token_type": "Bearer", "user": {"id": 1, "name": "Jefe Policia"}}
     * @response 401 {"message": "Credenciales incorrectas"}
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Credenciales incorrectas',
            ], 401);
        }

        $user = Auth::user();

        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user->load('teams'),
        ]);
    }

    /**
     * Cerrar Sesion
     *
     * Revoca el token de acceso actual del usuario.
     *
     * @authenticated
     */
    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken();

        if ($token) {
            $token->delete();
        } else {
            $request->user()->tokens()->delete();
        }

        return response()->json([
            'message' => 'Sesion cerrada correctamente',
        ]);
    }
}
