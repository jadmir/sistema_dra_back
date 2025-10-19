<?php

namespace App\Exports;

use App\Models\SubSector;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CultivosAgrupadoExport implements FromView, ShouldAutoSize
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function view(): View
    {
        $filters = $this->filters;
        $term = trim((string)($filters['search'] ?? ''));

        $subsectores = SubSector::query()
            ->where('estado', 1)
            ->when(!empty($filters['sub_sector_id']), fn($q) => $q->where('id', (int)$filters['sub_sector_id']))
            ->with([
                'grupos' => function ($q) use ($filters, $term) {
                    $q->where('estado', 1)
                      ->when(!empty($filters['grupo_id']), fn($qq) => $qq->where('id', (int)$filters['grupo_id']))
                      ->with([
                          'subgrupos' => function ($q2) use ($filters, $term) {
                              $q2->where('estado', 1)
                                 ->when(!empty($filters['sub_grupo_id']), fn($qq2) => $qq2->where('id', (int)$filters['sub_grupo_id']))
                                 ->with([
                                     'cultivos' => function ($q3) use ($filters, $term) {
                                         $q3->where('estado', 1)
                                            ->when(!empty($filters['cultivo_id']), fn($qq3) => $qq3->where('id', (int)$filters['cultivo_id']));
                                         if ($term !== '') {
                                             $q3->where(function ($w) use ($term) {
                                                 $w->where('codigo', 'like', "%{$term}%")
                                                   ->orWhere('descripcion', 'like', "%{$term}%");
                                             });
                                         }
                                     }
                                 ]);
                          }
                      ]);
                }
            ])
            ->orderBy('codigo')
            ->get();

        return view('reportes.cultivos_agrupado', [
            'subsectores' => $subsectores,
            'fecha'       => now()->format('d/m/Y H:i'),
        ]);
    }
}
