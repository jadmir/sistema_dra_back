<?php

namespace App\Http\Controllers;

use App\Models\{SubSector, Cultivo};
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\{CultivosExport, CultivosAgrupadoExport};

class ReporteCultivosController extends Controller
{
    // Lista blanca de filtros
    private const ALLOWED_FILTERS = ['sub_sector_id','grupo_id','sub_grupo_id','cultivo_id','search'];

    // Normaliza y retorna filtros
    private function filtros(Request $request): array
    {
        $f = $request->only(self::ALLOWED_FILTERS);

        foreach (['sub_sector_id','grupo_id','sub_grupo_id','cultivo_id'] as $k) {
            if (array_key_exists($k, $f)) {
                $f[$k] = $f[$k] === '' ? null : (int) $f[$k];
                if ($f[$k] === null) unset($f[$k]);
            }
        }

        if (isset($f['search'])) {
            $f['search'] = trim((string) $f['search']);
            if ($f['search'] === '') unset($f['search']);
        }

        return $f;
    }

    // Reporte PDF de cultivos (simple o agrupado)
    public function pdf(Request $request)
    {
        try {
            $request->validate([
                'tipo'          => 'sometimes|in:agrupado,simple',
                'sub_sector_id' => 'sometimes|integer|exists:sub_sectores,id',
                'grupo_id'      => 'sometimes|integer|exists:grupos,id',
                'sub_grupo_id'  => 'sometimes|integer|exists:sub_grupos,id',
                'cultivo_id'    => 'sometimes|integer|exists:cultivos,id',
                'search'        => 'sometimes|string|max:100',
            ]);

            $tipo     = $request->get('tipo', 'agrupado');
            $filters  = $this->filtros($request);

            if ($tipo === 'simple') {
                $cultivos = $this->filtrarCultivos($filters);
                $view = 'reportes.cultivos_simple';
                $data = [
                    'cultivos' => $cultivos,
                    'fecha'    => now()->format('d/m/Y H:i'),
                ];
            } else {
                $subsectores = $this->getJerarquia($filters);
                $view = 'reportes.cultivos_agrupado';
                $data = [
                    'subsectores' => $subsectores,
                    'fecha'       => now()->format('d/m/Y H:i'),
                ];
            }

            $pdf = Pdf::loadView($view, $data)->setPaper('a4', 'portrait');
            return $pdf->stream("reporte_cultivos_{$tipo}.pdf");
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al generar el PDF de cultivos.'
            ], 500);
        }
    }

    // Lista plana de cultivos, con filtros por jerarquía y búsqueda
    private function filtrarCultivos(array $filters)
    {
        $term = $filters['search'] ?? '';

        $q = Cultivo::with('subgrupo.grupo.subsector')
            ->where('estado', 1)
            ->when(isset($filters['cultivo_id']), fn($qq) => $qq->where('id', $filters['cultivo_id']))
            ->when(isset($filters['sub_grupo_id']), fn($qq) => $qq->where('sub_grupo_id', $filters['sub_grupo_id']))
            ->when(isset($filters['grupo_id']), function ($qq) use ($filters) {
                $qq->whereHas('subgrupo', fn($w) => $w->where('grupo_id', $filters['grupo_id']));
            })
            ->when(isset($filters['sub_sector_id']), function ($qq) use ($filters) {
                $qq->whereHas('subgrupo.grupo', fn($w) => $w->where('sub_sector_id', $filters['sub_sector_id']));
            });

        if ($term !== '') {
            $q->where(function ($w) use ($term) {
                $w->where('codigo', 'like', "%{$term}%")
                  ->orWhere('descripcion', 'like', "%{$term}%");
            });
        }

        return $q->orderBy('codigo')->get(); // sin paginar para PDF
    }

    // Estructura jerárquica: Subsector -> Grupos -> Subgrupos -> Cultivos (todos activos)
    private function getJerarquia(array $filters)
    {
        $term = $filters['search'] ?? '';

        return SubSector::query()
            ->where('estado', 1)
            ->when(isset($filters['sub_sector_id']), fn($q) => $q->where('id', $filters['sub_sector_id']))
            ->with(['grupos' => function ($q) use ($filters, $term) {
                $q->where('estado', 1)
                  ->when(isset($filters['grupo_id']), fn($qq) => $qq->where('id', $filters['grupo_id']))
                  ->with(['subgrupos' => function ($q2) use ($filters, $term) {
                      $q2->where('estado', 1)
                         ->when(isset($filters['sub_grupo_id']), fn($qq2) => $qq2->where('id', $filters['sub_grupo_id']))
                         ->with(['cultivos' => function ($q3) use ($filters, $term) {
                             $q3->where('estado', 1)
                                ->when(isset($filters['cultivo_id']), fn($qq3) => $qq3->where('id', $filters['cultivo_id']));

                             if ($term !== '') {
                                 $q3->where(function ($w) use ($term) {
                                     $w->where('codigo', 'like', "%{$term}%")
                                       ->orWhere('descripcion', 'like', "%{$term}%");
                                 });
                             }
                         }]);
                  }]);
            }])
            ->orderBy('codigo')
            ->get();
    }

    // Reporte Excel de cultivos (simple o agrupado)
    public function excel(Request $request)
    {
        try {
            $request->validate([
                'tipo'          => 'sometimes|in:agrupado,simple',
                'sub_sector_id' => 'sometimes|integer|exists:sub_sectores,id',
                'grupo_id'      => 'sometimes|integer|exists:grupos,id',
                'sub_grupo_id'  => 'sometimes|integer|exists:sub_grupos,id',
                'cultivo_id'    => 'sometimes|integer|exists:cultivos,id',
                'search'        => 'sometimes|string|max:100',
            ]);

            $tipo    = $request->get('tipo', 'agrupado');
            $filters = $this->filtros($request); // unificados
            $stamp   = now()->format('Ymd_His');

            if ($tipo === 'simple') {
                return Excel::download(new CultivosExport($filters), "reporte_cultivos_simple_{$stamp}.xlsx");
            }

            return Excel::download(new CultivosAgrupadoExport($filters), "reporte_cultivos_agrupado_{$stamp}.xlsx");
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Error al generar el Excel de cultivos.'], 500);
        }
    }
}
