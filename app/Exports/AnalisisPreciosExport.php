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

class AnalisisPreciosExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $datos;
    protected $tipo;
    protected $filtros;

    public function __construct($datos, $tipo, $filtros = [])
    {
        $this->datos = $datos;
        $this->tipo = $tipo;
        $this->filtros = $filtros;
    }

    public function view(): View
    {
        return view('exports.analisis-precios', [
            'datos' => $this->datos,
            'tipo' => $this->tipo,
            'filtros' => $this->filtros,
            'fecha_generacion' => now()->format('d/m/Y H:i'),
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Obtener la última fila con datos
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                // Estilo del encabezado (filas 1-5: título e información)
                $sheet->getStyle('A1:' . $highestColumn . '1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '2E7D32']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                // Merge del título
                $sheet->mergeCells('A1:' . $highestColumn . '1');

                // Estilo de la información del reporte (filas 2-5)
                $sheet->getStyle('A2:' . $highestColumn . '5')->applyFromArray([
                    'font' => ['size' => 10],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E8F5E9']
                    ]
                ]);

                // Estilo del encabezado de columnas (fila 7)
                $sheet->getStyle('A7:' . $highestColumn . '7')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '388E3C']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // Estilo de los datos
                if ($highestRow > 7) {
                    $sheet->getStyle('A8:' . $highestColumn . $highestRow)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'CCCCCC']
                            ]
                        ],
                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_CENTER
                        ]
                    ]);

                    // Alternar colores en las filas
                    for ($row = 8; $row <= $highestRow; $row++) {
                        if ($row % 2 == 0) {
                            $sheet->getStyle('A' . $row . ':' . $highestColumn . $row)->applyFromArray([
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => ['rgb' => 'F5F5F5']
                                ]
                            ]);
                        }
                    }
                }

                // Ajustar altura de filas
                $sheet->getRowDimension(1)->setRowHeight(30);
                $sheet->getRowDimension(7)->setRowHeight(25);

                // Formato de moneda para columnas de precios
                $precioColumns = ['D', 'E', 'F', 'G', 'J'];
                foreach ($precioColumns as $col) {
                    if ($highestRow > 7) {
                        $sheet->getStyle($col . '8:' . $col . $highestRow)
                            ->getNumberFormat()
                            ->setFormatCode('S/. #,##0.00');
                    }
                }

                // Formato de porcentaje
                if ($highestRow > 7) {
                    $sheet->getStyle('K8:K' . $highestRow)
                        ->getNumberFormat()
                        ->setFormatCode('0.00"%"');
                }

                // Alinear columnas numéricas a la derecha
                $numericColumns = ['D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'M', 'N'];
                foreach ($numericColumns as $col) {
                    if ($highestRow > 7) {
                        $sheet->getStyle($col . '8:' . $col . $highestRow)->applyFromArray([
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
                        ]);
                    }
                }
            }
        ];
    }
}
