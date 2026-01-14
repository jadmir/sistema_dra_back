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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class MaquinariaExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $datos;
    protected $filtros;
    protected $estadisticas;

    public function __construct($datos, $filtros = [], $estadisticas = [])
    {
        $this->datos = $datos;
        $this->filtros = $filtros;
        $this->estadisticas = $estadisticas;
    }

    public function view(): View
    {
        return view('exports.maquinaria_reporte', [
            'datos' => $this->datos,
            'filtros' => $this->filtros,
            'estadisticas' => $this->estadisticas,
            'fecha_generacion' => now()->format('d/m/Y H:i'),
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // Colores institucionales SIEA
                $amarilloPrincipal = 'FFC107';
                $amarilloClaro = 'FFE082';
                $amarilloFondo = 'FFFDE7';
                $marron = '8B4513';

                // ============================================
                // ENCABEZADO PRINCIPAL (Filas 1-3)
                // ============================================
                $sheet->getStyle('A1:I3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => $marron]
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $amarilloPrincipal]
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THICK,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // Logo SIEA (A1)
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 20,
                        'color' => ['rgb' => $marron]
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT
                    ]
                ]);

                // Código F-1 (I1:I3)
                $sheet->getStyle('I1:I3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 28,
                        'color' => ['rgb' => $marron]
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $amarilloPrincipal]
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THICK,
                            'color' => ['rgb' => $marron]
                        ]
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                // ============================================
                // AJUSTES DE TAMAÑO
                // ============================================
                $sheet->getRowDimension(1)->setRowHeight(25);
                $sheet->getRowDimension(2)->setRowHeight(20);
                $sheet->getRowDimension(3)->setRowHeight(25);

                $sheet->getColumnDimension('A')->setWidth(8);   // #
                $sheet->getColumnDimension('B')->setWidth(15);  // Nombre
                $sheet->getColumnDimension('C')->setWidth(15);  // Nombre (cont)
                $sheet->getColumnDimension('D')->setWidth(15);  // Categoría
                $sheet->getColumnDimension('E')->setWidth(14);  // Precio Promedio
                $sheet->getColumnDimension('F')->setWidth(14);  // Precio Mínimo
                $sheet->getColumnDimension('G')->setWidth(14);  // Precio Máximo
                $sheet->getColumnDimension('H')->setWidth(12);  // Desv. Estándar
                $sheet->getColumnDimension('I')->setWidth(10);  // N° Reg.

                // ============================================
                // FORMATO DE NÚMEROS (Columnas de precios)
                // ============================================
                $sheet->getStyle('E:G')->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle('H')->getNumberFormat()->setFormatCode('#,##0.00');

                // ============================================
                // BORDES GENERALES
                // ============================================
                $sheet->getStyle('A1:I' . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);
            }
        ];
    }
}
