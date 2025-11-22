<?php

namespace App\Exports;

use App\Models\SubSector;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class CultivosAgrupadoExport implements FromView, ShouldAutoSize, WithEvents
{
    protected array $filters;
    protected string $nivel;

    public function __construct(array $filters = [], string $nivel = 'todo')
    {
        $this->filters = $filters;
        $this->nivel = $nivel;
    }

    public function view(): View
    {
        $filters = $this->filters;
        $nivel = $this->nivel;
        $term = trim((string)($filters['search'] ?? ''));

        $subsectores = $this->getDataPorNivel($filters, $nivel, $term);

        return view('reportes.cultivos_agrupado_excel', [
            'subsectores' => $subsectores,
            'fecha'       => now()->format('d/m/Y H:i'),
            'nivel'       => $nivel,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Estilo del encabezado (fila 1)
                $sheet->getStyle('A1:C1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                        'size' => 12,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '2d5016'], // Verde oscuro agrícola
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Ajustar altura de encabezado
                $sheet->getRowDimension(1)->setRowHeight(25);

                // Bordes a todas las celdas con datos
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle("A1:C{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '8b7355'], // Marrón tierra
                        ],
                    ],
                ]);

                // Aplicar colores a filas según nivel jerárquico
                for ($row = 2; $row <= $highestRow; $row++) {
                    $cellValue = $sheet->getCell("A{$row}")->getValue();

                    // Detectar nivel por el contenido
                    if (stripos($cellValue, 'SubSector') !== false) {
                        $sheet->getStyle("A{$row}:C{$row}")->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => '4a7c39'], // Verde hoja
                            ],
                            'font' => [
                                'bold' => true,
                                'color' => ['rgb' => 'FFFFFF'],
                                'size' => 11,
                            ],
                        ]);
                    } elseif (stripos($cellValue, 'Grupo') !== false) {
                        $sheet->getStyle("A{$row}:C{$row}")->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => '6b9e5a'], // Verde medio
                            ],
                            'font' => [
                                'bold' => true,
                                'color' => ['rgb' => 'FFFFFF'],
                            ],
                        ]);
                    } elseif (stripos($cellValue, 'SubGrupo') !== false) {
                        $sheet->getStyle("A{$row}:C{$row}")->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'a8d08d'], // Verde claro
                            ],
                            'font' => [
                                'bold' => true,
                                'color' => ['rgb' => '2d4a2b'],
                            ],
                        ]);
                    }
                }

                // Congelar primera fila
                $sheet->freezePane('A2');
            },
        ];
    }

    private function getDataPorNivel(array $filters, string $nivel, string $term)
    {
        switch ($nivel) {
            case 'subsector':
                // Solo subsectores
                return SubSector::query()
                    ->where('estado', 1)
                    ->when(!empty($filters['sub_sector_id']), fn($q) => $q->where('id', (int)$filters['sub_sector_id']))
                    ->orderBy('codigo')
                    ->get();

            case 'grupo':
                // Subsectores con grupos
                return SubSector::query()
                    ->where('estado', 1)
                    ->when(!empty($filters['sub_sector_id']), fn($q) => $q->where('id', (int)$filters['sub_sector_id']))
                    ->with(['grupos' => function ($q) use ($filters) {
                        $q->where('estado', 1)
                          ->when(!empty($filters['grupo_id']), fn($qq) => $qq->where('id', (int)$filters['grupo_id']))
                          ->orderBy('codigo');
                    }])
                    ->orderBy('codigo')
                    ->get();

            case 'subgrupo':
                // Hasta subgrupos
                return SubSector::query()
                    ->where('estado', 1)
                    ->when(!empty($filters['sub_sector_id']), fn($q) => $q->where('id', (int)$filters['sub_sector_id']))
                    ->with(['grupos' => function ($q) use ($filters) {
                        $q->where('estado', 1)
                          ->when(!empty($filters['grupo_id']), fn($qq) => $qq->where('id', (int)$filters['grupo_id']))
                          ->with(['subgrupos' => function ($q2) use ($filters) {
                              $q2->where('estado', 1)
                                 ->when(!empty($filters['sub_grupo_id']), fn($qq2) => $qq2->where('id', (int)$filters['sub_grupo_id']))
                                 ->orderBy('codigo');
                          }])
                          ->orderBy('codigo');
                    }])
                    ->orderBy('codigo')
                    ->get();

            case 'cultivo':
            case 'todo':
            default:
                // Jerarquía completa
                return SubSector::query()
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
                              ])
                              ->orderBy('codigo');
                        }
                    ])
                    ->orderBy('codigo')
                    ->get();
        }
    }
}
