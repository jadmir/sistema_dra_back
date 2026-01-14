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

class AgroquimicosF6Export implements FromView, ShouldAutoSize, WithEvents
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
        return view('exports.agroquimicos-f6', [
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

                // Obtener la última fila con datos
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                // ESTILO DEL ENCABEZADO PRINCIPAL (Fila 1 - Título principal)
                $sheet->getStyle('A1:I1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                        'color' => ['rgb' => '000000']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFC107'] // Amarillo
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THICK,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);
                $sheet->mergeCells('A1:I1');
                $sheet->getRowDimension(1)->setRowHeight(60);

                // INFORMACIÓN DEL SISTEMA (Fila 2)
                $sheet->getStyle('A2:I2')->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFFDE7'] // Amarillo claro
                    ],
                    'font' => ['size' => 10]
                ]);
                $sheet->mergeCells('A2:I2');

                // TÍTULO UBICACIÓN POLÍTICA (Fila 3)
                $sheet->getStyle('A3:I3')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 11],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFFFFF']
                    ]
                ]);
                $sheet->mergeCells('A3:I3');

                // DATOS DE UBICACIÓN (Fila 4)
                $sheet->getStyle('A4:I4')->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFFDE7']
                    ]
                ]);

                // TÍTULO AÑO Y MES (Fila 5)
                $sheet->getStyle('A5:I5')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 11],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFFFFF']
                    ]
                ]);
                $sheet->mergeCells('A5:I5');

                // DATOS DE AÑO Y MES (Fila 6)
                $sheet->getStyle('A6:I6')->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFFDE7']
                    ]
                ]);

                // TÍTULO SECCIÓN PRODUCTOS (Fila 7)
                $sheet->getStyle('A7:I7')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFFFFF']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);
                $sheet->mergeCells('A7:I7');
                $sheet->getRowDimension(7)->setRowHeight(25);

                // ENCABEZADOS DE COLUMNAS (Filas 8-9)
                $sheet->getStyle('A8:I9')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 10,
                        'color' => ['rgb' => '000000']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFC107'] // Amarillo
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // Sub-encabezados (Fila 9 - casas comerciales)
                $sheet->getStyle('D9:H9')->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFE082'] // Amarillo más claro
                    ]
                ]);

                $sheet->getRowDimension(8)->setRowHeight(30);
                $sheet->getRowDimension(9)->setRowHeight(20);

                // ESTILO DE LOS DATOS (desde fila 10 hasta antes del footer)
                if ($highestRow > 9) {
                    $dataStartRow = 10;
                    $dataEndRow = $highestRow - 5; // Aproximadamente donde terminan los datos

                    // Bordes para todas las celdas de datos
                    $sheet->getStyle('A' . $dataStartRow . ':I' . $dataEndRow)->applyFromArray([
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

                    // Filas de categorías (tienen fondo amarillo claro)
                    for ($row = $dataStartRow; $row <= $dataEndRow; $row++) {
                        $cellValue = $sheet->getCell('A' . $row)->getValue();

                        // Si la fila tiene colspan o es encabezado de categoría
                        if (strpos($cellValue, 'CIDAS') !== false ||
                            strpos($cellValue, 'HERENTES') !== false ||
                            strpos($cellValue, 'FOLIARES') !== false ||
                            strpos($cellValue, 'CRECIMIENTO') !== false) {
                            $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray([
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => ['rgb' => 'FFFDE7']
                                ],
                                'font' => ['bold' => true],
                                'borders' => [
                                    'allBorders' => [
                                        'borderStyle' => Border::BORDER_THIN,
                                        'color' => ['rgb' => '000000']
                                    ]
                                ]
                            ]);
                        } else {
                            // Alternar colores en las filas de datos
                            if ($row % 2 == 0) {
                                $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray([
                                    'fill' => [
                                        'fillType' => Fill::FILL_SOLID,
                                        'startColor' => ['rgb' => 'FAFAFA']
                                    ]
                                ]);
                            }
                        }
                    }

                    // Columna de PRECIO PROMEDIO con fondo amarillo
                    $sheet->getStyle('I' . $dataStartRow . ':I' . $dataEndRow)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'FFFDE7']
                        ],
                        'font' => ['bold' => true]
                    ]);

                    // Formato de moneda para columnas de precios (D-I)
                    for ($col = 'D'; $col <= 'I'; $col++) {
                        $sheet->getStyle($col . $dataStartRow . ':' . $col . $dataEndRow)
                            ->getNumberFormat()
                            ->setFormatCode('0.00');

                        // Alinear a la derecha
                        $sheet->getStyle($col . $dataStartRow . ':' . $col . $dataEndRow)->applyFromArray([
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
                        ]);
                    }
                }

                // ESTILO DEL FOOTER
                $footerStartRow = $highestRow - 4;
                if ($footerStartRow > 0) {
                    // Nota de SENASA
                    $sheet->getStyle('A' . $footerStartRow . ':I' . $footerStartRow)->applyFromArray([
                        'font' => ['italic' => true, 'size' => 9]
                    ]);

                    // Título OBSERVACIONES
                    $sheet->getStyle('A' . ($footerStartRow + 1) . ':I' . ($footerStartRow + 1))->applyFromArray([
                        'font' => ['bold' => true, 'size' => 11],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'FFFFFF']
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => '000000']
                            ]
                        ]
                    ]);

                    // Contenido de observaciones
                    $sheet->getStyle('A' . ($footerStartRow + 2) . ':I' . ($footerStartRow + 2))->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'CCCCCC']
                            ]
                        ],
                        'alignment' => ['wrapText' => true, 'vertical' => Alignment::VERTICAL_TOP]
                    ]);

                    // Información de generación
                    $sheet->getStyle('A' . ($footerStartRow + 3) . ':I' . ($footerStartRow + 3))->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'FFFDE7']
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'CCCCCC']
                            ]
                        ]
                    ]);

                    // Código del formulario
                    $sheet->getStyle('A' . ($footerStartRow + 4) . ':I' . ($footerStartRow + 4))->applyFromArray([
                        'font' => ['bold' => true, 'size' => 10],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
                    ]);
                }

                // AJUSTAR ANCHOS DE COLUMNAS
                $sheet->getColumnDimension('A')->setWidth(15); // TIPO
                $sheet->getColumnDimension('B')->setWidth(30); // AGROQUIMICO
                $sheet->getColumnDimension('C')->setWidth(15); // ENVASE COMERCIAL
                $sheet->getColumnDimension('D')->setWidth(12); // Casa 1
                $sheet->getColumnDimension('E')->setWidth(12); // Casa 2
                $sheet->getColumnDimension('F')->setWidth(12); // Casa 3
                $sheet->getColumnDimension('G')->setWidth(12); // Casa 4
                $sheet->getColumnDimension('H')->setWidth(12); // Casa 5
                $sheet->getColumnDimension('I')->setWidth(15); // PRECIO PROMEDIO
            }
        ];
    }
}
