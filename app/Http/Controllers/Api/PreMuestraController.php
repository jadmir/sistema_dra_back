<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PreMuestra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PreMuestraController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /api/precios/muestras?fecha=2025-11-17&mercado_id=1&producto_id=1&validado=1
     */
    public function index(Request $request)
    {
        try {
            $query = PreMuestra::with(['mercado', 'producto', 'encuestador', 'usuario']);

            // Filtros
            if ($request->has('fecha')) {
                Log::info('Filtrando por fecha: ' . $request->fecha);
                $query = $query->fecha($request->fecha);
            }

            if ($request->has('mercado_id')) {
                $query = $query->where('mercado_id', $request->mercado_id);
            }

            if ($request->has('producto_id')) {
                $query = $query->where('producto_id', $request->producto_id);
            }

            if ($request->has('encuestador_id')) {
                $query = $query->where('encuestador_id', $request->encuestador_id);
            }

            if ($request->has('validado')) {
                $validado = filter_var($request->validado, FILTER_VALIDATE_BOOLEAN);
                $query = $query->where('validado', $validado);
            }

            // Log para debug
            $sql = $query->toSql();
            $bindings = $query->getBindings();
            Log::info('SQL Query: ' . $sql, ['bindings' => $bindings]);

            // Ordenar por fecha descendente y muestra número
            $muestras = $query->orderBy('fecha', 'desc')
                             ->orderBy('muestra_nro')
                             ->paginate($request->per_page ?? 50);

            Log::info('Total muestras encontradas: ' . $muestras->total());

            return response()->json($muestras);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener muestras',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/precios/muestras
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mercado_id' => 'required|exists:pre_mercados,id',
            'producto_id' => 'required|exists:pre_productos,id',
            'encuestador_id' => 'required|exists:pre_encuestadores,id',
            'fecha' => 'required|date',
            'muestra_nro' => 'nullable|integer|between:1,4', // Ahora opcional - se calcula automáticamente
            'punto_nro' => 'required|integer|min:1',
            'calidad' => 'required', // Acepta string o número
            'precio' => 'required|numeric|min:0',
            'moneda' => 'nullable|string|max:3',
            'procedencia_principal' => 'nullable|string|max:150',
            'procedencia_secundaria' => 'nullable|string|max:150',
            'observaciones' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Errores de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Convertir calidad a número (acepta string o número)
            $calidadMap = [
                'Extra' => 1,
                'Primera' => 2,
                'Segunda' => 3,
                'Tercera' => 4,
                'Descarte' => 5,
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
                5 => 5
            ];

            $calidadInput = $request->calidad;

            // Validar que la calidad sea válida
            if (!isset($calidadMap[$calidadInput])) {
                return response()->json([
                    'message' => 'Errores de validación',
                    'errors' => [
                        'calidad' => ['El campo calidad debe ser un número entre 1-5 o un texto válido (Extra, Primera, Segunda, Tercera, Descarte)']
                    ]
                ], 422);
            }

            // Calcular automáticamente el siguiente número de muestra disponible
            // para evitar duplicados de la clave única (mercado_id, producto_id, fecha, muestra_nro)
            $siguienteMuestraNro = PreMuestra::where('mercado_id', $request->mercado_id)
                ->where('producto_id', $request->producto_id)
                ->where('fecha', $request->fecha)
                ->max('muestra_nro') + 1;

            // Si no hay muestras previas, empezar desde 1
            if (!$siguienteMuestraNro) {
                $siguienteMuestraNro = 1;
            }

            Log::info('Creando muestra', [
                'mercado_id' => $request->mercado_id,
                'producto_id' => $request->producto_id,
                'fecha' => $request->fecha,
                'muestra_nro_calculado' => $siguienteMuestraNro
            ]);

            $muestra = PreMuestra::create([
                'mercado_id' => $request->mercado_id,
                'producto_id' => $request->producto_id,
                'encuestador_id' => $request->encuestador_id,
                'fecha' => $request->fecha,
                'muestra_nro' => $siguienteMuestraNro, // Usar el número calculado
                'punto_nro' => $request->punto_nro,
                'calidad' => $calidadMap[$calidadInput],
                'precio' => $request->precio,
                'moneda' => $request->moneda ?? 'PEN',
                'procedencia_principal' => $request->procedencia_principal,
                'procedencia_secundaria' => $request->procedencia_secundaria,
                'observaciones' => $request->observaciones,
                'usuario_id' => $request->user()->id,
                'validado' => false
            ]);

            $muestra->load(['mercado', 'producto', 'encuestador']);

            DB::commit();

            return response()->json([
                'message' => 'Muestra registrada exitosamente',
                'data' => $muestra
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            // Log del error para debug
            Log::error('Error al crear muestra: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'message' => 'Error al registrar muestra',
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $muestra = PreMuestra::with(['mercado', 'producto', 'encuestador', 'usuario', 'validadorUsuario'])
                                ->findOrFail($id);

            return response()->json($muestra);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Muestra no encontrada',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'mercado_id' => 'sometimes|exists:pre_mercados,id',
            'producto_id' => 'sometimes|exists:pre_productos,id',
            'encuestador_id' => 'sometimes|exists:pre_encuestadores,id',
            'fecha' => 'sometimes|date',
            'muestra_nro' => 'sometimes|integer|between:1,4',
            'punto_nro' => 'sometimes|integer|min:1',
            'calidad' => 'sometimes', // Acepta string o número
            'precio' => 'sometimes|numeric|min:0',
            'moneda' => 'nullable|string|max:3',
            'procedencia_principal' => 'nullable|string|max:150',
            'procedencia_secundaria' => 'nullable|string|max:150',
            'observaciones' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Errores de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $muestra = PreMuestra::findOrFail($id);

            // Solo permitir edición si no está validado
            if ($muestra->validado) {
                return response()->json([
                    'message' => 'No se puede editar una muestra ya validada'
                ], 403);
            }

            // Convertir calidad a número si viene (acepta string o número)
            $data = $request->all();
            if (isset($data['calidad'])) {
                $calidadMap = [
                    'Extra' => 1,
                    'Primera' => 2,
                    'Segunda' => 3,
                    'Tercera' => 4,
                    'Descarte' => 5,
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    5 => 5
                ];

                if (!isset($calidadMap[$data['calidad']])) {
                    return response()->json([
                        'message' => 'Errores de validación',
                        'errors' => [
                            'calidad' => ['El campo calidad debe ser un número entre 1-5 o un texto válido (Extra, Primera, Segunda, Tercera, Descarte)']
                        ]
                    ], 422);
                }

                $data['calidad'] = $calidadMap[$data['calidad']];
            }

            $muestra->update($data);
            $muestra->load(['mercado', 'producto', 'encuestador']);

            return response()->json([
                'message' => 'Muestra actualizada exitosamente',
                'data' => $muestra
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar muestra',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $muestra = PreMuestra::findOrFail($id);

            // Solo permitir eliminación si no está validado
            if ($muestra->validado) {
                return response()->json([
                    'message' => 'No se puede eliminar una muestra ya validada'
                ], 403);
            }

            $muestra->delete();

            return response()->json([
                'message' => 'Muestra eliminada exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar muestra',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validar una muestra
     * POST /api/precios/muestras/{id}/validar
     */
    public function validar(Request $request, string $id)
    {
        try {
            $muestra = PreMuestra::findOrFail($id);

            if ($muestra->validado) {
            return response()->json([
                'message' => 'La muestra ya está validada'
            ], 400);
        }

        $muestra->update([
            'validado' => true,
            'validado_por' => $request->user()->id,
            'validado_at' => now()
        ]);

        return response()->json([
            'message' => 'Muestra validada exitosamente',
            'data' => $muestra
        ]);        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al validar muestra',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validar múltiples muestras
     * POST /api/precios/muestras/validar-lote
     */
    public function validarLote(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:pre_muestras,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Errores de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $count = PreMuestra::whereIn('id', $request->ids)
                              ->where('validado', false)
                              ->update([
                                  'validado' => true,
                                  'validado_por' => $request->user()->id,
                                  'validado_at' => now()
                              ]);

            DB::commit();

            return response()->json([
                'message' => "Se validaron {$count} muestras exitosamente",
                'count' => $count
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al validar muestras',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
