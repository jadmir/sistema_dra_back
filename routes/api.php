<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AgriSacaClaseController;
use App\Http\Controllers\AgriAnimalController;
use App\Http\Controllers\AgriDestinoController;
use App\Http\Controllers\AgriNatalidadMortalidadController;
use App\Http\Controllers\AgriVariedadController;
use App\Http\Controllers\AgriProductoController;
use App\Http\Controllers\AgriVariedadAnimalController;
use App\Http\Controllers\AgriRegistroPecuarioController;
use App\Http\Controllers\CultivoController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\ReporteCultivosController;
use App\Http\Controllers\SubGrupoController;
use App\Http\Controllers\SubSectorController;
use App\Http\Controllers\ReporteRegistroPecuarioController;
use App\Models\AgriVariedadAnimal;
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

// agri_saca_clases
Route::prefix('v1')->middleware(['auth.jwt'])->group(function () {
    Route::get('agri-saca-clases/search', [AgriSacaClaseController::class, 'search']);
    Route::apiResource('agri-saca-clases', AgriSacaClaseController::class);
});

// agri_variedades
Route::prefix('v1')->middleware(['auth.jwt'])->group(function () {
    Route::get('agri-variedades/search', [AgriVariedadController::class, 'search']);
    Route::apiResource('agri-variedades', AgriVariedadController::class);
});

// agri_productos
Route::prefix('v1')->middleware(['auth.jwt'])->group(function () {
    Route::get('agri-productos/search', [AgriProductoController::class, 'search']);
    Route::apiResource('agri-productos', AgriProductoController::class);
});

//agri_variedad_animal
Route::prefix('v1')->middleware(['auth.jwt'])->group(function () {
    Route::get('agri-variedad-animales/search', [AgriVariedadAnimalController::class, 'search']);
    Route::apiResource('agri-variedad-animales', AgriVariedadAnimalController::class);
});

//agri_animales
Route::prefix('v1')->middleware(['auth.jwt'])->group(function () {
    Route::get('agri-animales/search', [AgriAnimalController::class, 'search']);
    Route::apiResource('agri-animales', AgriAnimalController::class);
});

//agri_natalidad_mortalidad
Route::prefix('v1')->middleware(['auth.jwt'])->group(function () {
    Route::get('agri-natalidad-mortalidad/search', [AgriNatalidadMortalidadController::class, 'search']);
    Route::apiResource('agri-natalidad-mortalidad', AgriNatalidadMortalidadController::class);
});

Route::prefix('v1')->middleware(['auth.jwt'])->group(function () {
    //destinos
    Route::get('agri-destinos/search', [AgriDestinoController::class, 'search']);
    Route::apiResource('destinos', AgriDestinoController::class);
});

Route::prefix('v1')->middleware(['auth.jwt'])->group(function () {
    Route::get('agri-registros-pecuarios/search', [AgriRegistroPecuarioController::class, 'search']);
    Route::apiResource('agri-registros-pecuarios', AgriRegistroPecuarioController::class);
});

Route::prefix('v1')->middleware(['auth.jwt'])->group(function () {
   
    //subsectores
    Route::get('subsectores/search', [SubSectorController::class, 'search']);
    Route::apiResource('subsectores', SubSectorController::class);
    //grupos
    Route::get('grupos/search', [GrupoController::class, 'search']);
    Route::apiResource('grupos', GrupoController::class);

    //sub grupos
    Route::get('subgrupos/search', [SubGrupoController::class, 'search']);
    Route::apiResource('subgrupos', SubGrupoController::class);

    //cultivos
    Route::get('cultivos/search', [CultivoController::class, 'search']);
    Route::apiResource('cultivos', CultivoController::class);

    //reporte cultivos
    Route::get('reportes/cultivos/pdf', [ReporteCultivosController::class, 'pdf']);
    Route::get('reportes/cultivos/excel', [ReporteCultivosController::class, 'excel']);

    Route::get('reportes/registro-pecuario/pdf/{id}', [ReporteRegistroPecuarioController::class, 'pdf']);
    Route::get('exportar-natalidad-mortalidad', [AgriNatalidadMortalidadController::class, 'exportExcel']);
    Route::get('exportar-destinos', [AgriDestinoController::class, 'exportExcel']);
    Route::get('exportar-variedad-animal', [AgriVariedadAnimalController::class, 'exportExcel']);



});




