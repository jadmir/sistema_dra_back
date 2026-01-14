<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AgriEncuesta;
use App\Exports\EncuestasEstadisticasExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AgriEncuestaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = AgriEncuesta::with(['encuestador', 'supervisor']);

            // Filtros
            if ($request->has('tipo_formulario')) {
                $query->tipoFormulario($request->tipo_formulario);
            }

            if ($request->has('estado')) {
                $query->estado($request->estado);
            }

            if ($request->has('encuestador_id')) {
                $query->encuestador($request->encuestador_id);
            }

            if ($request->has('supervisor_id')) {
                $query->supervisor($request->supervisor_id);
            }

            if ($request->has('provincia')) {
                $query->provincia($request->provincia);
            }

            if ($request->has('anio') && $request->has('mes')) {
                $query->periodo($request->anio, $request->mes);
            } elseif ($request->has('anio')) {
                $query->periodo($request->anio);
            }

            // Ordenamiento
            $sortBy = $request->get('sort_by', 'fecha_recoleccion');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Paginación
            $perPage = $request->get('per_page', 15);
            $encuestas = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $encuestas->items(),
                'pagination' => [
                    'total' => $encuestas->total(),
                    'per_page' => $encuestas->perPage(),
                    'current_page' => $encuestas->currentPage(),
                    'last_page' => $encuestas->lastPage(),
                    'from' => $encuestas->firstItem(),
                    'to' => $encuestas->lastItem(),
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener encuestas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'tipo_formulario' => 'required|in:F-1,F-4,F-6,F-14',
                'region' => 'required|string|max:100',
                'provincia' => 'required|string|max:100',
                'distrito' => 'required|string|max:100',
                'anio' => 'required|integer|min:2020|max:2030',
                'mes' => 'required|integer|min:1|max:12',
                'fecha_recoleccion' => 'required|date',
                'encuestador_id' => 'required|exists:agri_encuestadores_insumos,id',
                'supervisor_id' => 'nullable|exists:agri_supervisores_insumos,id',
                'nombre_informante' => 'nullable|string|max:200',
                'telefono_informante' => 'nullable|string|max:20',
                'fuente_informacion' => 'nullable|string|max:200',
                'estado' => 'nullable|in:borrador,enviado,validado,rechazado',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $encuesta = AgriEncuesta::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Encuesta creada exitosamente',
                'data' => $encuesta->load(['encuestador', 'supervisor'])
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear encuesta',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $encuesta = AgriEncuesta::with(['encuestador', 'supervisor'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $encuesta
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Encuesta no encontrada',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $encuesta = AgriEncuesta::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'tipo_formulario' => 'sometimes|in:F-1,F-4,F-6,F-14',
                'region' => 'sometimes|string|max:100',
                'provincia' => 'sometimes|string|max:100',
                'distrito' => 'sometimes|string|max:100',
                'anio' => 'sometimes|integer|min:2020|max:2030',
                'mes' => 'sometimes|integer|min:1|max:12',
                'fecha_recoleccion' => 'sometimes|date',
                'encuestador_id' => 'sometimes|exists:agri_encuestadores_insumos,id',
                'supervisor_id' => 'nullable|exists:agri_supervisores_insumos,id',
                'estado' => 'sometimes|in:borrador,enviado,validado,rechazado',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $encuesta->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Encuesta actualizada exitosamente',
                'data' => $encuesta->load(['encuestador', 'supervisor'])
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar encuesta',
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
            $encuesta = AgriEncuesta::findOrFail($id);
            $encuesta->delete();

            return response()->json([
                'success' => true,
                'message' => 'Encuesta eliminada exitosamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar encuesta',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validar una encuesta
     */
    public function validar(Request $request, string $id)
    {
        try {
            $encuesta = AgriEncuesta::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'supervisor_id' => 'required|exists:agri_supervisores_insumos,id',
                'observaciones_supervisor' => 'nullable|string',
                'firma_supervisor' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $encuesta->update([
                'estado' => 'validado',
                'supervisor_id' => $request->supervisor_id,
                'fecha_validacion' => now(),
                'observaciones_supervisor' => $request->observaciones_supervisor,
                'firma_supervisor' => $request->firma_supervisor,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Encuesta validada exitosamente',
                'data' => $encuesta->load(['encuestador', 'supervisor'])
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al validar encuesta',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rechazar una encuesta
     */
    public function rechazar(Request $request, string $id)
    {
        try {
            $encuesta = AgriEncuesta::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'supervisor_id' => 'required|exists:agri_supervisores_insumos,id',
                'observaciones_supervisor' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $encuesta->update([
                'estado' => 'rechazado',
                'supervisor_id' => $request->supervisor_id,
                'fecha_validacion' => now(),
                'observaciones_supervisor' => $request->observaciones_supervisor,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Encuesta rechazada',
                'data' => $encuesta->load(['encuestador', 'supervisor'])
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al rechazar encuesta',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Estadísticas de encuestas
     */
    public function estadisticas(Request $request)
    {
        try {
            // Query base con filtros
            $queryBase = AgriEncuesta::query();

            // Filtros opcionales
            if ($request->has('anio')) {
                $queryBase->where('anio', $request->anio);
            }

            if ($request->has('mes')) {
                $queryBase->where('mes', $request->mes);
            }

            if ($request->has('region')) {
                $queryBase->where('region', $request->region);
            }

            if ($request->has('supervisor_id')) {
                $queryBase->where('supervisor_id', $request->supervisor_id);
            }

            // Calcular estadísticas (clonando query para cada una)
            $estadisticas = [
                'total' => (clone $queryBase)->count(),
                'por_estado' => (clone $queryBase)
                    ->selectRaw('estado, COUNT(*) as total')
                    ->groupBy('estado')
                    ->pluck('total', 'estado'),
                'por_tipo' => (clone $queryBase)
                    ->selectRaw('tipo_formulario, COUNT(*) as total')
                    ->groupBy('tipo_formulario')
                    ->pluck('total', 'tipo_formulario'),
                'por_provincia' => (clone $queryBase)
                    ->selectRaw('provincia, COUNT(*) as total')
                    ->groupBy('provincia')
                    ->orderByDesc('total')
                    ->limit(10)
                    ->pluck('total', 'provincia'),
                'por_region' => (clone $queryBase)
                    ->selectRaw('region, COUNT(*) as total')
                    ->groupBy('region')
                    ->pluck('total', 'region'),
            ];

            return response()->json([
                'success' => true,
                'data' => $estadisticas
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

        /**
         * Obtener el formulario completo de una encuesta con todos sus detalles
         */
        public function obtenerFormulario(string $id)
        {
            try {
                $encuesta = AgriEncuesta::with(['encuestador', 'supervisor'])->findOrFail($id);

                $resultado = [
                    'encuesta' => $encuesta,
                    'formulario' => null,
                    'items' => []
                ];

                // Según el tipo de formulario, obtener los datos específicos
                switch ($encuesta->tipo_formulario) {
                    case 'F-1':
                        // Maquinaria
                        $resultado['items'] = DB::table('agri_precios_maquinaria_insumos')
                            ->where('encuesta_id', $id)
                            ->get();
                        $resultado['formulario'] = 'F-1: Encuesta de Maquinaria Agrícola';
                        break;

                    case 'F-4':
                        // Fertilizantes
                        $resultado['items'] = DB::table('agri_precios_fertilizantes_insumos')
                            ->where('encuesta_id', $id)
                            ->get();
                        $resultado['formulario'] = 'F-4: Encuesta de Fertilizantes';
                        break;

                    case 'F-6':
                        // Agroquímicos
                        $resultado['items'] = DB::table('agri_precios_agroquimicos_insumos')
                            ->where('encuesta_id', $id)
                            ->get();
                        $resultado['formulario'] = 'F-6: Encuesta de Agroquímicos';
                        break;

                    case 'F-14':
                        // Transporte
                        $resultado['items'] = DB::table('agri_precios_transporte_insumos')
                            ->where('encuesta_id', $id)
                            ->get();
                        $resultado['formulario'] = 'F-14: Encuesta de Transporte de Carga';
                        break;

                    default:
                        return response()->json([
                            'success' => false,
                            'message' => 'Tipo de formulario no reconocido'
                        ], 400);
                }

                return response()->json([
                    'success' => true,
                    'data' => $resultado
                ], 200);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al obtener formulario',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

    /**
     * Exportar estadísticas de encuestas
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportarEstadisticas(Request $request)
    {
        try {
            // Validar formato de exportación
            $formato = $request->input('formato', 'excel'); // excel o pdf

            if (!in_array($formato, ['excel', 'pdf'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Formato no válido. Use: excel o pdf'
                ], 400);
            }

            // Obtener filtros
            $filtros = $request->only(['anio', 'mes', 'region', 'provincia', 'estado', 'tipo_formulario', 'supervisor_id']);

            // Obtener estadísticas para incluir en el reporte
            $queryBase = AgriEncuesta::query();

            foreach ($filtros as $campo => $valor) {
                if (!empty($valor)) {
                    $queryBase->where($campo, $valor);
                }
            }

            $estadisticas = [
                'total' => (clone $queryBase)->count(),
                'por_estado' => (clone $queryBase)
                    ->selectRaw('estado, COUNT(*) as total')
                    ->groupBy('estado')
                    ->pluck('total', 'estado')
                    ->toArray(),
                'por_tipo' => (clone $queryBase)
                    ->selectRaw('tipo_formulario, COUNT(*) as total')
                    ->groupBy('tipo_formulario')
                    ->pluck('total', 'tipo_formulario')
                    ->toArray(),
            ];

            // Generar nombre de archivo
            $timestamp = now()->format('Y-m-d_His');
            $nombreBase = "estadisticas_encuestas_{$timestamp}";

            if ($formato === 'excel') {
                // Exportar a Excel
                $nombreArchivo = "{$nombreBase}.xlsx";

                return Excel::download(
                    new EncuestasEstadisticasExport($filtros, $estadisticas),
                    $nombreArchivo,
                    \Maatwebsite\Excel\Excel::XLSX
                );

            } else {
                // Exportar a PDF
                $nombreArchivo = "{$nombreBase}.pdf";

                // Obtener datos para el PDF
                $query = AgriEncuesta::with(['encuestador', 'supervisor'])
                    ->orderBy('fecha_recoleccion', 'desc');

                foreach ($filtros as $campo => $valor) {
                    if (!empty($valor)) {
                        $query->where($campo, $valor);
                    }
                }

                $encuestas = $query->get();

                // Generar PDF
                $pdf = Pdf::loadView('exports.estadisticas-encuestas-pdf', [
                    'encuestas' => $encuestas,
                    'estadisticas' => $estadisticas,
                    'filtros' => $filtros,
                    'fecha_generacion' => now()->format('d/m/Y H:i:s')
                ]);

                $pdf->setPaper('a4', 'landscape');

                return $pdf->download($nombreArchivo);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al exportar estadísticas',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
