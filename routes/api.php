<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// 👇 RUTAS PÚBLICAS (No requieren Token)
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1');

// 👇 RUTAS PROTEGIDAS (Requieren Token de Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    
    // Usuario autenticado y Logout
    Route::get('/user', function (Request $request) {
        return $request->user()->load('teams');
    });
    Route::post('/logout', [AuthController::class, 'logout']);
});