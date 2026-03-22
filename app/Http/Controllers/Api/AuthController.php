<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Iniciar Sesión (Obtener Token)
     *
     * Autentica a un usuario y devuelve un token Bearer (Sanctum) para interactuar con la API.
     *
     * @unauthenticated
     * @bodyParam email string required Correo electrónico del usuario. Example: jefe@cordoba.es
     * @bodyParam password string required Contraseña del usuario. Example: 123456
     * 
     * @response 200 {"access_token": "1|token...", "token_type": "Bearer", "user": {"id": 1, "name": "Jefe Policia"}}
     * @response 401 {"message": "Credenciales incorrectas"}
     */
    public function login(Request $request)
    {
        // 1. Validar las credenciales enviadas
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // 2. Comprobar si las credenciales son correctas y existe el usuario
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        // 3. Obtener el usuario autenticado
        $user = Auth::user();

        // 4. Crear el token de acceso (Revocamos los anteriores si queremos, opcional)
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        // 5. Devolver la respuesta con el token y datos del usuario y su organización
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user->load('teams')
        ]);
    }

    /**
     * Cerrar Sesión
     *
     * Revoca el token de acceso actual del usuario.
     *
     * @authenticated
     */
    public function logout(Request $request)
    {
        // Revocar el token con el que el usuario hizo la petición actual
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ]);
    }
}
