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
use App\Http\Controllers\CultivoController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\ReporteCultivosController;
use App\Http\Controllers\SubGrupoController;
use App\Http\Controllers\SubSectorController;
use App\Http\Controllers\Api\PreGeoUbicacionController;
use App\Http\Controllers\Api\PreCategoriaController;
use App\Http\Controllers\Api\PreProductoController;
use App\Http\Controllers\Api\PreMercadoController;
use App\Http\Controllers\Api\PreEncuestadorController;
use App\Http\Controllers\Api\PreMuestraController;
use App\Http\Controllers\Api\PreReporteController;
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
    Route::put('/perfil', [AuthController::class, 'actualizarPerfil']);
    Route::put('/perfil/password', [UsuarioController::class, 'cambiarPassword']);
});

// USUARIOS - Gestión de usuarios
Route::prefix('v1')->middleware(['auth.jwt'])->group(function () {
    // Búsqueda y Listado
    Route::get('usuarios/search', [UsuarioController::class, 'search']);
    Route::get('usuarios', [UsuarioController::class, 'index']);
    Route::get('usuarios/{usuario}', [UsuarioController::class, 'show'])->whereNumber('usuario');

    // Crear, Actualizar, Eliminar - Solo Administrador
    Route::post('usuarios', [UsuarioController::class, 'store'])->middleware('role:Administrador');
    Route::put('usuarios/{usuario}', [UsuarioController::class, 'update'])
        ->whereNumber('usuario')
        ->middleware('role:Administrador');
    Route::delete('usuarios/{usuario}', [UsuarioController::class, 'destroy'])
        ->whereNumber('usuario')
        ->middleware('role:Administrador');
});

// ROLES Y PERMISOS - Solo Administrador
Route::prefix('v1')->middleware(['auth.jwt', 'role:Administrador'])->group(function () {
    // Roles
    Route::get('roles/search', [RolController::class, 'search']);
    Route::apiResource('roles', RolController::class);
    Route::post('roles/{id}/permisos', [RolController::class, 'asignarPermisos']);

    // Permisos
    Route::get('permisos/search', [PermisoController::class, 'search']);
    Route::apiResource('permisos', PermisoController::class);
});

// AGRICULTURA - Solo Administrador y Técnico pueden modificar
Route::prefix('v1')->middleware(['auth.jwt', 'role:Administrador,Técnico'])->group(function () {

    // SACA CLASES
    Route::get('saca_clases/search', [AgriSacaClaseController::class, 'search']);
    Route::get('saca_clases', [AgriSacaClaseController::class, 'index']);
    Route::post('saca_clases', [AgriSacaClaseController::class, 'store']);
    Route::get('saca_clases/{id}', [AgriSacaClaseController::class, 'show']);
    Route::put('saca_clases/{id}', [AgriSacaClaseController::class, 'update']);
    Route::delete('saca_clases/{id}', [AgriSacaClaseController::class, 'destroy']);

    // VARIEDADES
    Route::get('variedades/search', [AgriVariedadController::class, 'search']);
    Route::get('variedades', [AgriVariedadController::class, 'index']);
    Route::post('variedades', [AgriVariedadController::class, 'store']);
    Route::get('variedades/{id}', [AgriVariedadController::class, 'show']);
    Route::put('variedades/{id}', [AgriVariedadController::class, 'update']);
    Route::delete('variedades/{id}', [AgriVariedadController::class, 'destroy']);

    // ANIMALES
    Route::get('agri_animales/search', [AgriAnimalController::class, 'search']);
    Route::get('agri_animales', [AgriAnimalController::class, 'index']);
    Route::post('agri_animales', [AgriAnimalController::class, 'store']);
    Route::get('agri_animales/{id}', [AgriAnimalController::class, 'show']);
    Route::put('agri_animales/{id}', [AgriAnimalController::class, 'update']);
    Route::delete('agri_animales/{id}', [AgriAnimalController::class, 'destroy']);

    // NATALIDAD-MORTALIDAD
    Route::get('natalidad-mortalidad/search', [AgriNatalidadMortalidadController::class, 'search']);
    Route::get('natalidad-mortalidad', [AgriNatalidadMortalidadController::class, 'index']);
    Route::post('natalidad-mortalidad', [AgriNatalidadMortalidadController::class, 'store']);
    Route::get('natalidad-mortalidad/{id}', [AgriNatalidadMortalidadController::class, 'show']);
    Route::put('natalidad-mortalidad/{id}', [AgriNatalidadMortalidadController::class, 'update']);
    Route::delete('natalidad-mortalidad/{id}', [AgriNatalidadMortalidadController::class, 'destroy']);

    // DESTINOS
    Route::get('agri-destinos/search', [AgriDestinoController::class, 'search']);
    Route::apiResource('destinos', AgriDestinoController::class);
});

// CULTIVOS - Solo Administrador y Técnico (consulta y modificación)
Route::prefix('v1')->middleware(['auth.jwt', 'role:Administrador,Técnico'])->group(function () {

    // SubSectores - CRUD completo
    Route::get('subsectores', [SubSectorController::class, 'index']);
    Route::get('subsectores/{subsector}', [SubSectorController::class, 'show']);
    Route::get('subsectores/search', [SubSectorController::class, 'search']);
    Route::post('subsectores', [SubSectorController::class, 'store']);
    Route::put('subsectores/{subsector}', [SubSectorController::class, 'update']);
    Route::delete('subsectores/{subsector}', [SubSectorController::class, 'destroy']);

    // Grupos - CRUD completo
    Route::get('grupos', [GrupoController::class, 'index']);
    Route::get('grupos/{grupo}', [GrupoController::class, 'show']);
    Route::get('grupos/search', [GrupoController::class, 'search']);
    Route::post('grupos', [GrupoController::class, 'store']);
    Route::put('grupos/{grupo}', [GrupoController::class, 'update']);
    Route::delete('grupos/{grupo}', [GrupoController::class, 'destroy']);

    // SubGrupos - CRUD completo
    Route::get('subgrupos', [SubGrupoController::class, 'index']);
    Route::get('subgrupos/{subgrupo}', [SubGrupoController::class, 'show']);
    Route::get('subgrupos/search', [SubGrupoController::class, 'search']);
    Route::post('subgrupos', [SubGrupoController::class, 'store']);
    Route::put('subgrupos/{subgrupo}', [SubGrupoController::class, 'update']);
    Route::delete('subgrupos/{subgrupo}', [SubGrupoController::class, 'destroy']);

    // Cultivos - CRUD completo
    Route::get('cultivos', [CultivoController::class, 'index']);
    Route::get('cultivos/{cultivo}', [CultivoController::class, 'show']);
    Route::get('cultivos/search', [CultivoController::class, 'search']);
    Route::post('cultivos', [CultivoController::class, 'store']);
    Route::put('cultivos/{cultivo}', [CultivoController::class, 'update']);
    Route::delete('cultivos/{cultivo}', [CultivoController::class, 'destroy']);

    // Reportes Interactivos - Solo Admin/Técnico pueden exportar
    // Parámetros: nivel (subsector|grupo|subgrupo|todo), sub_sector_id, grupo_id, sub_grupo_id, search
    Route::get('reportes/cultivos/pdf', [ReporteCultivosController::class, 'pdf']);
    Route::get('reportes/cultivos/excel', [ReporteCultivosController::class, 'excel']);
});

// PRECIOS - Sistema de Encuestas de Precios en Mercados
Route::prefix('precios')->middleware(['auth.jwt', 'role:Administrador,Técnico'])->group(function () {

    // Catálogos base
    Route::apiResource('ubicaciones', PreGeoUbicacionController::class)->names('precios.ubicaciones');
    Route::apiResource('categorias', PreCategoriaController::class)->names('precios.categorias');
    Route::apiResource('productos', PreProductoController::class)->names('precios.productos');
    Route::apiResource('mercados', PreMercadoController::class)->names('precios.mercados');
    Route::apiResource('encuestadores', PreEncuestadorController::class)->names('precios.encuestadores');

    // Muestras - Registro de precios
    Route::apiResource('muestras', PreMuestraController::class)->names('precios.muestras');
    Route::post('muestras/{id}/validar', [PreMuestraController::class, 'validar'])->name('precios.muestras.validar');
    Route::post('muestras/validar-lote', [PreMuestraController::class, 'validarLote'])->name('precios.muestras.validar-lote');

    // Reportes
    Route::get('reportes/comparativo', [PreReporteController::class, 'comparativo']);
    Route::post('reportes/generar-comparativo', [PreReporteController::class, 'generarComparativo']);
    Route::get('reportes/resumen-muestras', [PreReporteController::class, 'resumenMuestras']);
    Route::get('reportes/historico/{producto_id}', [PreReporteController::class, 'historico']);

    // Reportes de Productividad de Encuestadores
    Route::get('reportes/encuestadores/productividad', [PreReporteController::class, 'productividadEncuestadores']);
    Route::get('reportes/encuestadores/por-dia', [PreReporteController::class, 'encuestadoresPorDia']);
    Route::get('reportes/encuestadores/por-mes', [PreReporteController::class, 'encuestadoresPorMes']);
});

// AGRI - Sistema SIEA de Insumos Agrícolas
Route::prefix('agri')->middleware(['auth.jwt'])->group(function () {

    // CRUD Gestión Operacional

    // Rutas personalizadas de encuestas (ANTES de apiResource)
    Route::get('encuestas-estadisticas', [App\Http\Controllers\Api\AgriEncuestaController::class, 'estadisticas']);
    Route::get('encuestas-estadisticas/exportar', [App\Http\Controllers\Api\AgriEncuestaController::class, 'exportarEstadisticas']);
    Route::get('encuestas/{id}/formulario-completo', [App\Http\Controllers\Api\AgriEncuestaController::class, 'obtenerFormulario']);
    Route::post('encuestas/{id}/validar', [App\Http\Controllers\Api\AgriEncuestaController::class, 'validar']);
    Route::post('encuestas/{id}/rechazar', [App\Http\Controllers\Api\AgriEncuestaController::class, 'rechazar']);

    // CRUD estándar de encuestas
    Route::apiResource('encuestas', App\Http\Controllers\Api\AgriEncuestaController::class)->names('agri.encuestas');

    Route::apiResource('encuestadores', App\Http\Controllers\Api\AgriEncuestadorController::class)->names('agri.encuestadores');
    Route::apiResource('supervisores', App\Http\Controllers\Api\AgriSupervisorController::class)->names('agri.supervisores');
    Route::apiResource('asignaciones', App\Http\Controllers\Api\AgriAsignacionController::class)->names('agri.asignaciones');

    // CRUD Catálogos
    Route::apiResource('maquinaria', App\Http\Controllers\Api\AgriMaquinariaController::class)->names('agri.maquinaria');
    Route::apiResource('fertilizantes', App\Http\Controllers\Api\AgriFertilizanteController::class)->names('agri.fertilizantes');
    Route::apiResource('agroquimicos', App\Http\Controllers\Api\AgriAgroquimicoController::class)->names('agri.agroquimicos');

    // Reportes SIEA
    Route::prefix('reportes')->group(function () {

        // Reportes de precios por formulario
        Route::get('precios-maquinaria', [App\Http\Controllers\Api\AgriReporteController::class, 'preciosMaquinaria']);
        Route::get('precios-fertilizantes', [App\Http\Controllers\Api\AgriReporteController::class, 'preciosFertilizantes']);
        Route::get('precios-agroquimicos', [App\Http\Controllers\Api\AgriReporteController::class, 'preciosAgroquimicos']);
        Route::get('precios-transporte', [App\Http\Controllers\Api\AgriReporteController::class, 'preciosTransporte']);

        // Reportes comparativos
        Route::get('tendencias', [App\Http\Controllers\Api\AgriReporteController::class, 'tendencias']);

        // Reportes de productividad
        Route::get('productividad-encuestadores', [App\Http\Controllers\Api\AgriReporteController::class, 'productividadEncuestadores']);
        Route::get('cumplimiento-metas', [App\Http\Controllers\Api\AgriReporteController::class, 'cumplimientoMetas']);

        // Dashboards
        Route::get('dashboard-general', [App\Http\Controllers\Api\AgriReporteController::class, 'dashboardGeneral']);

        // Exportaciones Excel y PDF
        Route::get('analisis-precios-export', [App\Http\Controllers\Api\AgriReporteController::class, 'analisisPreciosExport']);
        Route::get('analisis-precios-pdf', [App\Http\Controllers\Api\AgriReporteController::class, 'analisisPreciosPdf']);
        Route::get('transporte-export', [App\Http\Controllers\Api\AgriReporteController::class, 'transporteExport']);
        Route::get('transporte-pdf', [App\Http\Controllers\Api\AgriReporteController::class, 'transportePdf']);
        Route::get('maquinaria-export', [App\Http\Controllers\Api\AgriReporteController::class, 'maquinariaExport']);
        Route::get('maquinaria-pdf', [App\Http\Controllers\Api\AgriReporteController::class, 'maquinariaPdf']);
        Route::get('fertilizantes-export', [App\Http\Controllers\Api\AgriReporteController::class, 'fertilizantesExport']);
        Route::get('fertilizantes-pdf', [App\Http\Controllers\Api\AgriReporteController::class, 'fertilizantesPdf']);
        Route::get('agroquimicos-export', [App\Http\Controllers\Api\AgriReporteController::class, 'agroquimicosExport']);
        Route::get('agroquimicos-pdf', [App\Http\Controllers\Api\AgriReporteController::class, 'agroquimicosPdf']);
        Route::get('transporte-export-nuevo', [App\Http\Controllers\Api\AgriReporteController::class, 'transporteExportNuevo']);
        Route::get('transporte-pdf-nuevo', [App\Http\Controllers\Api\AgriReporteController::class, 'transportePdfNuevo']);
    });
});

