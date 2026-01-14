<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class AgroquimicosExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $datos;
    protected $estadisticas;
    protected $filtros;

    public function __construct($datos, $estadisticas = [], $filtros = [])
    {
        $this->datos = $datos;
        $this->estadisticas = $estadisticas;
        $this->filtros = $filtros;
    }

    public function view(): View
    {
        return view('exports.agroquimicos_reporte', [
            'datos' => $this->datos,
            'estadisticas' => $this->estadisticas,
            'filtros' => $this->filtros,
            'fecha_generacion' => now()->format('d/m/Y H:i'),
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                // Encabezado amarillo principal (filas 1-3)
                $sheet->getStyle('A1:L3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '8B4513']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFD700']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THICK,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // Estilo para F-6 (columna L, fila 3)
                $sheet->getStyle('L3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 24,
                        'color' => ['rgb' => '8B4513']
                    ]
                ]);

                // Bordes para toda la tabla
                $sheet->getStyle('A1:L' . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // Altura de filas
                $sheet->getRowDimension(1)->setRowHeight(20);
                $sheet->getRowDimension(2)->setRowHeight(18);
                $sheet->getRowDimension(3)->setRowHeight(25);

                // Anchos de columna
                $sheet->getColumnDimension('A')->setWidth(18);
                $sheet->getColumnDimension('B')->setWidth(22);
                $sheet->getColumnDimension('C')->setWidth(15);
                for ($col = 'D'; $col <= 'K'; $col++) {
                    $sheet->getColumnDimension($col)->setWidth(10);
                }
                $sheet->getColumnDimension('L')->setWidth(12);

                // Fondo blanco para el resto del documento
                if ($highestRow > 3) {
                    $sheet->getStyle('A4:L' . $highestRow)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'FFFFFF']
                        ]
                    ]);
                }
            }
        ];
    }
}
