<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AgriSacaClaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/prueba', fn () => response()->json(['message' => 'API is working']));

// Público (sin JWT)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/refresh', [AuthController::class, 'refresh']);
Route::post('/logout', [AuthController::class, 'logout']);

// Protegido (con JWT)
Route::middleware('auth.jwt')->group(function () {
    Route::get('/perfil', [AuthController::class, 'perfil']);
});

// v1 SOLO store
Route::prefix('v1')->middleware(['auth.jwt'])->group(function () {
    // Búsqueda primero
    Route::get('usuarios/search', [UsuarioController::class, 'search']);

    // Listado
    Route::get('usuarios', [UsuarioController::class, 'index']);

    // Crear
    Route::post('usuarios', [UsuarioController::class, 'store'])->middleware('role:Administrador');

    // Mostrar (restringido a números)
    Route::get('usuarios/{usuario}', [UsuarioController::class, 'show'])
        ->whereNumber('usuario');

    // Actualizar
    Route::put('usuarios/{usuario}', [UsuarioController::class, 'update'])
        ->whereNumber('usuario')
        ->middleware('role:Administrador');

    // Eliminar (desactivar)
    Route::delete('usuarios/{usuario}', [UsuarioController::class, 'destroy'])
        ->whereNumber('usuario')
        ->middleware('role:Administrador');

    Route::put('perfil/password', [UsuarioController::class, 'cambiarPassword']);
});

Route::prefix('v1')->middleware(['auth.jwt'])->group(function () {
    //Roles
    Route::apiResource('roles', RolController::class);
    Route::post('roles/{id}/permisos', [RolController::class, 'asignarPermisos']);

    // Permisos
    Route::apiResource('permisos', PermisoController::class);
});

Route::prefix('v1')->middleware(['auth.jwt'])->group(function () {
    Route::get('saca_clases/search', [AgriSacaClaseController::class, 'search']);
    Route::get('saca_clases', [AgriSacaClaseController::class, 'index']);
    Route::post('saca_clases', [AgriSacaClaseController::class, 'store']);
    Route::get('saca_clases/{id}', [AgriSacaClaseController::class, 'show']);
    Route::put('saca_clases/{id}', [AgriSacaClaseController::class, 'update']);
    Route::delete('saca_clases/{id}', [AgriSacaClaseController::class, 'destroy']);
});
