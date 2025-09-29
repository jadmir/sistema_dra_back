<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/prueba', function (Request $request) {
    return response()->json(['message' => 'API is working']);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/refresh', [AuthController::class, 'refresh']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('jwt')->group(function () {
    Route::get('/perfil', [AuthController::class, 'perfil']);
});
